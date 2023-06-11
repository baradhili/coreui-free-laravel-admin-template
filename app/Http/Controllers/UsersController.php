<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $you = auth()->user();
        $users = User::all();

        return view('dashboard.admin.usersList', compact('users', 'you'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $user = User::find($id);

        return view('dashboard.admin.userShow', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $user = User::find($id);

        return view('dashboard.admin.userEditForm', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:256',
            'email' => 'required|email|max:256',
        ]);
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        $request->session()->flash('message', 'Successfully updated user');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }

        return redirect()->route('users.index');
    }
}
