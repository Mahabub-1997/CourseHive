<?php

namespace App\Http\Controllers\Web\Backend\CMS\ContactUs;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    // List all contacts with pagination
    public function index()
    {
        $contacts = ContactUs::latest()->paginate(10);
        return view('backend.layouts.contact_us.list', compact('contacts'));
    }

    // Show create form
    public function create()
    {
        return view('backend.layouts.contact_us.add');
    }

    // Store new contact
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:contact_us,email',
            'description' => 'nullable|string',
        ]);

        ContactUs::create($request->only(['name', 'email', 'description']));

        return redirect()->route('web-contactus.index')
            ->with('success', 'Contact added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $contact = ContactUs::findOrFail($id);
        return view('backend.layouts.contact_us.edit', compact('contact'));
    }

    // Update contact
    public function update(Request $request, $id)
    {
        $contact = ContactUs::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:contact_us,email,' . $contact->id,
            'description' => 'nullable|string',
        ]);

        $contact->update($request->only(['name', 'email', 'description']));

        return redirect()->route('web-contactus.index')
            ->with('message', 'Contact updated successfully!');
    }

    // Delete contact
    public function destroy($id)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->delete();

        return redirect()->route('web-contactus.index')
            ->with('message', 'Contact deleted successfully!');
    }
}
