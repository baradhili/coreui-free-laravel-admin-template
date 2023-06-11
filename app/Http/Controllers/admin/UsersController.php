<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
    }

    /**
     * Show the users list.
     */
    public function index(): View
    {
        $you = auth()->user();
        $users = User::all();

        return view('dashboard.admin.usersList', compact('users', 'you'));
    }

    /**
     *  Remove user
     *
     *  @param  int  $id
     */
    public function remove($id): RedirectResponse
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }

        return redirect()->route('adminUsers');
    }

    /**
     *  Show the form for editing the user.
     *
     *  @param  int  $id
     */
    public function editForm($id): View
    {
        $user = User::find($id);

        return view('dashboard.admin.userEditForm', compact('user'));
    }

    public function edit()
    {
    }
}
