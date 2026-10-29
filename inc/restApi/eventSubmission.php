<?php

/**
 * REST endpoint that receives public submissions from the FormEvent
 * component and stores them as `pending` Event posts for review.
 *
 * Accepts multipart/form-data (file uploads). Security: honeypot, per-IP
 * rate limit, strict sanitisation, choice allow-listing, conditional required
 * validation. Anonymous callers can only ever create pending posts.
 */

namespace Flynt\Event;

const REST_ROUTE = '/looptopia/v1/event';

add_action('rest_api_init', function () {
    register_rest_route('looptopia/v1', '/event', [
        'methods' => 'POST',
        'callback' => __NAMESPACE__ . '\\handleSubmission',
        'permission_callback' => '__return_true',
    ]);
});

// Tolerate a stale wp_rest nonce on this public route. The form no longer
// sends one, but pages cached in a visitor's browser from before that change
// still attach an `X-WP-Nonce` header. Once it expires, WordPress core's
// rest_cookie_check_errors() rejects the whole request with "Cookie check
// failed" — before our handler ever runs. Stripping the nonce for this one
// route (ahead of core's priority-100 check) makes core treat the caller as
// an anonymous user, which is exactly what this endpoint expects. Abuse is
// still contained by the honeypot, per-IP rate limit and pending-only posts.
add_filter('rest_authentication_errors', function ($result) {
    // Someone earlier in the chain already decided — don't override it.
    if (null !== $result) {
        return $result;
    }
    if (strpos($_SERVER['REQUEST_URI'] ?? '', REST_ROUTE) !== false) {
        unset($_SERVER['HTTP_X_WP_NONCE'], $_REQUEST['_wpnonce'], $_GET['_wpnonce'], $_POST['_wpnonce']);
    }
    return $result;
}, 5);

