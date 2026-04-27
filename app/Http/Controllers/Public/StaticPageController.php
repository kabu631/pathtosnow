<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Inertia\Inertia;

class StaticPageController extends Controller
{
    public function show(string $slug)
    {
        $page = StaticPage::where('slug', $slug)->firstOrFail();

        return Inertia::render('Public/StaticPage', [
            'page' => $page,
        ]);
    }
}
