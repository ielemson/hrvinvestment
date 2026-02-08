<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:50', 'unique:roles,name'],
        ]);

        Role::create([
            'name' => strtolower($validated['name']),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        // optional: show users to assign this role to
        $users = User::orderBy('name')->get();
        $roleUserIds = $role->users()->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role','users','roleUserIds'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:50', Rule::unique('roles','name')->ignore($role->id)],
            'users' => ['nullable','array'],
            'users.*' => ['integer', Rule::exists('users','id')],
        ]);

        $role->update([
            'name' => strtolower($validated['name']),
        ]);

        // Assign users to this role (SYNC role membership)
        $users = User::whereIn('id', $validated['users'] ?? [])->get();

        // Remove this role from everyone first
        User::role($role->name)->get()->each(fn($u) => $u->removeRole($role->name));

        // Assign to selected users
        foreach ($users as $user) {
            $user->assignRole($role->name);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }
}
