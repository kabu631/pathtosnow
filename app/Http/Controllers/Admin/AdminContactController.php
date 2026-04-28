<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\MailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Contact/Index', [
            'messages'    => ContactMessage::latest()->paginate(20),
            'unreadCount' => ContactMessage::where('status', 'unread')->count(),
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

    public function sendReply(Request $req, ContactMessage $message)
    {
        $req->validate(['reply_text' => 'required|string|max:5000']);
        $sent = app(MailService::class)->sendContactReply($message, $req->reply_text);
        $message->update(['status' => 'replied']);
        return back()->with('success', $sent ? 'Reply sent successfully!' : 'Reply saved, but email could not be delivered. Check SMTP settings.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect('/admin/contact')->with('success', 'Message deleted.');
    }
}
