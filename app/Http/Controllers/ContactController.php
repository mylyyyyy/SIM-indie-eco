<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // We will create this model next
use Illuminate\Support\Facades\Validator; // For manual validation if needed

class ContactController extends Controller
{
    /**
     * Store a newly created contact message in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Create a new Contact record
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // 3. Optionally, send an email notification, etc.
        // Mail::to('admin@example.com')->send(new NewContactMessage($contact));

        // 4. Return a success response
        return response()->json(['message' => 'Pesan Anda berhasil dikirim!'], 201);
    }
}
