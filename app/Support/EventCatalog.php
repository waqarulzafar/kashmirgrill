<?php

namespace App\Support;

class EventCatalog
{
    public static function all(): array
    {
        return [
            [
                'slug' => 'ceremonies',
                'name' => __('Ceremonies'),
                'image' => 'assets/images/events/ceremonies.jpg',
                'description' => __('Elegant event dining for engagements, anniversaries, and family milestones with tailored service and menu planning.'),
                'summary' => __('Designed for meaningful family occasions that require a polished setting, coordinated timing, and dependable hospitality.'),
                'details' => __('Our ceremonies package supports curated menu planning, advance table arrangement, guest flow, and attentive service for milestone gatherings.'),
                'ideal_for' => [__('Engagement dinners'), __('Anniversary gatherings'), __('Family milestones')],
                'highlights' => [__('Tailored menu guidance'), __('Refined table setup'), __('Dedicated service pacing')],
            ],
            [
                'slug' => 'get-together',
                'name' => __('Get Together'),
                'image' => 'assets/images/events/get-together.jpg',
                'description' => __('Relaxed group dining for friends and families with shared platters, flexible seating, and an easy social atmosphere.'),
                'summary' => __('A practical choice for casual gatherings where guests want generous food, comfortable seating, and an uncomplicated booking experience.'),
                'details' => __('Ideal for weekend dinners, reunions, and informal celebrations with mixed menu preferences across grill, curry, and rice dishes.'),
                'ideal_for' => [__('Family reunions'), __('Friends dinners'), __('Weekend social meals')],
                'highlights' => [__('Sharing platters'), __('Flexible seating'), __('Mixed menu options')],
            ],
            [
                'slug' => 'meetings',
                'name' => __('Meetings'),
                'image' => 'assets/images/events/meetings.jpg',
                'description' => __('Comfortable arrangements for team lunches, client meetings, and small professional gatherings with efficient service.'),
                'summary' => __('Built for business-focused dining where timing, clarity, and a calm environment matter as much as food quality.'),
                'details' => __('Meeting reservations can include pre-selected menus, tea and coffee service, and paced dining suitable for work-related conversations.'),
                'ideal_for' => [__('Team lunches'), __('Client discussions'), __('Small business gatherings')],
                'highlights' => [__('Efficient lunch service'), __('Quiet table setup'), __('Tea and coffee add-ons')],
            ],
            [
                'slug' => 'conferences',
                'name' => __('Conferences'),
                'image' => 'assets/images/events/conferences.jpg',
                'description' => __('Structured event dining for larger professional groups with coordinated serving plans and dependable kitchen timing.'),
                'summary' => __('Well suited to organised group requirements where service timing and pre-planned meal arrangements are important.'),
                'details' => __('Conference bookings can be arranged around group schedules, with buffet or plated dining options depending on the event format.'),
                'ideal_for' => [__('Conference delegates'), __('Workshops'), __('Large corporate groups')],
                'highlights' => [__('Timed serving plans'), __('Group dining coordination'), __('Flexible meal formats')],
            ],
            [
                'slug' => 'valentines-day',
                'name' => __("Valentine's Day"),
                'image' => 'assets/images/events/valentines-day.jpg',
                'description' => __('A romantic dining experience with chef specials, a warm atmosphere, and thoughtful service for couples.'),
                'summary' => __('Prepared for guests looking for a memorable, intimate evening with signature food and a refined dining setting.'),
                'details' => __('Valentine bookings are best reserved in advance and can include celebration notes, preferred timing, and selected menu favourites.'),
                'ideal_for' => [__('Couples dining'), __('Anniversary-style evenings'), __('Special date nights')],
                'highlights' => [__('Chef specials'), __('Romantic setting'), __('Advance reservation support')],
            ],
            [
                'slug' => 'festivals',
                'name' => __('Festivals'),
                'image' => 'assets/images/events/festivals.jpg',
                'description' => __('Seasonal family dining for Eid, Ramadan, Easter, Christmas, and other festive gatherings throughout the year.'),
                'summary' => __('A strong option for larger family groups and seasonal celebrations that benefit from pre-planned seating and menu selection.'),
                'details' => __('Festival bookings can include sharing platters, family-style service, and advance menu coordination for larger tables.'),
                'ideal_for' => [__('Eid gatherings'), __('Ramadan iftar meals'), __('Holiday family dining')],
                'highlights' => [__('Seasonal menu support'), __('Large-table planning'), __('Family-style dining flow')],
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
