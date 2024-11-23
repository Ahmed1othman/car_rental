<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the form for editing the first contact (used as settings).
     */
    public function edit()
    {
        // Retrieve the first contact record
        $contact = Contact::first();

        // Return the edit view with the contact data
        return view('pages.admin.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request)
    {
        // Retrieve the first contact record
        $contact = Contact::first();

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable|string|max:15',
            'alternative_phone' => 'nullable|string|max:15',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'snapchat' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'google_map_url' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:1000',
            'additional_info' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Update the contact
        $contact->update($request->all());

        // Redirect back with success message
        return redirect()->route('admin.contacts.edit')->with('success', 'Contact settings updated successfully.');
    }
}
