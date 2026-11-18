<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('manage-users');

        $status = $request->query('status', 'pending');

        if (! in_array($status, ['pending', 'approved', 'all'], true)) {
            $status = 'pending';
        }

        $search = mb_substr(
            trim((string) $request->query('search', '')),
            0,
            100
        );

        $query = DB::table('comments');

        if ($status === 'pending') {
            $query->where(function ($query) {
                $query->whereNull('is_allowed')
                    ->orWhere('is_allowed', '<>', 1);
            });
        } elseif ($status === 'approved') {
            $query->where('is_allowed', 1);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('coments', 'like', '%' . $search . '%')
                    ->orWhere('post_title', 'like', '%' . $search . '%');
            });
        }

        $comments = $query->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $pendingCount = DB::table('comments')
            ->where(function ($query) {
                $query->whereNull('is_allowed')
                    ->orWhere('is_allowed', '<>', 1);
            })
            ->count();

        return view('admin.comments.index', compact(
            'comments',
            'status',
            'search',
            'pendingCount'
        ));
    }

    public function update(
        Request $request,
        string $comment
    ): RedirectResponse {
        Gate::authorize('manage-users');

        $request->validate([
            'is_allowed' => ['required', 'boolean'],
        ]);

        abort_unless(
            DB::table('comments')->where('id', $comment)->exists(),
            404
        );

        $approved = $request->boolean('is_allowed');

        DB::table('comments')
            ->where('id', $comment)
            ->update([
                'is_allowed' => $approved ? 1 : 0,
            ]);

        return back()->with(
            'status',
            $approved
                ? 'Comment approved.'
                : 'Comment moved to pending.'
        );
    }

    public function destroy(string $comment): RedirectResponse
    {
        Gate::authorize('manage-users');

        $deleted = DB::table('comments')
            ->where('id', $comment)
            ->delete();

        abort_unless($deleted, 404);

        return back()->with('status', 'Comment deleted.');
    }
}