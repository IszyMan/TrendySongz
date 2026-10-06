<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $albums = DB::table('albums as al')
            ->leftJoin(
                'artists as a',
                'a.Artists_Id',
                '=',
                'al.artist_id'
            )
            ->select(
                'al.*',
                'a.ArtistsName',
                'a.Stage_Name'
            )
            ->when(
                $search !== '',
                fn ($query) => $query->where(
                    function ($q) use ($search) {
                        $q->where(
                            'al.title',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'a.ArtistsName',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'a.Stage_Name',
                            'like',
                            '%'.$search.'%'
                        );
                    }
                )
            )
            ->orderByDesc('al.id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.albums.index',
            compact('albums', 'search')
        );
    }

    private function artists()
    {
        return DB::table('artists')
            ->select(
                'Artists_Id',
                'ArtistsName',
                'Stage_Name'
            )
            ->orderBy('ArtistsName')
            ->get();
    }

    public function create()
    {
        return view('admin.albums.create', [
            'artists' => $this->artists(),
        ]);
    }

    public function edit(Request $request, int $album)
    {
        $row = DB::table('albums')
            ->where('id', $album)
            ->firstOrFail();

        $this->authorizeAlbum($request, $row);

        return view('admin.albums.edit', [
            'album' => $row,
            'artists' => $this->artists(),
        ]);
    }

    private function authorizeAlbum(
        Request $request,
        object $album
    ): void {
        abort_unless(
            (int) $request->user()->roleid === 1
                || (int) $album->user_id
                    === (int) $request->user()->id,
            403
        );
    }

    private function validateForm(
        Request $request,
        bool $editing
    ): array {
        return $request->validate([
            'artist_id' => [
                'required',
                Rule::exists(
                    'artists',
                    'Artists_Id'
                ),
            ],
            'title' => [
                'required',
                'string',
                'max:5000',
            ],
            'featuring' => [
                'nullable',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'released_year' => [
                'required',
                'string',
                'max:36',
            ],
            'released_date' => [
                'required',
                'date',
            ],
            'show_ads' => [
                'required',
                'boolean',
            ],
            'image' => [
                $editing ? 'nullable' : 'required',
                'image',
                'max:5120',
            ],
            'publish' => [
                'required',
                'boolean',
            ],
        ]);
    }

    private function formFields(
        Request $request,
        array $data
    ): array {
        return [
            'artist_id' =>
                $data['artist_id'],
            'title' =>
                $data['title'],
            'featuring' =>
                $data['featuring'] ?? null,
            'description' =>
                $data['description'] ?? null,
            'released_year' =>
                $data['released_year'],
            'released_date' =>
                $data['released_date'],
            'show_ads' =>
                $request->boolean('show_ads')
                    ? 1
                    : 0,
            'IsPublished' =>
                $request->boolean('publish')
                    ? 'YES'
                    : 'NO',
        ];
    }

    private function saveImage(
        Request $request
    ): ?string {
        if (! $request->hasFile('image')) {
            return null;
        }

        File::ensureDirectoryExists(
            public_path('images')
        );

        $image = $request->file('image');
        $extension = strtolower(
            $image->extension()
        );

        $base = substr(
            Str::slug(
                pathinfo(
                    $image->getClientOriginalName(),
                    PATHINFO_FILENAME
                )
            ) ?: 'album-cover',
            0,
            80
        );

        $filename = $base.'.'.$extension;
        $number = 2;

        while (
            File::exists(
                public_path(
                    'images/'.$filename
                )
            )
        ) {
            $filename = $base
                .'-'
                .$number++
                .'.'
                .$extension;
        }

        $image->move(
            public_path('images'),
            $filename
        );

        return $filename;
    }

    public function store(Request $request)
    {
        $data = $this->validateForm(
            $request,
            false
        );

        $image = $this->saveImage(
            $request
        );

        try {
            DB::table('albums')->insert([
                ...$this->formFields(
                    $request,
                    $data
                ),
                'cover_url' => $image,
                'user_id' =>
                    $request->user()->id,
                'created_at' => today(),
            ]);
        } catch (\Throwable $exception) {
            if ($image !== null) {
                File::delete(
                    public_path(
                        'images/'.$image
                    )
                );
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.albums.index')
            ->with(
                'status',
                'Album saved.'
            );
    }

    public function update(
        Request $request,
        int $album
    ) {
        $row = DB::table('albums')
            ->where('id', $album)
            ->firstOrFail();

        $this->authorizeAlbum(
            $request,
            $row
        );

        $data = $this->validateForm(
            $request,
            true
        );

        if (
            $data['artist_id']
                !== $row->artist_id
            && DB::table('listing')
                ->where('album_id', $album)
                ->exists()
        ) {
            return back()
                ->withErrors([
                    'artist_id' =>
                        'This album has tracks. Remove them before changing its artist.',
                ])
                ->withInput();
        }

        $fields = $this->formFields(
            $request,
            $data
        );

        $image = $this->saveImage(
            $request
        );

        if ($image !== null) {
            $fields['cover_url'] = $image;
        }

        try {
            DB::table('albums')
                ->where('id', $album)
                ->update($fields);
        } catch (\Throwable $exception) {
            if ($image !== null) {
                File::delete(
                    public_path(
                        'images/'.$image
                    )
                );
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.albums.index')
            ->with(
                'status',
                'Album updated.'
            );
    }

    public function destroy(
        Request $request,
        int $album
    ) {
        $row = DB::table('albums')
            ->where('id', $album)
            ->firstOrFail();

        $this->authorizeAlbum(
            $request,
            $row
        );

        if (
            DB::table('listing')
                ->where('album_id', $album)
                ->exists()
            || DB::table('blogs')
                ->where('album_id', $album)
                ->exists()
        ) {
            return back()->withErrors([
                'album' =>
                    'This album has listings or blog posts. Remove those first.',
            ]);
        }

        DB::table('albums')
            ->where('id', $album)
            ->delete();

        return redirect()
            ->route('admin.albums.index')
            ->with(
                'status',
                'Album deleted.'
            );
    }
}