<?php

/**
 * ACF field group for the Event CPT.
 *
 * Fields the client reviews/edits in wp-admin before publishing. Mirrors the
 * public form (Components/FormEvent) and the intake sheet, grouped into
 * the same sections. Choice lists come from Event\getConfig().
 *
 * Registered on `acf/init` (not `Flynt/afterRegisterComponents`) because the
 * client-editable choice labels live on an ACF options page, which is only
 * readable once ACF is initialised.
 */

use ACFComposer\ACFComposer;
use Flynt\Event;

add_action('acf/init', function () {
    $config = Event\getConfig();

    ACFComposer::registerFieldGroup([
        'name' => 'eventDetails',
        'title' => __('Submission Details', 'flynt'),
        'style' => 'default',
        'position' => 'normal',
        'fields' => [
            // Sits before the first tab, so it stays visible on every tab.
            [
                'label' => __('Im Programm anzeigen', 'flynt'),
                'instructions' => __('Aus: Der Eintrag wird aus der Programmliste ausgeblendet. Die Eventseite bleibt erreichbar.', 'flynt'),
                'name' => 'showInProgram',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Position in der Programmliste', 'flynt'),
                'instructions' => __('Oben fixieren: Der Eintrag steht immer ganz oben in der Programmliste.', 'flynt'),
                'name' => 'programPosition',
                'type' => 'button_group',
                'choices' => [
                    'default' => __('Position beibehalten', 'flynt'),
                    'top' => __('Oben fixieren', 'flynt'),
                ],
                'default_value' => 'default',
                'return_format' => 'value',
                'wrapper' => ['width' => 50],
            ],

            // ---- Section 1: Organisation ----
            [
                'label' => __('Organisation', 'flynt'),
                'name' => 'orgTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Name der Organisation', 'flynt'),
                'name' => 'orgName',
                'type' => 'text',
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Kontaktperson', 'flynt'),
                'name' => 'contactPerson',
                'type' => 'text',
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Mailadresse', 'flynt'),
                'instructions' => __('Privat — nicht öffentlich sichtbar.', 'flynt'),
                'name' => 'contactEmail',
                'type' => 'email',
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Logo der Organisation', 'flynt'),
                'name' => 'orgLogo',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,svg',
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Website', 'flynt'),
                'name' => 'website',
                'type' => 'url',
                'wrapper' => ['width' => 33],
            ],
            [
                'label' => __('Instagram', 'flynt'),
                'name' => 'instagram',
                'type' => 'url',
                'wrapper' => ['width' => 33],
            ],
            [
                'label' => __('LinkedIn', 'flynt'),
                'name' => 'linkedin',
                'type' => 'url',
                'wrapper' => ['width' => 33],
            ],
            [
                'label' => __('Weitere Organisationen', 'flynt'),
                'instructions' => __('Optional. Nur im Backend pflegbar — das öffentliche Formular erlaubt weiterhin nur eine Organisation.', 'flynt'),
                'name' => 'additionalOrganisations',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Organisation hinzufügen', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Name der Organisation', 'flynt'),
                        'name' => 'orgName',
                        'type' => 'text',
                        'required' => 1,
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Kontaktperson', 'flynt'),
                        'name' => 'contactPerson',
                        'type' => 'text',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Mailadresse', 'flynt'),
                        'instructions' => __('Privat — nicht öffentlich sichtbar.', 'flynt'),
                        'name' => 'contactEmail',
                        'type' => 'email',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Logo der Organisation', 'flynt'),
                        'name' => 'orgLogo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Website', 'flynt'),
                        'name' => 'website',
                        'type' => 'url',
                        'wrapper' => ['width' => 33],
                    ],
                    [
                        'label' => __('Instagram', 'flynt'),
                        'name' => 'instagram',
                        'type' => 'url',
                        'wrapper' => ['width' => 33],
                    ],
                    [
                        'label' => __('LinkedIn', 'flynt'),
                        'name' => 'linkedin',
                        'type' => 'url',
                        'wrapper' => ['width' => 33],
                    ],
                ],
            ],

            // ---- Section 2: Angebot (allgemein) ----
            [
                'label' => __('Angebot', 'flynt'),
                'name' => 'offerTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Thema — Welche Ziele verfolgt euer Programm?', 'flynt'),
                'name' => 'goals',
                'type' => 'checkbox',
                'choices' => $config['goals'],
                'required' => 1,
            ],
            [
                'label' => __('Sektor', 'flynt'),
                'name' => 'sectors',
                'type' => 'checkbox',
                'choices' => $config['sectors'],
                'required' => 1,
            ],
            [
                'label' => __('Ist eine Anmeldung erforderlich?', 'flynt'),
                'name' => 'registration',
                'type' => 'radio',
                'choices' => $config['registration'],
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Anmeldelink', 'flynt'),
                'name' => 'registrationLink',
                'type' => 'text',
                'conditional_logic' => [
                    [
                        ['fieldPath' => 'registration', 'operator' => '==', 'value' => 'anmeldung'],
                    ],
                ],
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Fallen für die Teilnahme Kosten an?', 'flynt'),
                'name' => 'costs',
                'type' => 'radio',
                'choices' => $config['costs'],
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Preis', 'flynt'),
                'name' => 'price',
                'type' => 'text',
                'conditional_logic' => [
                    [
                        ['fieldPath' => 'costs', 'operator' => '==', 'value' => 'ja'],
                    ],
                ],
                'wrapper' => ['width' => 25],
            ],
            [
                'label' => __('Link zur Bezahlung / Buchung', 'flynt'),
                'name' => 'paymentLink',
                'type' => 'url',
                'conditional_logic' => [
                    [
                        ['fieldPath' => 'costs', 'operator' => '==', 'value' => 'ja'],
                    ],
                ],
                'wrapper' => ['width' => 25],
            ],

            // ---- Section 3: Details zum Angebot ----
            [
                'label' => __('Details', 'flynt'),
                'name' => 'detailsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Beschreibungstext des Angebots', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 6,
                'required' => 1,
            ],
            [
                'label' => __('Format', 'flynt'),
                'name' => 'programTypes',
                'type' => 'checkbox',
                'choices' => $config['programTypes'],
                'required' => 1,
            ],
            [
                'label' => __('Old Format', 'flynt'),
                'instructions' => __('Optional. Bestimmt nur das Icon auf der Karte und im Karten-Badge — ohne Auswahl wird das Icon aus dem ersten gewählten Format abgeleitet.', 'flynt'),
                'name' => 'format',
                'type' => 'radio',
                'choices' => $config['format'],
                'allow_null' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Sprache der Veranstaltung', 'flynt'),
                'instructions' => __('Mehrfachauswahl möglich. Wird auf der Karte und im Eintrag angezeigt und ist filterbar.', 'flynt'),
                'name' => 'language',
                'type' => 'select',
                'choices' => $config['languages'],
                'multiple' => 1,
                'ui' => 1,
                'default_value' => ['de'],
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Zielgruppe', 'flynt'),
                'name' => 'audiences',
                'type' => 'checkbox',
                'choices' => $config['audiences'],
            ],

            // ---- Section 4: Veranstaltung ----
            [
                'label' => __('Veranstaltung', 'flynt'),
                'name' => 'eventTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Titel der Veranstaltung', 'flynt'),
                'instructions' => __('Wird als Titel des Eintrags verwendet.', 'flynt'),
                'name' => 'eventTitle',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'label' => __('Kurzer Introtext (1 Satz)', 'flynt'),
                'name' => 'intro',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'label' => __('Titelbild', 'flynt'),
                'name' => 'featuredImage',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png',
                'required' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Weitere Fotos (optional)', 'flynt'),
                'name' => 'gallery',
                'type' => 'gallery',
                'mime_types' => 'jpg,jpeg,png',
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Bildnachweise / Credits', 'flynt'),
                'name' => 'credits',
                'type' => 'text',
            ],
            [
                'label' => __('Wann soll eure Veranstaltung stattfinden?', 'flynt'),
                'name' => 'dates',
                'type' => 'checkbox',
                'choices' => $config['dates'],
                'required' => 1,
            ],
            [
                'label' => __('Termine & Zeiten', 'flynt'),
                'name' => 'eventSchedule',
                'type' => 'repeater',
                'instructions' => __('Pro gewähltem Tag Start- und Endzeit.', 'flynt'),
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'label' => __('Tag', 'flynt'),
                        'name' => 'date',
                        'type' => 'text',
                        'wrapper' => ['width' => 34],
                    ],
                    [
                        'label' => __('Startzeit', 'flynt'),
                        'name' => 'timeStart',
                        'type' => 'time_picker',
                        'display_format' => 'H:i',
                        'return_format' => 'H:i',
                        'wrapper' => ['width' => 33],
                    ],
                    [
                        'label' => __('Ende', 'flynt'),
                        'name' => 'timeEnd',
                        'type' => 'time_picker',
                        'display_format' => 'H:i',
                        'return_format' => 'H:i',
                        'wrapper' => ['width' => 33],
                    ],
                ],
            ],
            [
                'label' => __('Override date?', 'flynt'),
                'instructions' => __('Nur im Backend. Ersetzt auf der Eventseite die gewählten Termine durch das Datum rechts.', 'flynt'),
                'name' => 'hasDateOverride',
                'type' => 'true_false',
                'ui' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Datum', 'flynt'),
                'name' => 'dateOverride',
                'type' => 'date_picker',
                'display_format' => 'd.m.Y',
                'return_format' => 'Y-m-d',
                'first_day' => 1,
                'required' => 1,
                'conditional_logic' => [
                    [
                        ['fieldPath' => 'hasDateOverride', 'operator' => '==', 'value' => '1'],
                    ],
                ],
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Veranstaltungsort', 'flynt'),
                'name' => 'venueName',
                'type' => 'text',
            ],
            [
                'label' => __('Wo soll euer Angebot stattfinden?', 'flynt'),
                'name' => 'locationMode',
                'type' => 'radio',
                'choices' => $config['locationMode'],
                'required' => 1,
            ],
            [
                'label' => __('Straße + Hausnummer', 'flynt'),
                'name' => 'street',
                'type' => 'text',
                'conditional_logic' => [
                    [
                        ['fieldPath' => 'locationMode', 'operator' => '==', 'value' => 'eigen'],
                    ],
                ],
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Postleitzahl', 'flynt'),
                'name' => 'postalCode',
                'type' => 'text',
                'conditional_logic' => [
                    [
                        ['fieldPath' => 'locationMode', 'operator' => '==', 'value' => 'eigen'],
                    ],
                ],
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Bezirk', 'flynt'),
                'instructions' => __('Filterbar in der Programmliste. Nicht Teil des öffentlichen Formulars.', 'flynt'),
                'name' => 'district',
                'type' => 'select',
                'choices' => $config['districts'],
                'allow_null' => 1,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Map-Pin', 'flynt'),
                'instructions' => __('Wird beim Speichern aus Straße + PLZ ermittelt. Pin zum Feinjustieren ziehen.', 'flynt'),
                'name' => 'location',
                'type' => 'google_map',
                'height' => 400,
                'zoom' => 13,
            ],
            [
                'label' => __('Weitere Orte', 'flynt'),
                'instructions' => __('Nur im Backend. Jeder Ort bekommt einen eigenen Pin auf der Karte. Der Pin wird beim Speichern aus der Adresse ermittelt und kann danach verschoben werden.', 'flynt'),
                'name' => 'additionalLocations',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Ort hinzufügen', 'flynt'),
                'collapsed' => 'field_eventDetails_additionalLocations_name',
                'sub_fields' => [
                    [
                        'label' => __('Bezeichnung', 'flynt'),
                        'name' => 'name',
                        'type' => 'text',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Adresse', 'flynt'),
                        'instructions' => __('Straße + Hausnummer, PLZ', 'flynt'),
                        'name' => 'address',
                        'type' => 'text',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Map-Pin', 'flynt'),
                        'name' => 'location',
                        'type' => 'google_map',
                        'height' => 300,
                        'zoom' => 13,
                    ],
                ],
            ],
            [
                'label' => __('Hinweise zur klimafreundlichen Anreise', 'flynt'),
                'name' => 'mobilityInfo',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'label' => __('Bietet euer Veranstaltungsort Möglichkeiten für weitere Programmpunkte?', 'flynt'),
                'instructions' => __('Nur für interne Planung.', 'flynt'),
                'name' => 'venueOpenForOthers',
                'type' => 'true_false',
                'ui' => 1,
            ],
            [
                'label' => __('Info über Barrierefreiheit', 'flynt'),
                'name' => 'accessibility',
                'type' => 'checkbox',
                'choices' => $config['accessibility'],
                'required' => 1,
            ],

            // ---- Section 5: Intern & Zustimmung ----
            [
                'label' => __('Intern & Zustimmung', 'flynt'),
                'name' => 'consentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Möchtet ihr euch für eine Programmförderung bewerben?', 'flynt'),
                'instructions' => __('Nur intern.', 'flynt'),
                'name' => 'fundingInterest',
                'type' => 'true_false',
                'ui' => 1,
            ],
            [
                'label' => __('Teilnahmekriterien akzeptiert', 'flynt'),
                'name' => 'acceptCriteria',
                'type' => 'true_false',
                'ui' => 1,
                'wrapper' => ['width' => 33],
            ],
            [
                'label' => __('Nutzungsbedingungen akzeptiert', 'flynt'),
                'name' => 'acceptTerms',
                'type' => 'true_false',
                'ui' => 1,
                'wrapper' => ['width' => 33],
            ],
            [
                'label' => __('Newsletter abonniert', 'flynt'),
                'name' => 'newsletter',
                'type' => 'true_false',
                'ui' => 1,
                'wrapper' => ['width' => 33],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => Event\POST_TYPE,
                ],
            ],
        ],
    ]);
});