function handleSubmission(\WP_REST_Request $request)
{
    $params = $request->get_body_params();
    $files = $request->get_file_params();

    // No nonce check: this is a public, cacheable form. A nonce baked into
    // (potentially CDN-cached) HTML goes stale and triggers WordPress core's
    // "Cookie check failed". Abuse is contained instead by the honeypot, the
    // per-IP rate limit, and posts only ever being created as `pending`.

    // 1. Honeypot.
    if (!empty($params['website_hp'])) {
        return new \WP_REST_Response(['success' => true], 200);
    }

    // 2. Rate limit per IP.
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $rateKey = 'le_pe_' . md5($ip);
    $count = (int) get_transient($rateKey);
    if ($count >= 5) {
        return reject(__('Zu viele Einsendungen. Bitte versuche es später erneut.', 'flynt'), 429);
    }

    $config = getConfig();

    // 3. Sanitise scalars.
    $data = [
        'orgName'        => sanitize_text_field($params['orgName'] ?? ''),
        'contactPerson'  => sanitize_text_field($params['contactPerson'] ?? ''),
        'contactEmail'   => sanitize_email($params['contactEmail'] ?? ''),
        'website'        => esc_url_raw($params['website'] ?? ''),
        'instagram'      => esc_url_raw($params['instagram'] ?? ''),
        'linkedin'       => esc_url_raw($params['linkedin'] ?? ''),
        'registration'   => sanitize_key($params['registration'] ?? ''),
        // Free text: can be a URL or e.g. an e-mail instruction.
        'registrationLink' => sanitize_text_field($params['registrationLink'] ?? ''),
        'costs'          => sanitize_key($params['costs'] ?? ''),
        'price'          => sanitize_text_field($params['price'] ?? ''),
        'paymentLink'    => esc_url_raw($params['paymentLink'] ?? ''),
        'description'    => sanitize_textarea_field($params['description'] ?? ''),
        'format'         => sanitize_key($params['format'] ?? ''),
        'eventTitle'     => sanitize_text_field($params['eventTitle'] ?? ''),
        'intro'          => sanitize_text_field($params['intro'] ?? ''),
        'credits'        => sanitize_text_field($params['credits'] ?? ''),
        'venueName'      => sanitize_text_field($params['venueName'] ?? ''),
        'locationMode'   => sanitize_key($params['locationMode'] ?? ''),
        'street'         => sanitize_text_field($params['street'] ?? ''),
        'postalCode'     => sanitize_text_field($params['postalCode'] ?? ''),
        'mobilityInfo'   => sanitize_textarea_field($params['mobilityInfo'] ?? ''),
    ];

    // Multiple choice — keep only known keys.
    $goals        = allowedKeys($params['goals'] ?? [], $config['goals']);
    $sectors      = allowedKeys($params['sectors'] ?? [], $config['sectors']);
    $programTypes = allowedKeys($params['programTypes'] ?? [], $config['programTypes']);
    $audiences    = allowedKeys($params['audiences'] ?? [], $config['audiences']);
    $accessibility = allowedKeys($params['accessibility'] ?? [], $config['accessibility']);
    // Sprache — multiple choice, stored under the singular `language` key the
    // field has always used so existing entries stay readable.
    $language     = allowedKeys($params['language'] ?? [], $config['languages']);

    // Event day(s) + per-day times. `dates` is the set of selected day keys;
    // `times` is a map keyed by the same day → { start, end } (HH:MM).
    $dates = allowedKeys($params['dates'] ?? [], $config['dates']);
    $rawTimes = (array) ($params['times'] ?? []);
    $schedule = [];
    foreach ($dates as $dateKey) {
        $schedule[] = [
            'date'      => $config['dates'][$dateKey],
            'timeStart' => sanitizeTime($rawTimes[$dateKey]['start'] ?? ''),
            'timeEnd'   => sanitizeTime($rawTimes[$dateKey]['end'] ?? ''),
        ];
    }

    // Booleans.
    $venueOpenForOthers = !empty($params['venueOpenForOthers']);
    $fundingInterest    = !empty($params['fundingInterest']);
    $acceptCriteria     = !empty($params['acceptCriteria']);
    $acceptTerms        = !empty($params['acceptTerms']);
    $newsletter         = !empty($params['newsletter']);

    // 4. Validate.
    $errors = [];
    $required = [
        'orgName'       => __('Name der Organisation', 'flynt'),
        'contactPerson' => __('Kontaktperson', 'flynt'),
        'description'   => __('Beschreibungstext', 'flynt'),
        'eventTitle'    => __('Titel der Veranstaltung', 'flynt'),
        'intro'         => __('Introtext', 'flynt'),
    ];
    foreach ($required as $key => $label) {
        if ($data[$key] === '') {
            $errors[] = sprintf(__('Bitte fülle das Feld „%s“ aus.', 'flynt'), $label);
        }
    }
    if (!is_email($data['contactEmail'])) {
        $errors[] = __('Bitte gib eine gültige E-Mail-Adresse an.', 'flynt');
    }
    if (!isset($config['registration'][$data['registration']])) {
        $errors[] = __('Bitte wähle, ob eine Anmeldung erforderlich ist.', 'flynt');
    } elseif ($data['registration'] === 'anmeldung' && $data['registrationLink'] === '') {
        $errors[] = __('Bitte gib einen Anmeldelink an.', 'flynt');
    }
    if (!isset($config['costs'][$data['costs']])) {
        $errors[] = __('Bitte wähle, ob Kosten anfallen.', 'flynt');
    } elseif ($data['costs'] === 'ja' && $data['price'] === '') {
        $errors[] = __('Bitte gib den Preis an.', 'flynt');
    }
    // Hidden in the public form (see Components/FormEvent/index.twig), so an empty
    // value is expected — but a submitted value must still be a known key. The map
    // pin and card badge fall back to the first programTypes key via getFormatIcon().
    if ($data['format'] !== '' && !isset($config['format'][$data['format']])) {
        $errors[] = __('Bitte wähle ein Format.', 'flynt');
    }
    if (!$language) {
        $errors[] = __('Bitte wähle mindestens eine Sprache.', 'flynt');
    }
    if (!$dates) {
        $errors[] = __('Bitte wähle mindestens einen Veranstaltungstag.', 'flynt');
    } else {
        foreach ($schedule as $row) {
            if ($row['timeStart'] === '') {
                $errors[] = __('Bitte gib für jeden gewählten Tag eine Startzeit an.', 'flynt');
                break;
            }
        }
    }
    if (!isset($config['locationMode'][$data['locationMode']])) {
        $errors[] = __('Bitte wähle eine Ortsoption.', 'flynt');
    } elseif ($data['locationMode'] === 'eigen' && ($data['street'] === '' || $data['postalCode'] === '')) {
        $errors[] = __('Bitte gib Straße und Postleitzahl an.', 'flynt');
    }
    if (!$goals) {
        $errors[] = __('Bitte wähle mindestens ein Thema.', 'flynt');
    }
    if (!$sectors) {
        $errors[] = __('Bitte wähle mindestens einen Sektor.', 'flynt');
    }
    if (!$programTypes) {
        $errors[] = __('Bitte wähle die Art des Programmpunkts.', 'flynt');
    }
    if (!$accessibility) {
        $errors[] = __('Bitte gib Infos zur Barrierefreiheit an.', 'flynt');
    }
    if (!$acceptCriteria || !$acceptTerms) {
        $errors[] = __('Bitte akzeptiere die Teilnahmekriterien und Nutzungsbedingungen.', 'flynt');
    }
    // Required uploads must be present and error-free.
    if (!hasUpload($files, 'orgLogo')) {
        $errors[] = __('Bitte lade das Logo der Organisation hoch.', 'flynt');
    }
    if (!hasUpload($files, 'featuredImage')) {
        $errors[] = __('Bitte lade ein Titelbild hoch.', 'flynt');
    }

    if (!empty($errors)) {
        return reject(implode(' ', $errors), 422);
    }

    // 5. Geocode (best-effort) for an own venue.
    $geo = null;
    $geoError = null;
    if ($data['locationMode'] === 'eigen') {
        $geo = geocodeAddress(composeAddress($data['street'], $data['postalCode']), $geoError);
    }

    // 6. Create the pending post.
    $postId = wp_insert_post([
        'post_type'    => POST_TYPE,
        'post_status'  => 'pending',
        'post_title'   => $data['eventTitle'],
        'post_content' => $data['description'],
    ], true);

    if (is_wp_error($postId)) {
        return reject(__('Beim Speichern ist etwas schiefgelaufen. Bitte versuche es erneut.', 'flynt'), 500);
    }

    // 7. Uploads.
    requireMediaDeps();
    $logoId = uploadSingle('orgLogo', $postId);
    $featuredId = uploadSingle('featuredImage', $postId);
    $galleryIds = uploadMultiple('gallery', $postId);

    // 8. Store ACF fields.
    foreach ($data as $key => $value) {
        update_field($key, $value, $postId);
    }
    update_field('dates', $dates, $postId);
    update_field('eventSchedule', $schedule, $postId);
    update_field('goals', $goals, $postId);
    update_field('sectors', $sectors, $postId);
    update_field('programTypes', $programTypes, $postId);
    update_field('audiences', $audiences, $postId);
    update_field('accessibility', $accessibility, $postId);
    update_field('language', $language, $postId);
    update_field('venueOpenForOthers', $venueOpenForOthers, $postId);
    update_field('fundingInterest', $fundingInterest, $postId);
    update_field('acceptCriteria', $acceptCriteria, $postId);
    update_field('acceptTerms', $acceptTerms, $postId);
    update_field('newsletter', $newsletter, $postId);

    if ($logoId) {
        update_field('orgLogo', $logoId, $postId);
    }
    if ($featuredId) {
        update_field('featuredImage', $featuredId, $postId);
        set_post_thumbnail($postId, $featuredId);
    }
    if ($galleryIds) {
        update_field('gallery', $galleryIds, $postId);
    }
    if ($geo) {
        update_field('location', [
            'address' => $geo['formatted'],
            'lat'     => $geo['lat'],
            'lng'     => $geo['lng'],
        ], $postId);
    } elseif ($geoError) {
        // Flags the entry in wp-admin instead of losing it from the map.
        storeGeocodeError($postId, $geoError);
    }

    // 9. Notify the editor.
    wp_mail(
        get_option('admin_email'),
        __('Neuer Programm-Eintrag zur Prüfung', 'flynt'),
        sprintf(
            /* translators: 1: event title, 2: edit link */
            __("Ein neuer Eintrag wurde eingereicht: %1\$s\n\nPrüfen und veröffentlichen:\n%2\$s", 'flynt'),
            $data['eventTitle'],
            admin_url('post.php?post=' . $postId . '&action=edit')
        )
    );

    set_transient($rateKey, $count + 1, HOUR_IN_SECONDS);

    return new \WP_REST_Response([
        'success' => true,
        'message' => __('Danke! Dein Eintrag wurde zur Prüfung eingereicht.', 'flynt'),
    ], 201);
}

