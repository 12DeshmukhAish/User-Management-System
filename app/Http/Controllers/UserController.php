<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users',
            'mobile_no' => 'required|digits:10',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg',
            'password' => 'required|min:8',
        ]);

        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $validated['profile_pic'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile_no' => 'required|digits:10',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg',
            'password' => 'nullable|min:8',
        ]);

        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $validated['profile_pic'] = $path;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->profile_pic) {
            Storage::disk('public')->delete($user->profile_pic);
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function export()
    {
        $users = User::all();
        $csvFileName = 'users.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Name', 'Email', 'Mobile No', 'Profile Pic']);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->name,
                $user->email,
                $user->mobile_no,
                $user->profile_pic
            ]);
        }

        fclose($handle);
        return response()->stream(
            function() use ($handle) {
                fclose($handle);
            },
            200,
            $headers
        );
    }
}
