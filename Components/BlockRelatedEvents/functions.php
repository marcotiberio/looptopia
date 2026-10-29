<?php

namespace Flynt\Components\BlockRelatedEvents;

use Flynt\Event;
use Flynt\Utils\Asset;
use Flynt\Utils\Options;

// One full row of the three-column grid, as in the design.
const MAX_CARDS = 3;

/**
 * Icon set exported from the design (Streamline Ultimate).
 * Passed as URLs because the PNGs are not part of a Vite entry.
 */
function iconUrl($file)
{
    return Asset::requireUrl('assets/icons/event/' . $file);
}

/**
 * Resolve stored choice keys to their human labels.
 */
function labels($keys, array $choices)
{
    return array_values(array_map(function ($key) use ($choices) {
        return $choices[$key] ?? $key;
    }, (array) ($keys ?: [])));
}

/**
 * Day rows of a card: `{ short, time }` per event day, e.g. "14.11.26" +
 * "09:00–17:00".
 *
 * Times live on the `eventSchedule` repeater, dates on the stable ISO keys —
 * days submitted without a repeater row still show, so an entry is never
 * silently hidden. Entries stored before the ISO keys existed fall back to the
 * repeater labels.
 */
function cardSchedule($postId)
{
    $config = Event\getConfig();
    $rows = Event\getScheduleRows($postId);
    $dates = array_filter((array) (get_field('dates', $postId) ?: []));

    $times = [];
    foreach ($rows as $row) {
        if ($row['key'] !== '') {
            $times[$row['key']] = $row['time'];
        }
    }
    foreach ($dates as $key) {
        if (!isset($times[$key])) {
            $times[$key] = '';
        }
    }

    if (!$times) {
        return array_map(function ($row) {
            return ['short' => $row['date'], 'time' => $row['time']];
        }, $rows);
    }

    ksort($times);

    $entries = [];
    foreach ($times as $key => $time) {
        $timestamp = strtotime($key);
        $entries[] = [
            'short' => $timestamp ? wp_date('d.m.y', $timestamp) : ($config['dates'][$key] ?? $key),
            'time'  => $time,
        ];
    }

    return $entries;
}

/**
 * Everything one teaser card renders. Shape matches
 * `templates/Partials/_eventCard.twig`.
 */
function buildCard($post, array $config)
{
    $accessibility = (array) (get_field('accessibility', $post->ID) ?: []);
    $audiences     = (array) (get_field('audiences', $post->ID) ?: []);

    // Trailing icon row: audience group + accessibility, when either applies.
    $markers = [];
    if (array_intersect($audiences, ['familien', 'jugend'])) {
        $markers[] = ['src' => iconUrl('family.png'), 'label' => __('Für Familien & Kinder geeignet', 'flynt')];
    }
    if ($accessibility && !in_array('keine', $accessibility, true)) {
        $markers[] = ['src' => iconUrl('accessibility.png'), 'label' => __('Barrierefrei', 'flynt')];
    }

    return [
        'title'        => get_the_title($post),
        'link'         => get_permalink($post),
        'thumbnail'    => get_the_post_thumbnail_url($post, 'large'),
        // Badge glyph follows the entry's format, as on the map pins.
        'badge'        => iconUrl(Event\getFormatIcon($post->ID)),
        'schedule'     => cardSchedule($post->ID),
        'programTypes' => labels(get_field('programTypes', $post->ID), $config['programTypes']),
        'sectors'      => labels(get_field('sectors', $post->ID), $config['sectors']),
        'orgName'      => get_field('orgName', $post->ID),
        'markers'      => $markers,
    ];
}

/**
 * Related program entries: same kind of program point first, topped up with the
 * most recent entries so the row is always full.
 */
function findRelated($postId)
{
    $programTypes = (array) (get_field('programTypes', $postId) ?: []);
    $related = [];

    if ($programTypes) {
        $metaQuery = ['relation' => 'OR'];
        foreach ($programTypes as $type) {
            $metaQuery[] = ['key' => 'programTypes', 'value' => '"' . $type . '"', 'compare' => 'LIKE'];
        }
        $related = get_posts([
            'post_type'      => Event\POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => MAX_CARDS,
            'post__not_in'   => [$postId],
            'meta_query'     => $metaQuery,
        ]);
    }

    if (count($related) < MAX_CARDS) {
        $exclude = array_merge([$postId], wp_list_pluck($related, 'ID'));
        $related = array_merge($related, get_posts([
            'post_type'      => Event\POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => MAX_CARDS - count($related),
            'post__not_in'   => $exclude,
        ]));
    }

    return $related;
}

/**
 * "Programm entdecken" points at the program page when the client has created
 * one, otherwise at the event archive.
 */
function programLink()
{
    $programPage = get_page_by_path('programm');

    return $programPage ? get_permalink($programPage) : get_post_type_archive_link(Event\POST_TYPE);
}

/**
 * Block toggle from "Blocks Settings". Never-saved options come back as null,
 * which counts as enabled so the block shows by default.
 */
function isEnabled()
{
    $value = Options::getTranslatable('BlockRelatedEvents', 'showRelatedEvents');

    return $value === null || (bool) $value;
}

add_filter('Flynt/addComponentData?name=BlockRelatedEvents', function ($data) {
    // The template renders nothing without events.
    if (!isEnabled()) {
        $data['events'] = [];
        return $data;
    }

    $postId = isset($data['post']) ? (int) $data['post']->ID : (int) get_the_ID();
    $config = Event\getConfig();

    $data['title'] = $data['title'] ?? __('Weitere Programmpunkte, die dir gefallen könnten', 'flynt');
    $data['buttonLabel'] = $data['buttonLabel'] ?? __('Programm entdecken', 'flynt');
    $data['programLink'] = $data['programLink'] ?? programLink();

    $data['events'] = array_map(function ($post) use ($config) {
        return buildCard($post, $config);
    }, findRelated($postId));

    return $data;
});

Options::addTranslatable('BlockRelatedEvents', [
    [
        'label' => __('Show related events', 'flynt'),
        'instructions' => __('Show the "Weitere Programmpunkte" block below single events.', 'flynt'),
        'name' => 'showRelatedEvents',
        'type' => 'true_false',
        'default_value' => 1,
        'ui' => 1,
    ],
]);
