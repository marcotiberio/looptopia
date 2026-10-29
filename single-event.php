<?php

use Timber\Timber;
use Flynt\Event;
use Flynt\Utils\Asset;

$context = Timber::context();
$post = $context['post'];

// Resolve stored choice keys to their human labels.
$config = Event\getConfig();

$mapLabels = function ($keys, $choices) {
    return array_map(function ($key) use ($choices) {
        return $choices[$key] ?? $key;
    }, (array) ($keys ?: []));
};
$singleLabel = function ($key, $choices) use ($post) {
    $value = $post->meta($key);
    return $choices[$value] ?? $value;
};

$context['labels'] = [
    'goals'         => $mapLabels($post->meta('goals'), $config['goals']),
    'sectors'       => $mapLabels($post->meta('sectors'), $config['sectors']),
    'programTypes'  => $mapLabels($post->meta('programTypes'), $config['programTypes']),
    'audiences'     => $mapLabels($post->meta('audiences'), $config['audiences']),
    'accessibility' => $mapLabels($post->meta('accessibility'), $config['accessibility']),
    'registration'  => $singleLabel('registration', $config['registration']),
    'costs'         => $singleLabel('costs', $config['costs']),
    'format'        => $singleLabel('format', $config['format']),
    'language'      => $mapLabels($post->meta('language'), $config['languages']),
    'dates'         => $mapLabels($post->meta('dates'), $config['dates']),
    'locationMode'  => $singleLabel('locationMode', $config['locationMode']),
];

/**
 * Icon set exported from the design (Streamline Ultimate).
 * Passed as URLs because the PNGs are not part of a Vite entry.
 */
$icon = function ($file) {
    return Asset::requireUrl('assets/icons/event/' . $file);
};
$context['icons'] = [
    'calendar'      => $icon('calendar.png'),
    'clock'         => $icon('clock.png'),
    'mapPin'        => $icon('map-pin.png'),
    'ticket'        => $icon('ticket.png'),
    'money'         => $icon('money-wallet.png'),
    'audience'      => $icon('audience.png'),
    'accessibility' => $icon('accessibility.png'),
];

/**
 * Badge shown on the title card and on every teaser image — the entry's format
 * glyph, same as the map pins and the card badges.
 */
$context['badge'] = $icon(Event\getFormatIcon($post->ID));

/**
 * Termine & Zeiten. `dates` stores ISO keys, the repeater stores the label the
 * submitter saw — pair them by index (both are written in the same loop) so the
 * weekday can be formatted, with the stored label as fallback.
 */
// Weekday names are spelled out here rather than taken from wp_date('l') so the
// page stays German even on an installation running an English WP locale.
$weekdays = [
    1 => __('Montag', 'flynt'),
    2 => __('Dienstag', 'flynt'),
    3 => __('Mittwoch', 'flynt'),
    4 => __('Donnerstag', 'flynt'),
    5 => __('Freitag', 'flynt'),
    6 => __('Samstag', 'flynt'),
    7 => __('Sonntag', 'flynt'),
];

$formatSchedule = function ($postId) use ($config, $weekdays) {
    $dateKeys = array_values((array) (get_field('dates', $postId) ?: []));

    // One entry per repeater row, so several time slots on the same day all
    // show. Each row is resolved to its ISO key (by label, then by position).
    $rows = Event\getScheduleRows($postId);

    // Backend-only override: one custom date replaces the selected days. The
    // time is taken from the first schedule row.
    $dateOverride = get_field('hasDateOverride', $postId) ? (string) get_field('dateOverride', $postId) : '';
    if ($dateOverride) {
        $first    = $rows[0] ?? [];
        $dateKeys = [$dateOverride];
        $rows     = [array_merge($first, ['key' => $dateOverride])];
    }

    // Selected days without a repeater row still show, just without a time.
    $usedKeys = array_column($rows, 'key');
    foreach ($dateKeys as $key) {
        if (!in_array($key, $usedKeys, true)) {
            $rows[] = ['key' => $key, 'date' => '', 'start' => '', 'end' => ''];
        }
    }

    $entries = [];
    foreach ($rows as $row) {
        $key       = (string) ($row['key'] ?? '');
        $timestamp = $key !== '' ? strtotime($key) : false;
        $fallback  = $config['dates'][$key] ?? (string) ($row['date'] ?? '');

        $start = trim((string) ($row['start'] ?? ''));
        $end   = trim((string) ($row['end'] ?? ''));

        $entries[] = [
            'timestamp' => $timestamp ?: PHP_INT_MAX,
            'long'      => $timestamp
                ? $weekdays[(int) wp_date('N', $timestamp)] . ', ' . wp_date('d.m.Y', $timestamp)
                : $fallback,
            'short'     => $timestamp ? wp_date('d.m.y', $timestamp) : $fallback,
            'time'      => $start . ($end ? '–' . $end : ''),
            'start'     => $start,
        ];
    }

    // Chronological: by day, then by start time. Undated rows go last.
    usort($entries, function ($a, $b) {
        return [$a['timestamp'], $a['start']] <=> [$b['timestamp'], $b['start']];
    });

    return array_values(array_filter($entries, function ($entry) {
        return $entry['long'] !== '' || $entry['time'] !== '';
    }));
};
$context['schedule'] = $formatSchedule($post->ID);

