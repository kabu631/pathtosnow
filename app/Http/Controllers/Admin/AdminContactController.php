<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Inertia\Inertia;

class AdminContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Contact/Index', [
            'messages' => ContactMessage::latest()->paginate(20),
            'unreadCount' => ContactMessage::where('status','unread')->count(),
        ]);
    }

    public function show(ContactMessage $message)
    {
        $message->update(['status' => 'read']);
        return Inertia::render('Admin/Contact/Show', ['message' => $message]);
    }

    public function markReplied(ContactMessage $message)
    {
        $message->update(['status' => 'replied']);
        return back()->with('success', 'Message marked as replied.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect('/admin/contact')->with('success', 'Message deleted.');
    }
}
