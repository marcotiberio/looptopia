<?php

namespace Flynt\Components\CarouselProgram;

use Flynt\Event;
use Flynt\Utils\Asset;

// Cards shown in the grid. The featured entry sits above the grid and is never
// repeated among them. Every other upcoming entry is rendered and the browser
// picks four at random, so the choice survives full-page caching.
const MAX_CARDS = 4;

function getACFLayout()
{
    return [
        'name' => 'carouselProgram',
        'label' => __('Featured Programm Events', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text',
                'default_value' => __('Programm', 'flynt'),
            ],
            // [
            //     'label' => __('Intro', 'flynt'),
            //     'name' => 'preContentHtml',
            //     'type' => 'wysiwyg',
            //     'tabs' => 'visual',
            //     'media_upload' => 0,
            //     'delay' => 1,
            //     'required' => 0,
            // ],
            [
                'label' => __('Featured entry', 'flynt'),
                'instructions' => __('Leave empty to feature the entry closest in time. Entries that are already over are skipped.', 'flynt'),
                'name' => 'featuredEntry',
                'type' => 'post_object',
                'post_type' => [Event\POST_TYPE],
                'post_status' => ['publish'],
                'return_format' => 'id',
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'required' => 0,
            ],
            [
                'label' => __('Show grid', 'flynt'),
                'instructions' => __('Show the random pick of upcoming entries below the featured entry.', 'flynt'),
                'name' => 'showGrid',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
            ],
            [
                'label' => __('Button: Programm', 'flynt'),
                'name' => 'programLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            [
                'label' => __('Button: Karte', 'flynt'),
                'name' => 'mapLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50,
                ],
            ],
        ],
    ];
}

/**
 * Start of the next occurrence that has not finished yet, as a sortable
 * `Y-m-d H:i` string — this is what "closest in time" means for the feature.
 * Returns null once every day of an entry is over, so it drops out entirely.
 *
 * Days without a schedule row still count (start of day until midnight), so an
 * entry submitted without times is never silently hidden.
 */
function nextOccurrence(array $schedule, array $dates, $now)
{
    $rows = [];
    foreach ($schedule as $row) {
        if ($row['key']) {
            $rows[$row['key']] = ['start' => $row['start'] ?: '00:00', 'end' => $row['end'] ?: ''];
        }
    }
    foreach (array_filter($dates) as $day) {
        if (!isset($rows[$day])) {
            $rows[$day] = ['start' => '00:00', 'end' => ''];
        }
    }

    $upcoming = [];
    foreach ($rows as $day => $row) {
        // Running entries stay upcoming until their end time has passed.
        if ($day . ' ' . ($row['end'] ?: '23:59') >= $now) {
            $upcoming[] = $day . ' ' . $row['start'];
        }
    }
    sort($upcoming);

    return $upcoming[0] ?? null;
}

/**
 * Schedule row of a given day, so the card prints the times of the occurrence
 * it is sorted by instead of the first day of a two-day entry.
 */
function pickRow(array $schedule, $day, array $config)
{
    foreach ($schedule as $row) {
        if ($row['key'] === $day) {
            return ['date' => $row['date'], 'time' => $row['time']];
        }
    }

    return ['date' => $config['dates'][$day] ?? '', 'time' => ''];
}

/**
 * Attachment IDs of an entry, cover image first: the WordPress thumbnail (or
 * the submitted `featuredImage`) followed by the optional gallery. Image fields
 * hold plain attachment IDs — read as raw meta so the ACF image formatter
 * (which returns a Timber\Image) stays out of the way.
 */
function imageIds($postId)
{
    $ids = [(int) (get_post_thumbnail_id($postId) ?: 0)];
    $ids[] = (int) get_post_meta($postId, 'featuredImage', true);

    foreach ((array) (get_post_meta($postId, 'gallery', true) ?: []) as $id) {
        $ids[] = (int) $id;
    }

    return array_values(array_unique(array_filter($ids)));
}

function buildImage($id, $size)
{
    return [
        'url' => wp_get_attachment_image_url($id, $size) ?: '',
        'alt' => (string) get_post_meta($id, '_wp_attachment_image_alt', true),
    ];
}

/**
 * One entry per upcoming program point. Everything both the featured panel and
 * the cards render is resolved here; entries that are over return null.
 */
function buildEntry($post, array $config, $now)
{
    $dates = array_values((array) (get_field('dates', $post->ID) ?: []));
    $schedule = Event\getScheduleRows($post->ID);
    $sortKey = nextOccurrence($schedule, $dates, $now);

    if ($sortKey === null) {
        return null;
    }

    $programTypes = (array) (get_field('programTypes', $post->ID) ?: []);
    $sectors = (array) (get_field('sectors', $post->ID) ?: []);

    // Badge glyph follows the entry's format, as on the map pins.
    $iconFile = Event\getFormatIcon($post->ID);

    $imageIds = imageIds($post->ID);

    return [
        'id'               => $post->ID,
        'title'            => get_the_title($post),
        'link'             => get_permalink($post),
        'image'            => $imageIds ? buildImage($imageIds[0], 'medium_large') : null,
        'imageIds'         => $imageIds,
        'icon'             => Asset::requireUrl('assets/icons/event/' . $iconFile),
        'when'             => pickRow($schedule, substr($sortKey, 0, 10), $config),
        'programTypeLabel' => $config['programTypes'][reset($programTypes) ?: ''] ?? '',
        'sectorLabels'     => array_values(array_filter(array_map(fn ($key) => $config['sectors'][$key] ?? '', $sectors))),
        'org'              => (string) (get_field('orgName', $post->ID) ?: ''),
        'sortKey'          => $sortKey,
    ];
}

/**
 * Extras only the featured panel needs: the teaser line and the full image set
 * the chevrons page through.
 *
 * There is no separate venue-name field on an entry — the organisation is the
 * closest thing to the "name of the place", with the street as a fallback.
 */
function buildFeatured(array $entry)
{
    $intro = trim((string) (get_field('intro', $entry['id']) ?: ''));
    if ($intro === '') {
        $intro = wp_trim_words((string) (get_field('description', $entry['id']) ?: ''), 30);
    }

    $entry['intro'] = $intro;
    $entry['venue'] = $entry['org'] ?: trim((string) (get_field('street', $entry['id']) ?: ''));
    $entry['images'] = array_map(fn ($id) => buildImage($id, 'large'), $entry['imageIds']);

    return $entry;
}

add_filter('Flynt/addComponentData?name=CarouselProgram', function ($data) {
    $config = Event\getConfig();
    $now = current_time('Y-m-d H:i');

    $posts = get_posts([
        'post_type'      => Event\POST_TYPE,
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ]);

    $entries = array_values(array_filter(array_map(fn ($post) => buildEntry($post, $config, $now), $posts)));
    usort($entries, fn ($a, $b) => strcmp($a['sortKey'], $b['sortKey']));

    // The entry chosen in the backend is featured, provided it is still upcoming;
    // otherwise the entry closest in time. It is never repeated in the carousel.
    $chosenId = (int) ($data['featuredEntry'] ?? 0);
    $chosenIndex = $chosenId ? array_search($chosenId, array_column($entries, 'id'), true) : false;
    $featured = $chosenIndex !== false
        ? array_splice($entries, $chosenIndex, 1)[0]
        : array_shift($entries);

    $data['featured'] = $featured ? buildFeatured($featured) : null;
    $data['entries'] = $entries;
    $data['maxCards'] = MAX_CARDS;

    return $data;
});
