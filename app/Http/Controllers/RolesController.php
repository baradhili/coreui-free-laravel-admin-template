<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Menurole;
use App\Models\RoleHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = DB::table('roles')
        ->leftJoin('role_hierarchy', 'roles.id', '=', 'role_hierarchy.role_id')
        ->select('roles.*', 'role_hierarchy.hierarchy')
        ->orderBy('hierarchy', 'asc')
        ->get();

        return view('dashboard.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function moveUp(Request $request): RedirectResponse
    {
        $element = RoleHierarchy::where('role_id', '=', $request->input('id'))->first();
        $switchElement = RoleHierarchy::where('hierarchy', '<', $element->hierarchy)
            ->orderBy('hierarchy', 'desc')->first();
        if (! empty($switchElement)) {
            $temp = $element->hierarchy;
            $element->hierarchy = $switchElement->hierarchy;
            $switchElement->hierarchy = $temp;
            $element->save();
            $switchElement->save();
        }

        return redirect()->route('roles.index');
    }

    public function moveDown(Request $request): RedirectResponse
    {
        $element = RoleHierarchy::where('role_id', '=', $request->input('id'))->first();
        $switchElement = RoleHierarchy::where('hierarchy', '>', $element->hierarchy)
            ->orderBy('hierarchy', 'asc')->first();
        if (! empty($switchElement)) {
            $temp = $element->hierarchy;
            $element->hierarchy = $switchElement->hierarchy;
            $switchElement->hierarchy = $temp;
            $element->save();
            $switchElement->save();
        }

        return redirect()->route('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();
        $hierarchy = RoleHierarchy::select('hierarchy')
        ->orderBy('hierarchy', 'desc')->first();
        if (empty($hierarchy)) {
            $hierarchy = 0;
        } else {
            $hierarchy = $hierarchy['hierarchy'];
        }
        $hierarchy = ((int) $hierarchy) + 1;
        $roleHierarchy = new RoleHierarchy();
        $roleHierarchy->role_id = $role->id;
        $roleHierarchy->hierarchy = $hierarchy;
        $roleHierarchy->save();
        $request->session()->flash('message', 'Successfully created role');

        return redirect()->route('roles.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        return view('dashboard.roles.show', [
            'role' => Role::where('id', '=', $id)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        return view('dashboard.roles.edit', [
            'role' => Role::where('id', '=', $id)->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $role = Role::where('id', '=', $id)->first();
        $role->name = $request->input('name');
        $role->save();
        $request->session()->flash('message', 'Successfully updated role');

        return redirect()->route('roles.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request): View
    {
        $role = Role::where('id', '=', $id)->first();
        $roleHierarchy = RoleHierarchy::where('role_id', '=', $id)->first();
        $menuRole = Menurole::where('role_name', '=', $role->name)->first();
        if (! empty($menuRole)) {
            $request->session()->flash('message', "Can't delete. Role has assigned one or more menu elements.");
            $request->session()->flash('back', 'roles.index');

            return view('dashboard.shared.universal-info');
        } else {
            $role->delete();
            $roleHierarchy->delete();
            $request->session()->flash('message', 'Successfully deleted role');
            $request->session()->flash('back', 'roles.index');

            return view('dashboard.shared.universal-info');
        }
    }
}
