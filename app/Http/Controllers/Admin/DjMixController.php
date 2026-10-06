<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DjMixController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $mixes = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->select('m.*', 'd.dj_name')
            ->when(
                $search !== '',
                fn ($query) => $query->where(
                    function ($q) use ($search) {
                        $q->where(
                            'm.mix_title',
                            'like',
                            '%'.$search.'%'
                        )->orWhere(
                            'd.dj_name',
                            'like',
                            '%'.$search.'%'
                        );
                    }
                )
            )
            ->orderByDesc('m.id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.dj-mixes.index',
            compact('mixes', 'search')
        );
    }

    private function djOptions()
    {
        return DB::table('dj')
            ->select('id', 'dj_name')
            ->orderBy('dj_name')
            ->get();
    }

    public function create()
    {
        return view('admin.dj-mixes.create', [
            'djs' => $this->djOptions(),
        ]);
    }

    public function edit(Request $request, int $dj_mix)
    {
        $row = DB::table('dj_mixs')
            ->where('id', $dj_mix)
            ->firstOrFail();

        $this->authorizeMix($request, $row);

        return view('admin.dj-mixes.edit', [
            'mix' => $row,
            'djs' => $this->djOptions(),
        ]);
    }

    private function authorizeMix(
        Request $request,
        object $mix
    ): void {
        abort_unless(
            (int) $request->user()->roleid === 1
                || (int) $mix->user_id
                    === (int) $request->user()->id,
            403
        );
    }

    private function validateForm(
        Request $request,
        bool $editing
    ): array {
        return $request->validate([
            'dj_id' => [
                'required',
                'integer',
                Rule::exists('dj', 'id'),
            ],
            'mix_title' => [
                'required',
                'string',
                'max:50',
            ],
            'description1' => [
                'nullable',
                'string',
            ],
            'description2' => [
                'nullable',
                'string',
            ],
            'released_year' => [
                'nullable',
                'string',
                'max:36',
            ],
            'image' => [
                'nullable',
                'image',
                'max:5120',
            ],
            'back_image' => [
                'nullable',
                'image',
                'max:5120',
            ],
            'media' => [
                $editing ? 'nullable' : 'required',
                'file',
                'max:204800',
                'mimes:mp3,m4a,wav,aac',
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
            'dj_id' => $data['dj_id'],
            'mix_title' => $data['mix_title'],
            'description1' =>
                $data['description1'] ?? null,
            'description2' =>
                $data['description2'] ?? null,
            'released_year' =>
                $data['released_year'] ?? null,
            'slug' => Str::slug(
                $data['mix_title']
            ),
            'IsPublished' =>
                $request->boolean('publish')
                    ? 'YES'
                    : 'NO',
        ];
    }

    private function filename(
        string $original,
        string $extension,
        int $limit,
        callable $exists
    ): string {
        $base = Str::slug(
            pathinfo(
                $original,
                PATHINFO_FILENAME
            )
        ) ?: 'dj-mix';

        $base = substr(
            $base,
            0,
            $limit - strlen($extension) - 1
        );

        $name = $base.'.'.$extension;
        $number = 2;

        while ($exists($name)) {
            $suffix = '-'.$number++;

            $name = substr(
                $base,
                0,
                $limit
                    - strlen($extension)
                    - strlen($suffix)
                    - 1
            ).$suffix.'.'.$extension;
        }

        return $name;
    }

    private function saveImage(Request $request, string $field): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }
    
        File::ensureDirectoryExists(public_path('images'));
    
        $image = $request->file($field);
        $limit = $field === 'back_image' ? 150 : 100;
    
        $name = $this->filename(
            $image->getClientOriginalName(),
            strtolower($image->extension()),
            $limit,
            fn ($name) => File::exists(public_path('images/'.$name))
        );
    
        $image->move(public_path('images'), $name);
    
        return $name;
    }

    private function saveMedia(
        Request $request
    ): ?string {
        if (! $request->hasFile('media')) {
            return null;
        }

        $file = $request->file('media');
        $disk = Storage::disk('public');

        $name = $this->filename(
            $file->getClientOriginalName(),
            strtolower(
                $file->getClientOriginalExtension()
            ),
            100,
            fn ($name) => $disk->exists(
                'media/djmixs/'.$name
            )
        );

        $saved = $disk->putFileAs(
            'media/djmixs',
            $file,
            $name
        );

        if ($saved === false) {
            throw new \RuntimeException(
                'The DJ mix audio could not be saved.'
            );
        }

        return $name;
    }

    private function removeUploaded(
        ?string $cover,
        ?string $back,
        ?string $media
    ): void {
        if ($cover) {
            File::delete(
                public_path('images/'.$cover)
            );
        }

        if ($back) {
            File::delete(
                public_path('images/'.$back)
            );
        }

        if ($media) {
            Storage::disk('public')->delete(
                'media/djmixs/'.$media
            );
        }
    }

    private function removeUnusedMedia(
        ?string $name
    ): void {
        if (
            $name
            && basename($name) === $name
            && ! DB::table('dj_mixs')
                ->where('track_url', $name)
                ->exists()
        ) {
            Storage::disk('public')->delete(
                'media/djmixs/'.$name
            );
        }
    }

    public function store(Request $request)
    {
        $data = $this->validateForm(
            $request,
            false
        );

        $cover = null;
        $back = null;
        $media = null;

        try {
            $cover = $this->saveImage(
                $request,
                'image'
            );

            $back = $this->saveImage(
                $request,
                'back_image'
            );

            $media = $this->saveMedia(
                $request
            );

            DB::table('dj_mixs')->insert([
                ...$this->formFields(
                    $request,
                    $data
                ),
                'cover_url' => $cover,
                'back_cover' => $back,
                'track_url' => $media,
                'user_id' =>
                    $request->user()->id,
                'posted_by' => 159,
                'created_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            $this->removeUploaded(
                $cover,
                $back,
                $media
            );

            throw $exception;
        }

        return redirect()
            ->route('admin.dj-mixes.index')
            ->with(
                'status',
                'DJ mix saved.'
            );
    }

    public function update(
        Request $request,
        int $dj_mix
    ) {
        $row = DB::table('dj_mixs')
            ->where('id', $dj_mix)
            ->firstOrFail();

        $this->authorizeMix(
            $request,
            $row
        );

        $data = $this->validateForm(
            $request,
            true
        );

        $fields = $this->formFields(
            $request,
            $data
        );

        $cover = null;
        $back = null;
        $media = null;

        try {
            $cover = $this->saveImage(
                $request,
                'image'
            );

            $back = $this->saveImage(
                $request,
                'back_image'
            );

            $media = $this->saveMedia(
                $request
            );

            if ($cover !== null) {
                $fields['cover_url'] = $cover;
            }

            if ($back !== null) {
                $fields['back_cover'] = $back;
            }

            if ($media !== null) {
                $fields['track_url'] = $media;
            }

            // This legacy column changes automatically
            // on UPDATE unless its value is retained.
            $fields['created_at'] =
                $row->created_at;

            DB::table('dj_mixs')
                ->where('id', $dj_mix)
                ->update($fields);
        } catch (\Throwable $exception) {
            $this->removeUploaded(
                $cover,
                $back,
                $media
            );

            throw $exception;
        }

        if ($media !== null) {
            $this->removeUnusedMedia(
                $row->track_url
            );
        }

        return redirect()
            ->route('admin.dj-mixes.index')
            ->with(
                'status',
                'DJ mix updated.'
            );
    }

    public function destroy(
        Request $request,
        int $dj_mix
    ) {
        $row = DB::table('dj_mixs')
            ->where('id', $dj_mix)
            ->firstOrFail();

        $this->authorizeMix(
            $request,
            $row
        );

        DB::table('dj_mixs')
            ->where('id', $dj_mix)
            ->delete();

        $this->removeUnusedMedia(
            $row->track_url
        );

        return redirect()
            ->route('admin.dj-mixes.index')
            ->with(
                'status',
                'DJ mix deleted.'
            );
    }
}