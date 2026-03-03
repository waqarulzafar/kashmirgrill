<?php

namespace App\Http\Controllers;

use App\Support\EventCatalog;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventDetailPageController extends Controller
{
    public function __invoke(string $slug): View
    {
        $event = EventCatalog::find($slug);

        if (!$event) {
            throw new NotFoundHttpException();
        }

        $relatedEvents = collect(EventCatalog::all())
            ->reject(fn (array $item) => $item['slug'] === $slug)
            ->take(3)
            ->values()
            ->all();

        return view('pages.event-detail', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
        ]);
    }
}
