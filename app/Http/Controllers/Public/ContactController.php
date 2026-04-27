<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function show()
    {
        return Inertia::render('Public/Contact');
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:200',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        ContactMessage::create(array_merge($data, [
            'ip_address' => $req->ip(),
        ]));

        return back()->with('success', 'Your message has been sent! We will reply within 24 hours.');
    }
}
