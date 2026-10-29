<?php

namespace Flynt\Components\ListingEvents;

use Flynt\Event;
use Flynt\Utils\Asset;

function getACFLayout()
{
    return [
        'name' => 'listingEvents',
        'label' => __('Event Listing', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text',
                'default_value' => __('Programm', 'flynt'),
            ],
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
                'required' => 0,
            ],
        ],
    ];
}

/**
 * One list row per published event. Everything the row renders is resolved
 * here; `filters` holds the stable keys the client side matches against —
 * see index.twig (Alpine) for the filtering itself.
 */
function buildEntry($post, array $config)
{
    $dates = array_values((array) (get_field('dates', $post->ID) ?: []));
    $programTypes = (array) (get_field('programTypes', $post->ID) ?: []);
    $sectors = (array) (get_field('sectors', $post->ID) ?: []);
    $audiences = (array) (get_field('audiences', $post->ID) ?: []);
    $accessibility = (array) (get_field('accessibility', $post->ID) ?: []);
    $accessible = Event\isAccessible($accessibility);

    // Badge glyph follows the entry's format, as on the map pins.
    $iconFile = Event\getFormatIcon($post->ID);

    $imageId = get_post_thumbnail_id($post) ?: 0;
    if (!$imageId) {
        $featured = get_field('featuredImage', $post->ID);
        $imageId = is_array($featured) ? ($featured['ID'] ?? 0) : (int) $featured;
    }

    $schedule = Event\getScheduleRows($post->ID);

    $title = get_the_title($post);
    $org = (string) (get_field('orgName', $post->ID) ?: '');
    $addressLines = Event\getAddressLines($post->ID);
    // An entry can carry several program types; the row prints them all.
    $programTypeLabels = array_values(array_filter(array_map(fn ($key) => $config['programTypes'][$key] ?? '', $programTypes)));
    $sectorLabels = array_values(array_filter(array_map(fn ($key) => $config['sectors'][$key] ?? '', $sectors)));

    return [
        'id'               => $post->ID,
        'title'            => $title,
        'link'             => get_permalink($post),
        'image'            => $imageId ? wp_get_attachment_image_url($imageId, 'medium_large') : '',
        'imageAlt'         => $imageId ? (string) get_post_meta($imageId, '_wp_attachment_image_alt', true) : '',
        'icon'             => Asset::requireUrl('assets/icons/event/' . $iconFile),
        'schedule'         => $schedule,
        'programTypeLabels' => $programTypeLabels,
        'sectorLabels'     => $sectorLabels,
        'org'              => $org,
        'addressLines'     => $addressLines,
        'family'           => in_array('familien', $audiences, true),
        'accessible'       => $accessible,
        'pinned'           => Event\isPinned($post->ID),
        'sortKey'          => buildSortKey($dates, startTimes($schedule)),
        'filters'          => [
            'dates'      => $dates,
            'audiences'  => array_values($audiences),
            'sectors'    => array_values($sectors),
            // Format filter matches the program type tags shown on the row.
            'programTypes' => array_values($programTypes),
            'district'   => (string) (get_field('district', $post->ID) ?: ''),
            'accessible' => $accessible,
            'search'     => Event\buildSearchIndex(array_merge([$title, $org], $programTypeLabels, $sectorLabels, $addressLines)),
        ],
    ];
}

/**
 * Earliest start time per day key, e.g. `['2026-11-14' => '10:00']`. Times are
 * stored as `H:i`, so they compare as plain strings.
 */
function startTimes(array $schedule)
{
    $starts = [];

    foreach ($schedule as $row) {
        $day = (string) ($row['key'] ?? '');
        $start = (string) ($row['start'] ?? '');

        if ($day === '' || $start === '') {
            continue;
        }

        if (!isset($starts[$day]) || $start < $starts[$day]) {
            $starts[$day] = $start;
        }
    }

    return $starts;
}

/**
 * Chronological sort key: earliest day, then that day's start time — a time is
 * never compared outside the day it belongs to, so an entry running late on the
 * first day does not borrow an early start from the second. Entries without a
 * day or time sort last rather than jumping to the top.
 */
function buildSortKey(array $dates, array $starts)
{
    $days = array_values(array_filter($dates)) ?: array_keys($starts);
    sort($days);
    $first = (string) ($days[0] ?? '');

    return ($first ?: '9999-12-31') . ' ' . ($starts[$first] ?? '99:99');
}

/**
 * Every value used by at least one entry for a given filter key, so controls
 * that would match nothing are never rendered.
 */
function usedValues(array $entries, $key)
{
    $used = [];
    foreach (array_column($entries, 'filters') as $filters) {
        foreach ((array) ($filters[$key] ?? []) as $value) {
            if ($value !== '') {
                $used[$value] = true;
            }
        }
    }
    return $used;
}

add_filter('Flynt/addComponentData?name=ListingEvents', function ($data) {
    $config = Event\getConfig();

    $posts = get_posts([
        'post_type'      => Event\POST_TYPE,
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ]);

    // Entries the editor has switched off stay published and reachable by URL,
    // they just don't appear in the list.
    $posts = array_filter($posts, fn ($post) => Event\isListed($post->ID));

    $entries = array_map(fn ($post) => buildEntry($post, $config), $posts);
    // Pinned entries first, then day, start time and title — so entries starting
    // at the same moment keep a stable order instead of following the publish date.
    usort($entries, fn ($a, $b) => ($b['pinned'] <=> $a['pinned'])
        ?: strcmp($a['sortKey'], $b['sortKey'])
        ?: strnatcasecmp($a['title'], $b['title']));

    $data['entries'] = $entries;
    // Keyed by post ID: the rows are rendered server side and only ask the
    // client side whether their own ID currently matches. Cast to an object so
    // the lookup by ID works whatever the IDs happen to be, while the value
    // lists below it stay JSON arrays.
    $data['filterData'] = wp_json_encode((object) array_column($entries, 'filters', 'id'));
    $data['days'] = Event\getDays();
    $data['audienceGroups'] = $config['audienceGroups'];
    $data['sectors'] = $config['sectors'];
    $data['formats'] = $config['programTypes'];
    // Bezirk only lists what the program actually offers, so a control that
    // would match nothing is never rendered.
    $data['districts'] = array_intersect_key($config['districts'], usedValues($entries, 'district'));
    $data['icons'] = [
        'family'        => Asset::requireUrl('assets/icons/event/family.png'),
        'accessibility' => Asset::requireUrl('assets/icons/event/accessibility.png'),
    ];

    return $data;
});
