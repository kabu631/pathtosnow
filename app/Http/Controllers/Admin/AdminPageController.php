<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Pages/Index', [
            'pages' => StaticPage::orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }

    public function edit(StaticPage $page)
    {
        return Inertia::render('Admin/Pages/Edit', ['page' => $page]);
    }

    public function update(Request $req, StaticPage $page)
    {
        $req->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'meta_description' => 'nullable|string|max:300',
            'show_in_footer'   => 'boolean',
            'sort_order'       => 'integer|min:0',
        ]);

        $page->update($req->only('title','content','meta_description','show_in_footer','sort_order'));

        return back()->with('success', "Page \"{$page->title}\" updated successfully.");
    }
}
