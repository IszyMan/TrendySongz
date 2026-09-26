<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $artists = DB::table('artists')
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($q) use ($search) {
                    $q->where('ArtistsName', 'like', '%'.$search.'%')
                        ->orWhere('Stage_Name', 'like', '%'.$search.'%');
                })
            )
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.artists.index', compact('artists', 'search'));
    }

    public function create()
    {
        return view('admin.artists.create');
    }

    public function edit(Request $request, int $artist)
    {
        $row = DB::table('artists')->where('id', $artist)->firstOrFail();

        $this->authorizeArtist($request, $row);

        return view('admin.artists.edit', ['artist' => $row]);
    }

    private function authorizeArtist(Request $request, object $artist): void
    {
        abort_unless(
            (int) $request->user()->roleid === 1
                || (int) $artist->user_id === (int) $request->user()->id,
            403
        );
    }

    private function validateForm(Request $request, ?int $artistId = null): array
    {
        $stageNameRule = Rule::unique('artists', 'Stage_Name');

        if ($artistId !== null) {
            $stageNameRule->ignore($artistId, 'id');
        }

        return $request->validate([
            'ArtistsName' => ['required', 'string', 'max:100'],
            'Stage_Name' => [
                'required',
                'string',
                'max:100',
                $stageNameRule,
            ],
            'country_id' => [
                'required',
                Rule::in(['naija', 'ghana', 'african']),
            ],
            'Fullname' => ['nullable', 'string', 'max:100'],
            'RecordLabel' => ['nullable', 'string', 'max:100'],
            'Genres' => ['nullable', 'string', 'max:100'],
            'ArtistsProfile' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:5120'],
            'publish' => ['required', 'boolean'],
        ]);
    }

    private function formFields(Request $request, array $data): array
    {
        return [
            'ArtistsName' => $data['ArtistsName'],
            'Stage_Name' => $data['Stage_Name'],
            'Fullname' => $data['Fullname'] ?? null,
            'RecordLabel' => $data['RecordLabel'] ?? null,
            'Genres' => $data['Genres'] ?? null,
            'ArtistsProfile' => $data['ArtistsProfile'] ?? null,
            'country_id' => $data['country_id'],
            'IsPublished' => $request->boolean('publish') ? 'YES' : 'NO',
        ];
    }

    private function saveImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('images'));

        $image = $request->file('image');
        $base = Str::slug(
            pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
        );

        if ($base === '') {
            $base = 'artist-image';
        }

        $base = substr($base, 0, 75);
        $extension = strtolower($image->extension());
        $filename = $base.'.'.$extension;
        $number = 2;

        while (File::exists(public_path('images/'.$filename))) {
            $filename = $base.'-'.$number.'.'.$extension;
            $number++;
        }

        $image->move(public_path('images'), $filename);

        return $filename;
    }

    public function store(Request $request)
    {
        $data = $this->validateForm($request);
        $imageName = $this->saveImage($request);

        try {
            $lock = DB::selectOne(
                'SELECT GET_LOCK(?, 10) AS acquired',
                ['trendysongz_artist_id']
            );

            if ((int) $lock->acquired !== 1) {
                throw new \RuntimeException(
                    'Could not reserve an artiste ID. Please try again.'
                );
            }

            try {
                $result = DB::table('artists')
                    ->whereRaw("Artists_Id REGEXP '^TB[0-9]+$'")
                    ->selectRaw(
                        'MAX(CAST(SUBSTRING(Artists_Id, 3) AS UNSIGNED)) AS last_number'
                    )
                    ->first();

                $nextNumber = ((int) ($result->last_number ?? 0)) + 1;
                $artistKey = 'TB'.$nextNumber;

                DB::table('artists')->insert([
                    ...$this->formFields($request, $data),
                    'Artists_Id' => $artistKey,
                    'ProfilePic' => $imageName,
                    'posteb_by' => (string) $request->user()->id,
                    'user_id' => $request->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } finally {
                DB::select('SELECT RELEASE_LOCK(?)', [
                    'trendysongz_artist_id',
                ]);
            }
        } catch (\Throwable $exception) {
            if ($imageName !== null) {
                File::delete(public_path('images/'.$imageName));
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.artists.index')
            ->with('status', 'Artiste saved.');
    }

    public function update(Request $request, int $artist)
    {
        $row = DB::table('artists')->where('id', $artist)->firstOrFail();

        $this->authorizeArtist($request, $row);

        $data = $this->validateForm($request, $artist);
        $newImagePath = $this->saveImage($request);

        try {
            $fields = $this->formFields($request, $data);

            if ($newImagePath !== null) {
                $fields['ProfilePic'] = $newImagePath;
            }

            // The imported table changes created_at on UPDATE.
            $fields['created_at'] = $row->created_at;
            $fields['updated_at'] = now();

            DB::table('artists')
                ->where('id', $artist)
                ->update($fields);
        } catch (\Throwable $exception) {
            if ($newImagePath !== null) {
                File::delete(public_path('images/'.$newImagePath));
            }

            throw $exception;
        }

        if ($newImagePath !== null) {
            $oldImage = trim((string) $row->ProfilePic);

            if (
                $oldImage !== ''
                && basename($oldImage) === $oldImage
                && ! str_contains($oldImage, '\\')
            ) {
                File::delete(public_path('images/'.$oldImage));
            } elseif (str_starts_with($oldImage, 'artists/')) {
                File::delete(public_path('images/'.$oldImage));
            }
        }

        return redirect()
            ->route('admin.artists.index')
            ->with('status', 'Artiste updated.');
    }

    public function destroy(Request $request, int $artist)
    {
        $row = DB::table('artists')->where('id', $artist)->firstOrFail();

        $this->authorizeArtist($request, $row);

        if (
            DB::table('listing')
                ->where('Artists_Id', $row->Artists_Id)
                ->exists()
            || DB::table('albums')
                ->where('artist_id', $row->Artists_Id)
                ->exists()
        ) {
            return back()->withErrors([
                'artist' => 'This artiste has listings or albums. Remove those first.',
            ]);
        }

        DB::table('artists')->where('id', $artist)->delete();

        $oldImage = trim((string) $row->ProfilePic);

        if (
            $oldImage !== ''
            && basename($oldImage) === $oldImage
            && ! str_contains($oldImage, '\\')
        ) {
            File::delete(public_path('images/'.$oldImage));
        } elseif (str_starts_with($oldImage, 'artists/')) {
            File::delete(public_path('images/'.$oldImage));
        }

        return redirect()
            ->route('admin.artists.index')
            ->with('status', 'Artiste deleted.');
    }
}