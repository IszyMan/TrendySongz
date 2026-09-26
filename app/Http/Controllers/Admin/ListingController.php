<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        if (! in_array($type, ['audio', 'video'], true)) {
            $type = null;
        }

        $search = trim((string) $request->query('q', ''));

        $rows = DB::table('listing as l')
            ->leftJoin('artists as a', 'a.Artists_Id', '=', 'l.Artists_Id')
            ->select('l.*', 'a.ArtistsName')
            ->when(
                $type,
                fn ($query) => $query->where('l.ListingType', $type)
            )
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($q) use ($search) {
                    $q->where('l.TrackTitle', 'like', '%'.$search.'%')
                        ->orWhere('a.ArtistsName', 'like', '%'.$search.'%');
                })
            )
            ->orderByDesc('l.id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.listings.index', compact('rows', 'type', 'search'));
    }

    private function formOptions(): array
    {
        return [
            'artists' => DB::table('artists')
                ->select('Artists_Id', 'ArtistsName', 'Stage_Name')
                ->where('IsPublished', 'YES')
                ->orderBy('ArtistsName')
                ->get(),

            'albums' => DB::table('albums')
                ->select('id', 'artist_id', 'title')
                ->where('IsPublished', 'YES')
                ->orderByDesc('id')
                ->get(),
        ];
    }

    public function create()
    {
        return view('admin.listings.create', $this->formOptions());
    }

    public function edit(Request $request, int $listing)
    {
        $row = DB::table('listing')->where('id', $listing)->firstOrFail();

        $this->authorizeListing($request, $row);

        return view('admin.listings.edit', [
            ...$this->formOptions(),
            'row' => $row,
        ]);
    }

    private function authorizeListing(Request $request, object $row): void
    {
        abort_unless(
            (int) $request->user()->roleid === 1
                || (int) $row->user_id === (int) $request->user()->id,
            403
        );
    }

    private function validateForm(Request $request, bool $editing): array
    {
        return $request->validate([
            'Artists_Id' => [
                'required',
                Rule::exists('artists', 'Artists_Id')->where('IsPublished', 'YES'),
            ],
            'album_id' => ['nullable', 'integer', 'exists:albums,id'],
            'country_id' => ['required', Rule::in(['naija', 'ghana', 'african'])],
            'ListingType' => ['required', Rule::in(['audio', 'video'])],
            'TrackTitle' => ['required', 'string', 'max:1000'],
            'Featuring' => ['nullable', 'string', 'max:500'],
            'YearOfRelease' => ['nullable', 'string', 'max:100'],
            'producedby' => ['nullable', 'string', 'max:500'],
            'directedby' => ['nullable', 'string', 'max:36'],
            'introduction' => ['nullable', 'string'],
            'TrackInfo' => ['nullable', 'string', 'max:5000'],
            'trackinfo1' => ['nullable', 'string', 'max:5000'],
            'trackinfo2' => ['nullable', 'string', 'max:5000'],
            'track_number' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:5120'],
            'media' => [
                $editing ? 'nullable' : 'required',
                'file',
                'max:204800',
                'mimes:mp3,m4a,wav,aac,mp4,webm,mov',
            ],
            'isgospel' => ['sometimes', 'boolean'],
            'publish' => ['sometimes', 'boolean'],
        ]);
    }

    private function artistAndAlbum(array $data): array
    {
        $artist = DB::table('artists')
            ->where('Artists_Id', $data['Artists_Id'])
            ->where('IsPublished', 'YES')
            ->firstOrFail();

        $album = null;

        if (! empty($data['album_id'])) {
            $album = DB::table('albums')
                ->where('id', $data['album_id'])
                ->where('artist_id', $data['Artists_Id'])
                ->where('IsPublished', 'YES')
                ->first();

            if (! $album) {
                throw ValidationException::withMessages([
                    'album_id' => 'Choose a published album belonging to the selected artist.',
                ]);
            }
        }

        return [$artist, $album];
    }

    private function mediaExtension(Request $request, string $type): ?string
    {
        if (! $request->hasFile('media')) {
            return null;
        }

        $extension = strtolower(
            $request->file('media')->getClientOriginalExtension()
        );

        $allowed = $type === 'audio'
            ? ['mp3', 'm4a', 'wav', 'aac']
            : ['mp4', 'webm', 'mov'];

        if (! in_array($extension, $allowed, true)) {
            throw ValidationException::withMessages([
                'media' => 'Choose a supported file matching Audio or Video.',
            ]);
        }

        return $extension;
    }

    private function availableName(
        string $directory,
        string $originalName,
        string $extension,
        bool $publicImage
    ): string {
        $base = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));

        if ($base === '') {
            $base = 'trendysongz-file';
        }

        $base = substr($base, 0, 80);
        $name = $base.'.'.$extension;
        $number = 2;

        while (
            $publicImage
                ? File::exists(public_path($directory.'/'.$name))
                : Storage::disk('public')->exists($directory.'/'.$name)
        ) {
            $name = $base.'-'.$number.'.'.$extension;
            $number++;
        }

        return $name;
    }

    private function listingFields(
        Request $request,
        array $data,
        object $artist,
        ?object $album
    ): array {
        return [
            'Artists_Id' => $data['Artists_Id'],
            'album_id' => $album->id ?? 0,
            'AlbumName' => $album->title ?? null,
            'country_id' => $data['country_id'],
            'ListingType' => $data['ListingType'],
            'TrackTitle' => $data['TrackTitle'],
            'Featuring' => $data['Featuring'] ?? null,
            'YearOfRelease' => $data['YearOfRelease'] ?? null,
            'producedby' => $data['producedby'] ?? null,
            'directedby' => $data['directedby'] ?? null,
            'introduction' => $data['introduction'] ?? null,
            'TrackInfo' => $data['TrackInfo'] ?? null,
            'trackinfo1' => $data['trackinfo1'] ?? null,
            'trackinfo2' => $data['trackinfo2'] ?? null,
            'track_number' => $data['track_number'] ?? 1,
            'slug' => Str::slug(
                ($artist->ArtistsName ?: $artist->Stage_Name)
                    .' '.$data['TrackTitle']
            ),
            'isgospel' => $request->boolean('isgospel') ? 1 : 0,
            'IsPublished' => $request->boolean('publish') ? 'YES' : 'NO',
        ];
    }

    public function store(Request $request)
    {
        $data = $this->validateForm($request, false);
        [$artist, $album] = $this->artistAndAlbum($data);
        $extension = $this->mediaExtension($request, $data['ListingType']);

        $imageName = null;
        $mediaPath = null;

        try {
            if ($request->hasFile('image')) {
                File::ensureDirectoryExists(public_path('images'));

                $image = $request->file('image');
                $imageName = $this->availableName(
                    'images',
                    $image->getClientOriginalName(),
                    strtolower($image->extension()),
                    true
                );

                $image->move(public_path('images'), $imageName);
            }

            $media = $request->file('media');
            $directory = 'media/'.$data['ListingType'];

            $mediaName = $this->availableName(
                $directory,
                $media->getClientOriginalName(),
                $extension,
                false
            );

            $mediaPath = $media->storeAs($directory, $mediaName, 'public');

            if ($mediaPath === false) {
                throw new \RuntimeException('The media file could not be saved.');
            }

            DB::table('listing')->insert([
                ...$this->listingFields($request, $data, $artist, $album),
                'CoverUrl' => $imageName,
                'TrackUrl' => $mediaName,
                'user_id' => $request->user()->id,
                'posted_by' => $request->user()->id,
            ]);
        } catch (\Throwable $exception) {
            if ($imageName !== null) {
                File::delete(public_path('images/'.$imageName));
            }

            if ($mediaPath !== null) {
                Storage::disk('public')->delete($mediaPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.listings.index')
            ->with('status', 'Listing saved.');
    }

    public function update(Request $request, int $listing)
    {
        $row = DB::table('listing')->where('id', $listing)->firstOrFail();
        $this->authorizeListing($request, $row);

        $data = $this->validateForm($request, true);
        [$artist, $album] = $this->artistAndAlbum($data);

        if (
            $data['ListingType'] !== $row->ListingType
            && ! $request->hasFile('media')
        ) {
            throw ValidationException::withMessages([
                'media' => 'Upload replacement media when changing Audio to Video or Video to Audio.',
            ]);
        }

        $extension = $this->mediaExtension($request, $data['ListingType']);

        $newImageName = null;
        $newMediaPath = null;

        try {
            if ($request->hasFile('image')) {
                File::ensureDirectoryExists(public_path('images'));

                $image = $request->file('image');
                $newImageName = $this->availableName(
                    'images',
                    $image->getClientOriginalName(),
                    strtolower($image->extension()),
                    true
                );

                $image->move(public_path('images'), $newImageName);
            }

            if ($request->hasFile('media')) {
                $media = $request->file('media');
                $directory = 'media/'.$data['ListingType'];

                $mediaName = $this->availableName(
                    $directory,
                    $media->getClientOriginalName(),
                    $extension,
                    false
                );

                $newMediaPath = $media->storeAs(
                    $directory,
                    $mediaName,
                    'public'
                );

                if ($newMediaPath === false) {
                    throw new \RuntimeException('The media file could not be saved.');
                }
            }

            $fields = $this->listingFields($request, $data, $artist, $album);

            if ($newImageName !== null) {
                $fields['CoverUrl'] = $newImageName;
            }

            if ($newMediaPath !== null) {
                $fields['TrackUrl'] = $mediaName;
            }

            // The imported listing table updates created_at on UPDATE.
            // Preserve the original posting date.
            $fields['created_at'] = $row->created_at;

            DB::table('listing')->where('id', $listing)->update($fields);
        } catch (\Throwable $exception) {
            if ($newImageName !== null) {
                File::delete(public_path('images/'.$newImageName));
            }

            if ($newMediaPath !== null) {
                Storage::disk('public')->delete($newMediaPath);
            }

            throw $exception;
        }

        if ($newImageName !== null) {
            $oldCover = (string) $row->CoverUrl;

            if (
                $oldCover !== ''
                && ! str_contains($oldCover, '/')
                && ! str_contains($oldCover, '\\')
            ) {
                File::delete(public_path('images/'.$oldCover));
            } elseif (str_starts_with($oldCover, 'listing/')) {
                File::delete(public_path('images/'.$oldCover));
            }
        }

        if ($newMediaPath !== null) {
            $oldMediaPath = $this->localMediaPath(
                $row->TrackUrl,
                $row->ListingType
            );

            if ($oldMediaPath !== null) {
                Storage::disk('public')->delete($oldMediaPath);
            }
        }

        return redirect()
            ->route('admin.listings.index')
            ->with('status', 'Listing updated.');
    }

    public function destroy(Request $request, int $listing)
    {
        $row = DB::table('listing')->where('id', $listing)->firstOrFail();
        $this->authorizeListing($request, $row);

        DB::table('listing')->where('id', $listing)->delete();

        $cover = (string) $row->CoverUrl;

        if (
            $cover !== ''
            && ! str_contains($cover, '/')
            && ! str_contains($cover, '\\')
        ) {
            File::delete(public_path('images/'.$cover));
        } elseif (str_starts_with($cover, 'listing/')) {
            File::delete(public_path('images/'.$cover));
        }

        $mediaPath = $this->localMediaPath(
            $row->TrackUrl,
            $row->ListingType
        );

        if ($mediaPath !== null) {
            Storage::disk('public')->delete($mediaPath);
        }

        return redirect()
            ->route('admin.listings.index')
            ->with('status', 'Listing deleted.');
    }

    private function localMediaPath(?string $trackUrl, ?string $type): ?string
    {
        $trackUrl = trim((string) $trackUrl);

        if (
            ! in_array($type, ['audio', 'video'], true)
            || $trackUrl === ''
        ) {
            return null;
        }

        if (
            basename($trackUrl) === $trackUrl
            && ! str_contains($trackUrl, '\\')
        ) {
            return 'media/'.$type.'/'.$trackUrl;
        }

        $prefix = 'storage/media/'.$type.'/';

        if (str_starts_with($trackUrl, $prefix)) {
            return substr($trackUrl, strlen('storage/'));
        }

        return null;
    }
}