// Location. There is no venue name / venue website field yet, so the block
// shows the submitted address plus a map link.
$street     = trim((string) $post->meta('street'));
$postalCode = trim((string) $post->meta('postalCode'));
$context['address'] = trim(implode(' ', array_filter([
    $street ? $street . ',' : '',
    trim($postalCode . ' Berlin'),
])));

$location = $post->meta('location');
$context['mapsUrl'] = '';
if (!empty($location['lat']) && !empty($location['lng'])) {
    $context['mapsUrl'] = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($location['lat'] . ',' . $location['lng']);
} elseif ($street || $postalCode) {
    $context['mapsUrl'] = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(Event\composeAddress($street, $postalCode));
}

// "Weitere Orte" (backend only), listed below the main location.
$context['additionalLocations'] = array_values(array_filter(array_map(function ($row) {
    $name = trim((string) ($row['name'] ?? ''));
    $address = trim((string) ($row['address'] ?? ''));
    $pin = $row['location'] ?? [];

    $mapsUrl = '';
    if (!empty($pin['lat']) && !empty($pin['lng'])) {
        $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($pin['lat'] . ',' . $pin['lng']);
    } elseif ($address !== '') {
        $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(Event\composeAddress($address, ''));
    }

    if ($name === '' && $address === '' && $mapsUrl === '') {
        return null;
    }

    return [
        'name'    => $name,
        'address' => $address,
        'mapsUrl' => $mapsUrl,
    ];
}, get_field('additionalLocations', $post->ID) ?: [])));

// Image fields are stored as attachment IDs — read the raw meta so the ACF
// image formatter (which returns a Timber\Image) stays out of the way.
$logoId = get_post_meta($post->ID, 'orgLogo', true);
$context['orgLogoUrl'] = $logoId ? wp_get_attachment_image_url((int) $logoId, 'medium') : '';

// Titelbild — the submitted `featuredImage`, not the WP post thumbnail.
$featuredId = (int) get_post_meta($post->ID, 'featuredImage', true);
$context['featuredImage'] = $featuredId ? [
    'src' => wp_get_attachment_image_url($featuredId, 'large'),
    'alt' => (string) get_post_meta($featuredId, '_wp_attachment_image_alt', true),
] : null;

/**
 * Veranstalter. The submitted organisation comes from the top-level fields
 * (the public form only ever writes one); the client can add more in wp-admin
 * through the `additionalOrganisations` repeater. Both are normalised into one
 * list so the template renders every entry the same way.
 */
$organisations = [[
    'name'      => trim((string) $post->meta('orgName')),
    'logoUrl'   => $context['orgLogoUrl'],
    'website'   => trim((string) $post->meta('website')),
    'instagram' => trim((string) $post->meta('instagram')),
    'linkedin'  => trim((string) $post->meta('linkedin')),
]];

foreach ((array) (get_field('additionalOrganisations', $post->ID) ?: []) as $index => $row) {
    // Same reasoning as above: take the attachment ID from the raw row meta
    // rather than the formatted repeater value.
    $rowLogoId = get_post_meta($post->ID, "additionalOrganisations_{$index}_orgLogo", true);

    $organisations[] = [
        'name'      => trim((string) ($row['orgName'] ?? '')),
        'logoUrl'   => $rowLogoId ? wp_get_attachment_image_url((int) $rowLogoId, 'medium') : '',
        'website'   => trim((string) ($row['website'] ?? '')),
        'instagram' => trim((string) ($row['instagram'] ?? '')),
        'linkedin'  => trim((string) ($row['linkedin'] ?? '')),
    ];
}

$context['organisations'] = array_values(array_filter($organisations, function ($org) {
    return $org['name'] !== '' || $org['logoUrl'] !== '';
}));

$galleryIds = get_post_meta($post->ID, 'gallery', true);
$context['galleryUrls'] = array_values(array_filter(array_map(function ($id) {
    return wp_get_attachment_image_url((int) $id, 'large');
}, (array) ($galleryIds ?: []))));

// "Zurück zum Programm" / "Programm entdecken" point at the program page when
// the client has created one, otherwise at the event archive.
$programPage = get_page_by_path('programm');
$context['programLink'] = $programPage ? get_permalink($programPage) : get_post_type_archive_link(Event\POST_TYPE);

// The closing "Weitere Programmpunkte" row is a component of its own — see
// Components/BlockRelatedEvents.

Timber::render('templates/single-event.twig', $context);
