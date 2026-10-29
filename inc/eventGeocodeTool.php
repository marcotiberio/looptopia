<?php

/**
 * Backfill tool for event map pins.
 *
 * Entries only appear on the map once they have coordinates. New submissions
 * get them on save, but entries that predate that — or whose address the
 * geocoders rejected — need a run of this tool: Veranstaltungen → Map-Pins.
 *
 * Work happens in timed batches because Nominatim allows one request per
 * second; each batch redirects back to the tool, which continues on its own
 * until the queue is empty.
 */

namespace Flynt\Event;

// Constants below are read at load time and FileLoader requires inc/*.php in
// filesystem order, so pull the definitions in first.
require_once __DIR__ . '/eventFields.php';

const GEOCODE_TOOL_SLUG = 'looptopia-geocode';
const GEOCODE_TOOL_ACTION = 'looptopia_geocode_events';

// Seconds of geocoding per request. Well inside the usual 30s PHP limit, and
// at ~1 request per second that is a handful of entries per batch.
const GEOCODE_BATCH_SECONDS = 15;

add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=' . POST_TYPE,
        __('Map-Pins', 'flynt'),
        __('Map-Pins', 'flynt'),
        'edit_others_posts',
        GEOCODE_TOOL_SLUG,
        __NAMESPACE__ . '\\renderGeocodeTool'
    );
});

function toolUrl(array $args = [])
{
    return add_query_arg(
        $args + ['post_type' => POST_TYPE, 'page' => GEOCODE_TOOL_SLUG],
        admin_url('edit.php')
    );
}

/**
 * The tool screen: what is missing, what failed, and a button to start.
 */
function renderGeocodeTool()
{
    if (!current_user_can('edit_others_posts')) {
        wp_die(esc_html__('Keine Berechtigung.', 'flynt'));
    }

    // Entries that already failed are excluded, otherwise the batch loop below
    // would retry them on every pass and never finish.
    $pending = eventsNeedingPin(true);
    $running = isset($_GET['run']) && $_GET['run'] === '1';
    $pinned = isset($_GET['pinned']) ? (int) $_GET['pinned'] : 0;

    echo '<div class="wrap">';
    printf('<h1>%s</h1>', esc_html__('Map-Pins', 'flynt'));
    printf(
        '<p>%s</p>',
        esc_html__('Ordnet Veranstaltungen anhand von Straße und PLZ Koordinaten zu. Ohne Koordinaten erscheint ein Eintrag nicht auf der Karte. Bereits gesetzte Pins bleiben unverändert.', 'flynt')
    );

    // Keep the batches going until the queue is empty. A meta refresh rather
    // than JS, so it also works with scripts disabled.
    if ($running && $pending) {
        $next = wp_nonce_url(
            admin_url('admin-post.php?action=' . GEOCODE_TOOL_ACTION . '&pinned=' . $pinned),
            GEOCODE_TOOL_ACTION
        );
        printf('<meta http-equiv="refresh" content="0;url=%s">', esc_url($next));
        printf(
            '<div class="notice notice-info"><p><span class="spinner is-active" style="float:none;margin:0 6px 0 0"></span>%s</p></div>',
            sprintf(
                /* translators: 1: entries already pinned, 2: entries left */
                esc_html__('Läuft … %1$d Einträge verortet, %2$d verbleiben.', 'flynt'),
                $pinned,
                count($pending)
            )
        );
    } elseif ($running) {
        printf(
            '<div class="notice notice-success"><p>%s</p></div>',
            sprintf(
                /* translators: %d: number of entries pinned */
                esc_html__('Fertig. %d Einträge wurden verortet.', 'flynt'),
                $pinned
            )
        );
    }

    renderGeocodeSummary($pending, $running);

    echo '</div>';
}

/**
 * Entries whose address the geocoders rejected on the last attempt.
 *
 * @return int[]
 */
function eventsWithGeocodeError()
{
    return get_posts([
        'post_type'      => POST_TYPE,
        'post_status'    => ['publish', 'pending', 'draft', 'future'],
        'posts_per_page' => -1,
        'meta_key'       => GEOCODE_ERROR_META,
        'fields'         => 'ids',
    ]);
}

