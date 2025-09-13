<?php

namespace App\Http\Controllers\API\CMS;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // GET /contacts
    public function index()
    {
        return response()->json(Contact::all());
    }

    // POST /contacts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_number' => 'required|string|max:20',
            'email_address'  => 'required|email|unique:contacts,email_address',
            'location'       => 'required|string|max:255',
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully!',
            'data'    => $contact,
        ], 201);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(null, 204);
    }
}
