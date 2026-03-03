<?php

namespace App\Support;

class EventCatalog
{
    public static function all(): array
    {
        return [
            [
                'slug' => 'ceremonies',
                'name' => 'Ceremonies',
                'image' => 'assets/images/events/ceremonies.jpg',
                'description' => 'Elegant event dining for engagements, anniversaries, and family milestones with tailored service and menu planning.',
                'summary' => 'Designed for meaningful family occasions that require a polished setting, coordinated timing, and dependable hospitality.',
                'details' => 'Our ceremonies package supports curated menu planning, advance table arrangement, guest flow, and attentive service for milestone gatherings.',
                'ideal_for' => ['Engagement dinners', 'Anniversary gatherings', 'Family milestones'],
                'highlights' => ['Tailored menu guidance', 'Refined table setup', 'Dedicated service pacing'],
            ],
            [
                'slug' => 'get-together',
                'name' => 'Get Together',
                'image' => 'assets/images/events/get-together.jpg',
                'description' => 'Relaxed group dining for friends and families with shared platters, flexible seating, and an easy social atmosphere.',
                'summary' => 'A practical choice for casual gatherings where guests want generous food, comfortable seating, and an uncomplicated booking experience.',
                'details' => 'Ideal for weekend dinners, reunions, and informal celebrations with mixed menu preferences across grill, curry, and rice dishes.',
                'ideal_for' => ['Family reunions', 'Friends dinners', 'Weekend social meals'],
                'highlights' => ['Sharing platters', 'Flexible seating', 'Mixed menu options'],
            ],
            [
                'slug' => 'meetings',
                'name' => 'Meetings',
                'image' => 'assets/images/events/meetings.jpg',
                'description' => 'Comfortable arrangements for team lunches, client meetings, and small professional gatherings with efficient service.',
                'summary' => 'Built for business-focused dining where timing, clarity, and a calm environment matter as much as food quality.',
                'details' => 'Meeting reservations can include pre-selected menus, tea and coffee service, and paced dining suitable for work-related conversations.',
                'ideal_for' => ['Team lunches', 'Client discussions', 'Small business gatherings'],
                'highlights' => ['Efficient lunch service', 'Quiet table setup', 'Tea and coffee add-ons'],
            ],
            [
                'slug' => 'conferences',
                'name' => 'Conferences',
                'image' => 'assets/images/events/conferences.jpg',
                'description' => 'Structured event dining for larger professional groups with coordinated serving plans and dependable kitchen timing.',
                'summary' => 'Well suited to organised group requirements where service timing and pre-planned meal arrangements are important.',
                'details' => 'Conference bookings can be arranged around group schedules, with buffet or plated dining options depending on the event format.',
                'ideal_for' => ['Conference delegates', 'Workshops', 'Large corporate groups'],
                'highlights' => ['Timed serving plans', 'Group dining coordination', 'Flexible meal formats'],
            ],
            [
                'slug' => 'valentines-day',
                'name' => "Valentine's Day",
                'image' => 'assets/images/events/valentines-day.jpg',
                'description' => 'A romantic dining experience with chef specials, a warm atmosphere, and thoughtful service for couples.',
                'summary' => 'Prepared for guests looking for a memorable, intimate evening with signature food and a refined dining setting.',
                'details' => 'Valentine bookings are best reserved in advance and can include celebration notes, preferred timing, and selected menu favourites.',
                'ideal_for' => ['Couples dining', 'Anniversary-style evenings', 'Special date nights'],
                'highlights' => ['Chef specials', 'Romantic setting', 'Advance reservation support'],
            ],
            [
                'slug' => 'festivals',
                'name' => 'Festivals',
                'image' => 'assets/images/events/festivals.jpg',
                'description' => 'Seasonal family dining for Eid, Ramadan, Easter, Christmas, and other festive gatherings throughout the year.',
                'summary' => 'A strong option for larger family groups and seasonal celebrations that benefit from pre-planned seating and menu selection.',
                'details' => 'Festival bookings can include sharing platters, family-style service, and advance menu coordination for larger tables.',
                'ideal_for' => ['Eid gatherings', 'Ramadan iftar meals', 'Holiday family dining'],
                'highlights' => ['Seasonal menu support', 'Large-table planning', 'Family-style dining flow'],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        foreach (static::all() as $event) {
            if ($event['slug'] === $slug) {
                return $event;
            }
        }

        return null;
    }
}
