<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Notes;
use App\Models\Status;
use Illuminate\Http\Request;

class NotesController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $notes = Notes::with('user')->with('status')->paginate(20);

        return view('dashboard.notes.notesList', ['notes' => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $statuses = Status::all();

        return view('dashboard.notes.create', ['statuses' => $statuses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|min:1|max:64',
            'content' => 'required',
            'status_id' => 'required',
            'applies_to_date' => 'required|date_format:Y-m-d',
            'note_type' => 'required',
        ]);
        $user = auth()->user();
        $note = new Notes();
        $note->title = $request->input('title');
        $note->content = $request->input('content');
        $note->status_id = $request->input('status_id');
        $note->note_type = $request->input('note_type');
        $note->applies_to_date = $request->input('applies_to_date');
        $note->users_id = $user->id;
        $note->save();
        $request->session()->flash('message', 'Successfully created note');

        return redirect()->route('notes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $note = Notes::with('user')->with('status')->find($id);

        return view('dashboard.notes.noteShow', ['note' => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $note = Notes::find($id);
        $statuses = Status::all();

        return view('dashboard.notes.edit', ['statuses' => $statuses, 'note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        //var_dump('bazinga');
        //die();
        $validatedData = $request->validate([
            'title' => 'required|min:1|max:64',
            'content' => 'required',
            'status_id' => 'required',
            'applies_to_date' => 'required|date_format:Y-m-d',
            'note_type' => 'required',
        ]);
        $note = Notes::find($id);
        $note->title = $request->input('title');
        $note->content = $request->input('content');
        $note->status_id = $request->input('status_id');
        $note->note_type = $request->input('note_type');
        $note->applies_to_date = $request->input('applies_to_date');
        $note->save();
        $request->session()->flash('message', 'Successfully edited note');

        return redirect()->route('notes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $note = Notes::find($id);
        if ($note) {
            $note->delete();
        }

        return redirect()->route('notes.index');
    }
}
