<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('users as u')->leftJoin('roles as r', 'r.id', '=', 'u.roleid')
            ->select('u.id', 'u.name', 'u.email', 'u.roleid', 'r.name as role_name')
            ->orderByDesc('u.id')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'string', 'min:12'],
            'roleid' => ['required', Rule::in(['2', '3'])],
        ]);

        DB::table('users')->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'roleid' => $data['roleid'],
            'ref' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function destroy(Request $request, int $user)
    {
        $account = DB::table('users')->where('id', $user)->firstOrFail();
        abort_if((int) $account->id === (int) $request->user()->id || (int) $account->roleid === 1, 403);

        foreach (['listing', 'artists', 'albums', 'dj', 'dj_mixs', 'blogs'] as $table) {
            if (DB::table($table)->where('user_id', $user)->exists()) {
                return back()->withErrors(['user' => 'This user has uploaded content. Reassign their content before deleting the account.']);
            }
        }

        DB::table('users')->where('id', $user)->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}
