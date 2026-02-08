<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return "clicked";
        return view('user.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // public function update(Request $request)
    // {
    //     $user = $request->user();

    //     $validated = $request->validate([
    //         'name'  => ['required', 'string', 'max:120'],
    //         'phone' => ['nullable', 'string', 'max:25'],
    //     ]);

    //     $user->update($validated);

    //     return back()->with('success', 'Profile updated successfully.');
    // }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'phone'   => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }


    public function password(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function preferences(Request $request)
    {
        $validated = $request->validate([
            'notify_email' => ['nullable', 'boolean'],
            'notify_app'   => ['nullable', 'boolean'],
        ]);

        $request->user()->update([
            'notify_email' => (bool) ($validated['notify_email'] ?? false),
            'notify_app'   => (bool) ($validated['notify_app'] ?? false),
        ]);

        return back()->with('success', 'Preferences updated successfully.');
    }



    public function avatar(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB
        ]);

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar' => $path,
        ]);

        return back()->with('success', 'Profile photo updated successfully.');
    }
}
