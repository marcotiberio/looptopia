<?php

namespace Flynt\Components\FormEvent;

use Flynt\Event;
use Flynt\Utils\Asset;

function getACFLayout()
{
    return [
        'name' => 'formEvent',
        'label' => __('Form Event', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Success message', 'flynt'),
                'name' => 'successHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
            ],
            [
                'label' => __('Submit button label', 'flynt'),
                'name' => 'submitLabel',
                'type' => 'text',
                'default_value' => __('Anmeldeformular absenden', 'flynt'),
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Consent & links', 'flynt'),
                'name' => 'consentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Teilnahmekriterien link', 'flynt'),
                'name' => 'criteriaLink',
                'type' => 'link',
                'return_format' => 'array',
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Nutzungsbedingungen link', 'flynt'),
                'name' => 'termsLink',
                'type' => 'link',
                'return_format' => 'array',
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Newsletter consent label', 'flynt'),
                'name' => 'newsletterLabel',
                'type' => 'text',
                'default_value' => __('Ja, ich möchte den Circular Berlin Newsletter abonnieren', 'flynt'),
            ],
        ],
    ];
}

// Inject the REST endpoint and the shared choice lists so the Alpine form can
// render and submit without a separate config request. No nonce is passed: the
// endpoint is public and cacheable, so a nonce frozen into (CDN-cached) HTML
// would go stale and trip WordPress core's "Cookie check failed". Abuse is
// handled server-side via honeypot, per-IP rate limit and pending-only posts.
add_filter('Flynt/addComponentData?name=FormEvent', function ($data) {
    $data['config'] = Event\getConfig();
    // Section titles + intros, client-editable under "Global Options → Event".
    $data['sections'] = Event\getSections();
    // Free-text field labels + question legends, same options page.
    $data['fieldLabels'] = Event\getFieldLabels();
    $data['restUrl'] = esc_url_raw(rest_url('looptopia/v1/event'));
    // Local-only: exposes a button to preview the success popup + confetti
    // without submitting the form. True when the Vite dev server is running
    // (npm run serve) or the env is explicitly 'local' — never on the
    // deployed site, which ships a built dist/ with no hot file.
    $data['isLocal'] = Asset::isHotModuleReplacement() || wp_get_environment_type() === 'local';
    return $data;
});
