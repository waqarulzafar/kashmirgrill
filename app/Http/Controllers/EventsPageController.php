<?php

namespace App\Http\Controllers;

use App\Support\EventCatalog;
use Illuminate\Contracts\View\View;

class EventsPageController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.events', [
            'events' => EventCatalog::all(),
        ]);
    }
}