/**
 * Keep only the submitted values that exist in the allow-list.
 */
function allowedKeys($values, $allowed)
{
    $values = array_map('sanitize_key', (array) $values);
    return array_values(array_filter($values, fn ($v) => isset($allowed[$v])));
}

/**
 * Normalise a submitted time to HH:MM, or '' if it isn't a valid 24h time.
 */
function sanitizeTime($value)
{
    $value = sanitize_text_field((string) $value);
    return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value) ? $value : '';
}

/**
 * Whether a usable (error-free) upload exists for the given field.
 */
function hasUpload($files, $key)
{
    return !empty($files[$key]['name']) && ($files[$key]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;
}

function requireMediaDeps()
{
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
}

/**
 * Sideload one uploaded file into the media library; returns attachment ID or null.
 */
function uploadSingle($key, $postId)
{
    if (empty($_FILES[$key]['name']) || ($_FILES[$key]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }
    $id = media_handle_upload($key, $postId);
    return is_wp_error($id) ? null : $id;
}

/**
 * Sideload a multi-file field (name="key[]"); returns an array of attachment IDs.
 */
function uploadMultiple($key, $postId)
{
    if (empty($_FILES[$key]['name']) || !is_array($_FILES[$key]['name'])) {
        return [];
    }

    $ids = [];
    $count = count($_FILES[$key]['name']);
    for ($i = 0; $i < $count; $i++) {
        if (($_FILES[$key]['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            continue;
        }
        // Reshape this one file into the structure media_handle_sideload expects.
        $file = [
            'name'     => $_FILES[$key]['name'][$i],
            'type'     => $_FILES[$key]['type'][$i],
            'tmp_name' => $_FILES[$key]['tmp_name'][$i],
            'error'    => $_FILES[$key]['error'][$i],
            'size'     => $_FILES[$key]['size'][$i],
        ];
        $id = media_handle_sideload($file, $postId);
        if (!is_wp_error($id)) {
            $ids[] = $id;
        }
    }
    return $ids;
}

function reject($message, $status)
{
    return new \WP_REST_Response(['success' => false, 'message' => $message], $status);
}
