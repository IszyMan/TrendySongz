<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DjController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $djs = DB::table('dj')
            ->when(
                $search !== '',
                fn ($query) => $query->where(
                    function ($q) use ($search) {
                        $q->where(
                            'dj_name',
                            'like',
                            '%'.$search.'%'
                        )->orWhere(
                            'fullname',
                            'like',
                            '%'.$search.'%'
                        );
                    }
                )
            )
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.djs.index',
            compact('djs', 'search')
        );
    }

    public function create()
    {
        return view('admin.djs.create');
    }

    public function edit(Request $request, int $dj)
    {
        $row = DB::table('dj')
            ->where('id', $dj)
            ->firstOrFail();

        $this->authorizeDj($request, $row);

        return view('admin.djs.edit', [
            'dj' => $row,
        ]);
    }

    private function authorizeDj(
        Request $request,
        object $dj
    ): void {
        abort_unless(
            (int) $request->user()->roleid === 1
                || (int) $dj->user_id
                    === (int) $request->user()->id,
            403
        );
    }

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'dj_name' => [
                'required',
                'string',
                'max:50',
            ],
            'fullname' => [
                'nullable',
                'string',
                'max:50',
            ],
            'place_of_birth' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'award' => [
                'nullable',
                'string',
            ],
            'endorsement' => [
                'nullable',
                'string',
            ],
            'country_id' => [
                'required',
                Rule::in([
                    'naija',
                    'ghana',
                    'african',
                ]),
            ],
            'order_id' => [
                'nullable',
                'integer',
                'min:0',
                'max:99999999999',
            ],
            'image' => [
                'nullable',
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
            'dj_name' =>
                $data['dj_name'],
            'fullname' =>
                $data['fullname'] ?? null,
            'place_of_birth' =>
                $data['place_of_birth'] ?? null,
            'award' =>
                $data['award'] ?? null,
            'endorsement' =>
                $data['endorsement'] ?? null,
            'country_id' =>
                $data['country_id'],
            'order_id' =>
                $data['order_id'] ?? '10000',
            'IsPublished' =>
                $request->boolean('publish')
                    ? 'YES'
                    : 'NO',
        ];
    }

    private function saveImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('images'));

        $image = $request->file('image');
        $extension = strtolower($image->extension());

        $base = Str::slug(
            pathinfo(
                $image->getClientOriginalName(),
                PATHINFO_FILENAME
            )
        ) ?: 'dj';

        // The photo database column is varchar(50).
        $base = substr($base, 0, 50 - strlen($extension) - 1);
        $filename = $base.'.'.$extension;
        $number = 2;

        // Keep an existing image instead of overwriting it.
        while (File::exists(public_path('images/'.$filename))) {
            $suffix = '-'.$number;
            $shortBase = substr(
                $base,
                0,
                50 - strlen($extension) - strlen($suffix) - 1
            );

            $filename = $shortBase.$suffix.'.'.$extension;
            $number++;
        }

        $image->move(public_path('images'), $filename);

        return $filename;
    }

    private function deleteImage(
        ?string $filename
    ): void {
        // Avoid deleting older images that may be
        // referenced by another record.
        if (
            $filename
            && str_starts_with(
                $filename,
                'admin-'
            )
            && basename($filename)
                === $filename
            && ! str_contains(
                $filename,
                '\\'
            )
        ) {
            File::delete(
                public_path(
                    'images/dj/'.$filename
                )
            );
        }
    }

    public function store(Request $request)
    {
        $data = $this->validateForm(
            $request
        );

        $image = $this->saveImage(
            $request
        );

        try {
            $djId = DB::table('dj')->insertGetId([
                ...$this->formFields(
                    $request,
                    $data
                ),
                'photo' =>
                    $image,
                'user_id' =>
                    $request->user()->id,
                'posted_by' =>
                    $request->user()->id,
                'created_at' =>
                    now(),
                'updated_at' =>
                    today(),
            ]);
        } catch (\Throwable $exception) {
            $this->deleteImage(
                $image
            );

            throw $exception;
        }

        return redirect()
            ->route('admin.djs.edit', $djId)
            ->with('status', 'DJ created successfully.');
    }

    public function update(
        Request $request,
        int $dj
    ) {
        $row = DB::table('dj')
            ->where('id', $dj)
            ->firstOrFail();

        $this->authorizeDj(
            $request,
            $row
        );

        $data = $this->validateForm(
            $request
        );

        $image = $this->saveImage(
            $request
        );

        $fields = $this->formFields(
            $request,
            $data
        );

        if ($image !== null) {
            $fields['photo'] = $image;
        }

        $fields['updated_at'] = today();

        try {
            DB::table('dj')
                ->where('id', $dj)
                ->update($fields);
        } catch (\Throwable $exception) {
            $this->deleteImage(
                $image
            );

            throw $exception;
        }

        if ($image !== null) {
            $this->deleteImage(
                $row->photo
            );
        }

        return redirect()
            ->route('admin.djs.edit', $dj)
            ->with('status', 'DJ updated successfully.');
    }

    public function destroy(
        Request $request,
        int $dj
    ) {
        $row = DB::table('dj')
            ->where('id', $dj)
            ->firstOrFail();

        $this->authorizeDj(
            $request,
            $row
        );

        if (
            DB::table('dj_mixs')
                ->where('dj_id', $dj)
                ->exists()
        ) {
            return back()->withErrors([
                'dj' =>
                    'This DJ has mixtapes. Remove those first.',
            ]);
        }

        DB::table('dj')
            ->where('id', $dj)
            ->delete();

        $this->deleteImage(
            $row->photo
        );

        return redirect()
            ->route('admin.djs.index')
            ->with(
                'status',
                'DJ deleted.'
            );
    }
}