/**
 * Counts, the start button, and the entries that could not be geocoded.
 */
function renderGeocodeSummary(array $pending, $running)
{
    $total = wp_count_posts(POST_TYPE);
    $failed = eventsWithGeocodeError();

    printf(
        '<p><strong>%s</strong> %s</p>',
        sprintf(
            /* translators: %d: number of entries without a pin */
            esc_html__('%d Einträge ohne Pin.', 'flynt'),
            count($pending)
        ),
        esc_html(sprintf(
            /* translators: 1: published, 2: pending */
            __('(%1$d veröffentlicht, %2$d ausstehend insgesamt.)', 'flynt'),
            $total->publish ?? 0,
            $total->pending ?? 0
        ))
    );

    if ($pending && !$running) {
        $start = wp_nonce_url(
            admin_url('admin-post.php?action=' . GEOCODE_TOOL_ACTION),
            GEOCODE_TOOL_ACTION
        );
        printf(
            '<p><a href="%s" class="button button-primary">%s</a></p>',
            esc_url($start),
            esc_html__('Fehlende Pins ermitteln', 'flynt')
        );
    }

    if (!$failed) {
        return;
    }

    printf('<h2>%s</h2>', esc_html__('Adressen, die nicht verortet werden konnten', 'flynt'));
    printf(
        '<p>%s</p>',
        esc_html__('Diese Einträge brauchen eine korrigierte Adresse oder einen manuell gesetzten Pin. Nach dem Speichern einer Adresse wird automatisch erneut gesucht.', 'flynt')
    );

    if (!$running) {
        $retry = wp_nonce_url(
            admin_url('admin-post.php?action=' . GEOCODE_TOOL_ACTION . '&retry=1'),
            GEOCODE_TOOL_ACTION
        );
        printf(
            '<p><a href="%s" class="button">%s</a></p>',
            esc_url($retry),
            esc_html__('Diese Adressen erneut versuchen', 'flynt')
        );
    }

    echo '<table class="widefat striped"><thead><tr>';
    printf('<th>%s</th><th>%s</th><th>%s</th>', esc_html__('Eintrag', 'flynt'), esc_html__('Adresse', 'flynt'), esc_html__('Grund', 'flynt'));
    echo '</tr></thead><tbody>';

    foreach ($failed as $postId) {
        printf(
            '<tr><td><a href="%s">%s</a></td><td>%s</td><td><code>%s</code></td></tr>',
            esc_url(get_edit_post_link($postId)),
            esc_html(get_the_title($postId)),
            esc_html(composeAddress(get_field('street', $postId), get_field('postalCode', $postId))),
            esc_html((string) get_post_meta($postId, GEOCODE_ERROR_META, true))
        );
    }

    echo '</tbody></table>';
}

/**
 * Geocode entries until the time budget runs out, then hand control back to
 * the tool screen, which starts the next batch.
 */
add_action('admin_post_' . GEOCODE_TOOL_ACTION, function () {
    if (!current_user_can('edit_others_posts')) {
        wp_die(esc_html__('Keine Berechtigung.', 'flynt'));
    }

    check_admin_referer(GEOCODE_TOOL_ACTION);

    // Clearing the recorded errors puts those entries back into the queue.
    if (isset($_GET['retry']) && $_GET['retry'] === '1') {
        foreach (eventsWithGeocodeError() as $postId) {
            delete_post_meta($postId, GEOCODE_ERROR_META);
        }
    }

    $pinned = isset($_GET['pinned']) ? (int) $_GET['pinned'] : 0;
    $deadline = microtime(true) + GEOCODE_BATCH_SECONDS;

    foreach (eventsNeedingPin(true) as $postId) {
        if (geocodePost($postId) === 'pinned') {
            $pinned++;
        }

        // Always complete the entry in hand; only the next one is deferred.
        if (microtime(true) >= $deadline) {
            break;
        }
    }

    wp_safe_redirect(toolUrl(['run' => '1', 'pinned' => $pinned]));
    exit;
});
