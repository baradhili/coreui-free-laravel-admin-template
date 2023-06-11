<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Services\FormService;
use App\Services\RolesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //var_dump(DB::getSchemaBuilder()->getColumnListing('model'));

        return view('dashboard.form.index', ['forms' => Form::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.form.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'model' => 'required|min:1|max:128',
            'marker' => 'required',
        ]);
        $formService = new FormService();
        if ($request->has('marker') && $request->input('marker') === 'selectModel') {
            $model = DB::getSchemaBuilder()->getColumnListing($request->input('model'));
            if (empty($model)) {
                $request->session()->flash('message', 'Table not detected, or there is no columns in table');

                return view('dashboard.form.create');
            } else {
                $rolesService = new RolesService();

                return view('dashboard.form.create2', [
                    'columns' => $formService->getFormDataByModel($request->input('model')),
                    'options' => $formService->getFormOptions(),
                    'model' => $request->input('model'),
                    'roles' => $rolesService->get(),
                ]);
            }
        } else {
            $validatedData = $request->validate([
                'name' => 'required|min:1|max:128',
            ]);
            $formService->addNewForm($request->input('model'), $request->all());
            $request->session()->flash('message', 'Successfully added form');

            return redirect()->route('bread.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        return view('dashboard.form.show', [
            'form' => Form::find($id),
            'formFields' => FormField::where('form_id', '=', $id)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $formService = new FormService();
        $rolesService = new RolesService();

        return view('dashboard.form.edit', [
            'form' => Form::find($id),
            'formFields' => FormField::where('form_id', '=', $id)->get(),
            'options' => $formService->getFormOptions(),
            'roles' => $rolesService->get(),
            'formRoles' => $formService->getBreadRoles($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:128',
        ]);
        //$model = Models::find($request->input('model'));
        $formService = new FormService();
        $formService->updateForm($id, $request->all());
        $request->session()->flash('message', 'Successfully update form');

        return redirect()->route('bread.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, Request $request)
    {
        $form = Form::find($id);
        if ($request->has('marker')) {
            $formFields = FormField::where('form_id', '=', $id)->delete();
            $form->delete();
            $request->session()->flash('message', 'Successfully deleted form: '.$form->name);

            return redirect()->route('bread.index');
        } else {
            return view('dashboard.form.delete', ['id' => $id, 'formName' => $form->name]);
        }
    }
}
