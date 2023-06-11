<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $emailTemplates = EmailTemplate::paginate(20);

        return view('dashboard.email.index', ['emailTemplates' => $emailTemplates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.email.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:64',
            'subject' => 'required|min:1|max:128',
            'content' => 'required|min:1',
        ]);
        $template = new EmailTemplate();
        $template->name = $request->input('name');
        $template->subject = $request->input('subject');
        $template->content = $request->input('content');
        $template->save();
        $request->session()->flash('message', 'Successfully created Email Template');

        return redirect()->route('mail.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $template = EmailTemplate::find($id);

        return view('dashboard.email.show', ['template' => $template]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $template = EmailTemplate::find($id);

        return view('dashboard.email.edit', ['template' => $template]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:64',
            'subject' => 'required|min:1|max:128',
            'content' => 'required|min:1',
        ]);
        $template = EmailTemplate::find($id);
        $template->name = $request->input('name');
        $template->subject = $request->input('subject');
        $template->content = $request->input('content');
        $template->save();
        $request->session()->flash('message', 'Successfully updated Email Template');

        return redirect()->route('mail.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request): RedirectResponse
    {
        $template = EmailTemplate::find($id);
        if ($template) {
            $template->delete();
        }
        $request->session()->flash('message', 'Successfully deleted Email Template');

        return redirect()->route('mail.index');
    }

    public function prepareSend($id): View
    {
        $template = EmailTemplate::find($id);

        return view('dashboard.email.send', ['template' => $template]);
    }

    public function send($id, Request $request): RedirectResponse
    {
        $template = EmailTemplate::find($id);
        Mail::send([], [], function ($message) use ($request, $template) {
            $message->to($request->input('email'));
            $message->subject($template->subject);
            $message->setBody($template->content, 'text/html');
        });
        $request->session()->flash('message', 'Successfully sended Email');

        return redirect()->route('mail.index');
    }
}
