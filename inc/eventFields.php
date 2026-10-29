<?php

/**
 * Shared configuration + helpers for the Event feature.
 *
 * Single source of truth for the submission fields so the public form,
 * the ACF field group and the REST handler never drift apart.
 * Field spec mirrors the client's intake sheet (German labels).
 *
 * Choice labels are client-editable under "Global Options → Event";
 * the stored keys are hardcoded here and must never change so that
 * already-sent submissions stay compatible.
 */

namespace Flynt\Event;

use Flynt\Utils\Options;

const POST_TYPE = 'event';
const NONCE_ACTION = 'looptopia_event';
const LABEL_OPTIONS_SCOPE = 'EventChoiceLabels';

/**
 * Choice lists for every select/checkbox/radio field. Keys are stored,
 * labels are displayed. Single source for form, ACF and REST validation.
 *
 * Labels can be renamed by the client under "Global Options → Event";
 * the keys are fixed here and never change, so submissions sent with
 * the old wording stay fully compatible.
 */
function getConfig()
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $resolved = applyLabelOverrides(getDefaultConfig(), getLabelOverrides());

    // Options are only readable once ACF is initialised. Cache after that;
    // earlier callers just get the built-in default labels (same keys).
    if (did_action('acf/init')) {
        $config = $resolved;
    }

    return $resolved;
}

/**
 * Built-in defaults: the full set of stored keys with their original labels.
 */
function getDefaultConfig()
{
    return [
        // Thema / Ziele — multiple choice, grouped under non-selectable
        // category headings. Flat key=>label map for ACF + REST validation.
        'goalGroups' => getDefaultGoalGroups(),
        'goals' => flattenGroups(getDefaultGoalGroups()),
        // Sektor — multiple choice
        'sectors' => [
            'ernaehrung'    => __('Ernährung', 'flynt'),
            'bauen'         => __('Bauen & Wohnen', 'flynt'),
            'mobilitaet'    => __('Mobilität & Logistik', 'flynt'),
            'digital'       => __('Digitalwirtschaft & Technologie', 'flynt'),
            'kunst'         => __('Kunst & Kreativwirtschaft', 'flynt'),
            'produktion'    => __('Produktion & Industrie', 'flynt'),
            'handel'        => __('Handel & Konsumgüter', 'flynt'),
            'wissenschaft'  => __('Wissenschaft, Forschung & Bildung', 'flynt'),
            'tourismus'     => __('Tourismus, Freizeit & Veranstaltungen', 'flynt'),
        ],
        // Art des Programmpunkts — multiple choice
        'programTypes' => [
            'workshop'   => __('Mitmachaktion / Workshop', 'flynt'),
            'reparatur'  => __('Reparaturangebot / Reparatur Workshop', 'flynt'),
            'panel'      => __('Panel / Vortrag', 'flynt'),
            'kunst'      => __('Kunst / Kultur', 'flynt'),
            'community'  => __('Community / Networking Event', 'flynt'),
            'openhouse'  => __('Open House / Behind the Scene', 'flynt'),
            'tour'       => __('Tour / Walk / Stadt-Erlebnis', 'flynt'),
        ],
        // Zielgruppe — multiple choice, grouped under non-selectable
        // category headings. Flat key=>label map for ACF + REST validation.
        'audienceGroups' => getDefaultAudienceGroups(),
        'audiences' => flattenGroups(getDefaultAudienceGroups()),
        // Barrierefreiheit — multiple choice
        'accessibility' => [
            'eingang'   => __('Eingang barrierefrei', 'flynt'),
            'wc'        => __('WC barrierefrei', 'flynt'),
            'komplett'  => __('Komplett barrierefrei', 'flynt'),
            'keine'     => __('Nicht barrierefrei', 'flynt'),
        ],
        // Format — single choice
        'format' => [
            'workshop'   => __('Mitmachaktion / Workshop', 'flynt'),
            'reparatur'  => __('Reparaturangebot / Reparatur Workshop', 'flynt'),
            'panel'      => __('Panel / Vortrag', 'flynt'),
            'kunst'      => __('Kunst / Kultur', 'flynt'),
            'community'  => __('Community / Networking Event', 'flynt'),
            'openhouse'  => __('Open House / Behind the Scene', 'flynt'),
            'tour'       => __('Tour / Walk / Stadt-Erlebnis', 'flynt'),
        ],
        // Anmeldung erforderlich? — single choice (conditional link)
        'registration' => [
            'offen'      => __('Offenes Format', 'flynt'),
            'anmeldung'  => __('Mit Anmeldung', 'flynt'),
        ],
        // Sprache der Veranstaltung — multiple choice (German language names).
        // Asked in the public form, shown on the map card and the single, and
        // used by the language filter. Keys are ISO 639-1 codes (plus `dgs` for
        // Deutsche Gebärdensprache), because the filter pills render the key
        // uppercased — see languageShortLabel(). The three languages the program
        // actually expects come first, the rest follow alphabetically.
        'languages' => [
            'de'    => __('Deutsch', 'flynt'),
            'en'    => __('Englisch', 'flynt'),
            'de-en' => __('Deutsch & Englisch', 'flynt'),
            'ar'    => __('Arabisch', 'flynt'),
            'bs'    => __('Bosnisch', 'flynt'),
            'bg'    => __('Bulgarisch', 'flynt'),
            'zh'    => __('Chinesisch', 'flynt'),
            'da'    => __('Dänisch', 'flynt'),
            'dgs'   => __('Deutsche Gebärdensprache', 'flynt'),
            'fi'    => __('Finnisch', 'flynt'),
            'fr'    => __('Französisch', 'flynt'),
            'el'    => __('Griechisch', 'flynt'),
            'he'    => __('Hebräisch', 'flynt'),
            'hi'    => __('Hindi', 'flynt'),
            'id'    => __('Indonesisch', 'flynt'),
            'it'    => __('Italienisch', 'flynt'),
            'ja'    => __('Japanisch', 'flynt'),
            'ko'    => __('Koreanisch', 'flynt'),
            'hr'    => __('Kroatisch', 'flynt'),
            'ku'    => __('Kurdisch', 'flynt'),
            'nl'    => __('Niederländisch', 'flynt'),
            'no'    => __('Norwegisch', 'flynt'),
            'fa'    => __('Persisch (Farsi)', 'flynt'),
            'pl'    => __('Polnisch', 'flynt'),
            'pt'    => __('Portugiesisch', 'flynt'),
            'ro'    => __('Rumänisch', 'flynt'),
            'ru'    => __('Russisch', 'flynt'),
            'sv'    => __('Schwedisch', 'flynt'),
            'sr'    => __('Serbisch', 'flynt'),
            'sk'    => __('Slowakisch', 'flynt'),
            'sl'    => __('Slowenisch', 'flynt'),
            'es'    => __('Spanisch', 'flynt'),
            'sw'    => __('Swahili', 'flynt'),
            'th'    => __('Thailändisch', 'flynt'),
            'cs'    => __('Tschechisch', 'flynt'),
            'tr'    => __('Türkisch', 'flynt'),
            'uk'    => __('Ukrainisch', 'flynt'),
            'hu'    => __('Ungarisch', 'flynt'),
            'vi'    => __('Vietnamesisch', 'flynt'),
        ],
        // Kosten? — single choice (conditional price + link)
        'costs' => [
            'nein' => __('Nein', 'flynt'),
            'ja'   => __('Ja', 'flynt'),
        ],
        // Wann? — event runs on 14. and/or 15.11.26, both selectable.
        // Keys are ISO dates (stable for storage); labels are display-only.
        'dates' => [
            '2026-11-14' => __('14.11.26', 'flynt'),
            '2026-11-15' => __('15.11.26', 'flynt'),
        ],
        // Bezirk — single choice, set by the editor in wp-admin (not part of
        // the public form). Drives the district filter on the program list.
        'districts' => [
            'mitte'          => __('Mitte', 'flynt'),
            'friedrichshain' => __('Friedrichshain-Kreuzberg', 'flynt'),
            'pankow'         => __('Pankow', 'flynt'),
            'charlottenburg' => __('Charlottenburg-Wilmersdorf', 'flynt'),
            'spandau'        => __('Spandau', 'flynt'),
            'steglitz'       => __('Steglitz-Zehlendorf', 'flynt'),
            'tempelhof'      => __('Tempelhof-Schöneberg', 'flynt'),
            'neukoelln'      => __('Neukölln', 'flynt'),
            'treptow'        => __('Treptow-Köpenick', 'flynt'),
            'marzahn'        => __('Marzahn-Hellersdorf', 'flynt'),
            'lichtenberg'    => __('Lichtenberg', 'flynt'),
            'reinickendorf'  => __('Reinickendorf', 'flynt'),
        ],
        // Wo? — either/or radio
        'locationMode' => [
            'suche' => __('Ich habe keinen  passenden Veranstaltungsort und freue mich über Tipps und/oder Vernetzung', 'flynt'),
            'eigen' => __('Ich habe einen passenden Veranstaltungsort', 'flynt'),
        ],
    ];
}

/**
 * Thema / Ziele grouped under non-selectable category headings.
 * Only the choices are selectable; the group labels are display-only.
 * Array keys are the stable group slugs used for the label options.
 */
function getDefaultGoalGroups()
{
    return [
        'erlebbar' => [
            'label' => __('Kreislaufwirtschaft, die erlebbar ist', 'flynt'),
            'choices' => [
                'orte'          => __('Alltägliche Orte, um zirkuläre Lösungen auszuprobieren', 'flynt'),
                'nachbarschaft' => __('Nachbarschaft, Teilhabe & soziale Innovation', 'flynt'),
            ],
        ],
        'lohnt' => [
            'label' => __('Kreislaufwirtschaft, die sich lohnt', 'flynt'),
            'choices' => [
                'instrumente'       => __('Instrumente & Hilfsmittel für die Transformation', 'flynt'),
                'geschaeftsmodelle' => __('Zirkuläre Geschäftsmodelle & Innovation', 'flynt'),
                'finanzierung'      => __('Finanzierung & Skalierung', 'flynt'),
            ],
        ],
        'zukunft' => [
            'label' => __('Kreislaufwirtschaft, die Zukunft gestaltet', 'flynt'),
            'choices' => [
                'politik'     => __('Politische Hebel & Rahmenbedingungen für Circular Economy', 'flynt'),
                'kooperation' => __('Kooperation, Beteiligung & Wissensaufbau', 'flynt'),
            ],
        ],
    ];
}

/**
 * Zielgruppe grouped under non-selectable category headings.
 * Only the choices are selectable; the group labels are display-only.
 * Array keys are the stable group slugs used for the label options.
 */
function getDefaultAudienceGroups()
{
    return [
        'fach' => [
            'label' => __('Fachveranstaltung', 'flynt'),
            'choices' => [
                'unternehmen'  => __('Unternehmen', 'flynt'),
                'wissenschaft' => __('Wissenschaft & Bildung', 'flynt'),
                'politik'      => __('Politik & Verwaltung', 'flynt'),
            ],
        ],
        'freizeit' => [
            'label' => __('Freizeit', 'flynt'),
            'choices' => [
                'erwachsene' => __('Erwachsene', 'flynt'),
                'senioren'   => __('Senior:innen', 'flynt'),
                'jugend'     => __('Jugendliche', 'flynt'),
                'familien'   => __('Familien & Kinder', 'flynt'),
            ],
        ],
    ];
}

/**
 * Merge a grouped choice structure into a single flat key=>label map.
 */
function flattenGroups(array $groups)
{
    $flat = [];
    foreach ($groups as $group) {
        $flat += $group['choices'];
    }
    return $flat;
}

/**
 * Flat choice lists whose labels are client-editable (grouped lists are
 * handled separately). Shared by the option fields and the overlay.
 */
const FLAT_LABEL_LISTS = ['sectors', 'programTypes', 'accessibility', 'registration', 'costs', 'format', 'dates', 'locationMode', 'languages', 'districts'];

/**
 * Option field name for a choice key, e.g. sectors/ernaehrung →
 * `sectors_ernaehrung`. Hyphens are stripped so ISO date keys stay valid
 * ACF field names ('2026-11-14' → `dates_20261114`).
 */
function labelOptionName($list, $key)
{
    return $list . '_' . str_replace('-', '', $key);
}

/**
 * Client-saved label texts, keyed by option field name. Empty fields fall
 * back to the defaults. Returns [] before ACF is ready.
 */
function getLabelOverrides()
{
    if (!did_action('acf/init')) {
        return [];
    }

    $saved = Options::getGlobal(LABEL_OPTIONS_SCOPE);
    if (!is_array($saved)) {
        return [];
    }

    return array_filter($saved, fn ($value) => is_string($value) && trim($value) !== '');
}

/**
 * Replace default labels with the client-edited ones. Keys are never
 * touched — only the display text changes.
 */
function applyLabelOverrides(array $config, array $overrides)
{
    if (!$overrides) {
        return $config;
    }

    foreach (FLAT_LABEL_LISTS as $list) {
        foreach ($config[$list] as $key => $label) {
            $option = labelOptionName($list, $key);
            if (isset($overrides[$option])) {
                $config[$list][$key] = $overrides[$option];
            }
        }
    }

    foreach (['goalGroups' => 'goals', 'audienceGroups' => 'audiences'] as $groupList => $flatList) {
        foreach ($config[$groupList] as $slug => $group) {
            $groupOption = $flatList . 'Group_' . $slug;
            if (isset($overrides[$groupOption])) {
                $config[$groupList][$slug]['label'] = $overrides[$groupOption];
            }
            foreach ($group['choices'] as $key => $label) {
                $option = labelOptionName($flatList, $key);
                if (isset($overrides[$option])) {
                    $config[$groupList][$slug]['choices'][$key] = $overrides[$option];
                }
            }
        }
        $config[$flatList] = flattenGroups($config[$groupList]);
    }

    return $config;
}

/**
 * Section headings for the public form. Keys are fixed and map to the
 * `<fieldset>` blocks in Components/FormEvent/index.twig; the title and intro
 * are client-editable under "Global Options → Event". Intros default to empty,
 * so no intro is shown until one is entered.
 */
function getDefaultSections()
{
    return [
        'org'      => ['title' => __('Organisation', 'flynt'), 'intro' => ''],
        'offer'    => ['title' => __('Angebot', 'flynt'), 'intro' => ''],
        'details'  => ['title' => __('Details zum Angebot', 'flynt'), 'intro' => ''],
        'audience' => ['title' => __('Zielgruppe', 'flynt'), 'intro' => ''],
        'event'    => ['title' => __('Veranstaltung', 'flynt'), 'intro' => ''],
        'consent'  => ['title' => __('Anmeldeformular absenden', 'flynt'), 'intro' => ''],
    ];
}

/**
 * Option field name for a section part, e.g. org/title → `section_org_title`.
 */
function sectionOptionName($key, $part)
{
    return 'section_' . $key . '_' . $part;
}

/**
 * Section headings with the client-edited title/intro applied, falling back to
 * the defaults for any blank field. Shares the label-override scope, so the
 * single "Global Options → Event" page drives both choice labels and headings.
 */
function getSections()
{
    $overrides = getLabelOverrides();
    $sections = getDefaultSections();

    foreach ($sections as $key => $section) {
        foreach (['title', 'intro'] as $part) {
            $option = sectionOptionName($key, $part);
            if (isset($overrides[$option])) {
                $sections[$key][$part] = $overrides[$option];
            }
        }
    }

    return $sections;
}

/**
 * Labels for the form's free-text fields and its question legends — every
 * `font-bodySmall` label in Components/FormEvent/index.twig that isn't a choice
 * option. Keys are fixed and map to the template; the text is client-editable
 * under "Global Options → Event". Listed in form order. The trailing " *" and
 * file-type hints stay in the template, so only the wording lives here.
 */
function getDefaultFieldLabels()
{
    return [
        // 1. Organisation
        'orgName'        => __('Name der Organisation', 'flynt'),
        'contactPerson'  => __('Kontaktperson', 'flynt'),
        'contactEmail'   => __('Mailadresse', 'flynt'),
        'orgLogo'        => __('Logo der Organisation', 'flynt'),
        'website'        => __('Website', 'flynt'),
        'instagram'      => __('Instagram', 'flynt'),
        'linkedin'       => __('LinkedIn', 'flynt'),
        // 2. Angebot
        'goalsQuestion'        => __('Thema — Welche Ziele verfolgt dein Programm?', 'flynt'),
        'sectorsQuestion'      => __('Sektor', 'flynt'),
        'registrationQuestion' => __('Ist eine Anmeldung erforderlich?', 'flynt'),
        'registrationLink'     => __('Anmeldelink', 'flynt'),
        'costsQuestion'        => __('Fallen für die Teilnahme Kosten an?', 'flynt'),
        'price'                => __('Preis', 'flynt'),
        'paymentLink'          => __('Link zur Bezahlung / Buchung', 'flynt'),
        // 3. Details
        'description'          => __('Beschreibungstext des Angebots (max. 1.000 Zeichen)', 'flynt'),
        'programTypesQuestion' => __('Art des Programmpunkts', 'flynt'),
        'formatQuestion'       => __('Format', 'flynt'),
        'languageQuestion'     => __('Sprache', 'flynt'),
        // 4. Veranstaltung
        'eventTitle'       => __('Titel der Veranstaltung', 'flynt'),
        'intro'            => __('Kurzer Introtext (1 Satz)', 'flynt'),
        'featuredImage'    => __('Titelbild', 'flynt'),
        'gallery'          => __('Weitere Fotos (optional)', 'flynt'),
        'credits'          => __('Bildnachweise / Credits', 'flynt'),
        'datesQuestion'    => __('Wann soll deine Veranstaltung am 14. oder 15.11.26 stattfinden?', 'flynt'),
        'timeStart'        => __('Startzeit', 'flynt'),
        'timeEnd'          => __('Ende', 'flynt'),
        'venueName'        => __('Veranstaltungsort', 'flynt'),
        'locationQuestion' => __('Wo soll dein Angebot stattfinden?', 'flynt'),
        'street'           => __('Straße + Hausnummer', 'flynt'),
        'postalCode'       => __('Postleitzahl', 'flynt'),
        'mobilityInfo'     => __('Hinweise zur klimafreundlichen Anreise mit ÖPNV oder Fahrrad', 'flynt'),
        'accessibilityQuestion' => __('Info über Barrierefreiheit', 'flynt'),
    ];
}

/**
 * Option field name for a field label, e.g. orgName → `field_orgName`.
 */
function fieldLabelOptionName($key)
{
    return 'field_' . $key;
}

/**
 * Field + legend labels with the client-edited text applied, falling back to
 * the defaults for any blank field. Shares the label-override scope.
 */
function getFieldLabels()
{
    $overrides = getLabelOverrides();
    $labels = getDefaultFieldLabels();

    foreach ($labels as $key => $default) {
        $option = fieldLabelOptionName($key);
        if (isset($overrides[$option])) {
            $labels[$key] = $overrides[$option];
        }
    }

    return $labels;
}

/**
 * One text field per choice label for the "Global Options → Event" page.
 * Fields are generated from the defaults so the two can never drift; the
 * default label doubles as field label and placeholder.
 */
function getLabelOptionFields()
{
    $config = getDefaultConfig();

    $tab = fn ($label, $name) => [
        'label' => $label,
        'name' => $name,
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    ];
    $text = fn ($name, $default, $width = 50, $instructions = '') => [
        'label' => $default,
        'name' => $name,
        'type' => 'text',
        'instructions' => $instructions,
        'placeholder' => $default,
        'wrapper' => ['width' => $width],
    ];

    $fields = [
        [
            'label' => __('Hinweis', 'flynt'),
            'name' => 'labelsInfo',
            'type' => 'message',
            'message' => __('Beschriftungen der Auswahlfelder — sichtbar im Anmeldeformular, im Backend und auf der Karte. Leere Felder verwenden die Standard-Beschriftung (grau angezeigt). Die intern gespeicherten Werte ändern sich nicht, bereits eingegangene Einsendungen bleiben vollständig kompatibel.', 'flynt'),
        ],
    ];

    // Section headings tab — title + optional intro for each form section.
    $fields[] = $tab(__('Sections', 'flynt'), 'sectionsTab');
    foreach (getDefaultSections() as $key => $section) {
        $fields[] = $text(sectionOptionName($key, 'title'), $section['title'], 100, __('Section heading', 'flynt'));
        $fields[] = [
            'label' => sprintf(__('%s — Intro', 'flynt'), $section['title']),
            'name' => sectionOptionName($key, 'intro'),
            'type' => 'wysiwyg',
            'tabs' => 'visual',
            'media_upload' => 0,
            'delay' => 1,
            'instructions' => __('Optional intro text shown below the heading.', 'flynt'),
        ];
    }

    // Field labels tab — every free-text field label + question legend.
    $fields[] = $tab(__('Field labels', 'flynt'), 'fieldLabelsTab');
    foreach (getDefaultFieldLabels() as $key => $default) {
        $fields[] = $text(fieldLabelOptionName($key), $default, mb_strlen($default) > 60 ? 100 : 50);
    }

    $groupedTabs = [
        'goalGroups' => ['goals', __('Thema / Ziele', 'flynt')],
        'audienceGroups' => ['audiences', __('Zielgruppe', 'flynt')],
    ];
    foreach ($groupedTabs as $groupList => [$flatList, $tabLabel]) {
        $fields[] = $tab($tabLabel, $flatList . 'Tab');
        foreach ($config[$groupList] as $slug => $group) {
            $fields[] = $text($flatList . 'Group_' . $slug, $group['label'], 100, __('Gruppenüberschrift', 'flynt'));
            foreach ($group['choices'] as $key => $label) {
                $fields[] = $text(labelOptionName($flatList, $key), $label);
            }
        }
    }

    $flatTabs = [
        'sectors' => __('Sektor', 'flynt'),
        'programTypes' => __('Art des Programmpunkts', 'flynt'),
        'accessibility' => __('Barrierefreiheit', 'flynt'),
        'registration' => __('Anmeldung', 'flynt'),
        'costs' => __('Kosten', 'flynt'),
        'format' => __('Format', 'flynt'),
        'dates' => __('Termine', 'flynt'),
        'locationMode' => __('Veranstaltungsort', 'flynt'),
        'languages' => __('Sprache', 'flynt'),
        'districts' => __('Bezirk', 'flynt'),
    ];
    foreach ($flatTabs as $list => $tabLabel) {
        $fields[] = $tab($tabLabel, $list . 'Tab');
        foreach ($config[$list] as $key => $label) {
            $fields[] = $text(labelOptionName($list, $key), $label, mb_strlen($label) > 60 ? 100 : 50);
        }
    }

    return $fields;
}

Options::addGlobal(LABEL_OPTIONS_SCOPE, getLabelOptionFields(), 'Event');

/**
 * Icon per "Format", used by the map pins and the card badges.
 * Values are file names inside assets/icons/event/.
 */
function getFormatIcons()
{
    return [
        'workshop'  => 'Workshop.svg',
        'reparatur' => 'Repair.svg',
        'panel'     => 'Vortraege.svg',
        'kunst'     => 'Culture.svg',
        'community' => 'Community.svg',
        'openhouse' => 'BehindTheScenes.svg',
        'tour'      => 'CityTour.svg',
    ];
}

/**
 * Icon file name for one entry. Driven by `format`; entries submitted before
 * the format list was reworked fall back to their first "Art des
 * Programmpunkts" (same keys), and to the community glyph if neither matches.
 */
function getFormatIcon($postId)
{
    $icons = getFormatIcons();
    $format = (string) (get_field('format', $postId) ?: '');
    $programTypes = (array) (get_field('programTypes', $postId) ?: []);

    return $icons[$format] ?? $icons[reset($programTypes) ?: ''] ?? 'Community.svg';
}

/**
 * Day rows of a single entry: `{ key, date, time, start, end }` per event day.
 *
 * The `eventSchedule` repeater stores the day's *label* (as it read at
 * submission time), so rows are matched back to their stable date key by
 * label first and by position as a fallback — labels are client-editable.
 */
function getScheduleRows($postId)
{
    $config = getConfig();
    $dates = array_values((array) (get_field('dates', $postId) ?: []));
    $rows = (array) (get_field('eventSchedule', $postId) ?: []);
    $keyByLabel = array_flip($config['dates']);

    return array_values(array_map(function ($row, $index) use ($dates, $keyByLabel) {
        $label = (string) ($row['date'] ?? '');
        $start = (string) ($row['timeStart'] ?? '');
        $end = (string) ($row['timeEnd'] ?? '');

        return [
            'key'   => $keyByLabel[$label] ?? ($dates[$index] ?? ''),
            'date'  => $label,
            'time'  => $end ? $start . '–' . $end : $start,
            'start' => $start,
            'end'   => $end,
        ];
    }, $rows, array_keys($rows)));
}

/**
 * Selectable days for the filter bars: the configured event days as day number
 * plus short weekday, e.g. "SA / 14".
 *
 * Weekdays are the German abbreviations that go with this feature's German
 * source copy — wp_date() would follow the site language instead. The date
 * key is read as a plain calendar date, so no timezone can shift the day.
 */
function getDays()
{
    $weekdays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

    $days = [];
    foreach (getConfig()['dates'] as $key => $label) {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $key);
        $days[] = [
            'key'     => $key,
            'label'   => $label,
            'day'     => $date ? $date->format('j') : $label,
            'weekday' => $date ? $weekdays[(int) $date->format('N') - 1] : '',
        ];
    }
    return $days;
}

/**
 * Short code shown on the map's language filter, e.g. `de-en` → "DE/EN".
 */
function languageShortLabel($key)
{
    return strtoupper(str_replace('-', '/', (string) $key));
}

/**
 * Accessibility keys that mean "at least partly barrier-free" — everything
 * except the explicit "Nicht barrierefrei". Drives the wheelchair icon on the
 * map card and the wheelchair filter.
 */
function isAccessible(array $keys)
{
    return (bool) array_diff($keys, ['keine']);
}

/**
 * Should the entry be listed in the program list? Driven by the `showInProgram`
 * toggle in wp-admin, which defaults to on.
 *
 * The raw meta is read rather than get_field() so entries saved before the
 * toggle existed — they carry no meta at all — stay visible instead of
 * depending on ACF resolving the field default.
 */
function isListed($postId)
{
    $value = get_post_meta($postId, 'showInProgram', true);

    return $value === '' ? true : (bool) $value;
}

/**
 * Is the entry pinned to the top of the program list? Driven by the
 * `programPosition` choice in wp-admin; entries without the meta keep their
 * chronological position.
 */
function isPinned($postId)
{
    return get_post_meta($postId, 'programPosition', true) === 'top';
}

const GEOCODE_ERROR_META = '_geocodeError';

/**
 * Geocode a free-text address into coordinates.
 *
 * Google is tried first when a key is configured, Nominatim (OpenStreetMap,
 * keyless — same spirit as the map tiles) picks up whatever Google refuses.
 * The Theme Options key is usually a browser key with referrer restrictions,
 * which the Geocoding API rejects server side; the fallback keeps entries
 * pinned regardless.
 *
 * Returns null on failure. The reason is written to $error so callers can log
 * it and surface it in wp-admin instead of silently storing an entry with no
 * pin — see the acf/save_post hook below.
 *
 * @param string      $address Free-text address.
 * @param string|null $error   Out: human-readable failure reason.
 * @return array{lat: float, lng: float, formatted: string, provider: string}|null
 */
function geocodeAddress($address, &$error = null)
{
    $error = null;
    $address = trim((string) $address);
    if ($address === '') {
        $error = __('Keine Adresse hinterlegt.', 'flynt');
        return null;
    }

    $reasons = [];
    $apiKey = Options::getGlobal('Acf', 'googleMapsApiKey');

    // Submitted addresses often carry entrance/floor notes that the geocoders
    // choke on, so retry with progressively simpler variants.
    foreach (addressVariants($address) as $variant) {
        if (!empty($apiKey)) {
            $result = geocodeViaGoogle($variant, $apiKey, $googleError);
            if ($result) {
                return $result;
            }
            $reasons[] = 'Google: ' . $googleError;
        }

        $result = geocodeViaNominatim($variant, $nominatimError);
        if ($result) {
            return $result;
        }
        $reasons[] = 'Nominatim: ' . $nominatimError;
    }

    $error = implode(' | ', array_unique($reasons));

    return null;
}

/**
 * Progressively simpler forms of an address, most complete first:
 *   1. as submitted
 *   2. without parenthetical notes and floor/entrance hints
 *   3. only the street segment (the one carrying a house number) + postal code
 *
 * @return string[]
 */
function addressVariants($address)
{
    $variants = [$address];

    // Drop "(Eingang: …)" style notes and access hints the geocoders reject.
    $clean = preg_replace('/\s*\([^)]*\)/u', '', $address);
    $clean = preg_replace('/,?\s*\b(\d+\.\s*)?(Stock|Etage|OG|EG|Eg|VH|HH|Hinterhaus|Vorderhaus|Dachgeschoss|Hall|Halle\s*\d*)\b[^,]*/iu', '', $clean);
    $clean = normalizeAddressPunctuation($clean);
    $variants[] = $clean;

    // Berlin, Germany is appended by composeAddress and always kept.
    $parts = array_map('trim', explode(',', $clean));
    $tail = array_slice($parts, -2);
    $body = array_slice($parts, 0, max(0, count($parts) - 2));

    // Postal codes arrive as "10967 Berlin-Bezirk …"; keep the digits only.
    $postal = '';
    foreach ($body as $index => $part) {
        if (preg_match('/\b(\d{5})\b/', $part, $match)) {
            $postal = $match[1];
            unset($body[$index]);
            break;
        }
    }

    // The street is the first remaining segment that reads like "name + number";
    // venue names and duplicated address fragments sit around it.
    $street = '';
    foreach ($body as $part) {
        if (preg_match('/\p{L}/u', $part) && preg_match('/\d/', $part)) {
            $street = $part;
            break;
        }
    }

    if ($street !== '') {
        $variants[] = normalizeAddressPunctuation(implode(', ', array_filter([$street, $postal, ...$tail])));
    }

    return array_values(array_unique(array_filter(array_map('trim', $variants))));
}

/**
 * Collapse the empty segments and doubled spaces left behind by stripping.
 */
function normalizeAddressPunctuation($address)
{
    $address = preg_replace('/\s+/u', ' ', $address);
    $parts = array_filter(array_map('trim', explode(',', $address)), fn ($part) => $part !== '');

    return implode(', ', $parts);
}

/**
 * Google Geocoding API. Reuses the key configured for the ACF Google Map
 * field (Theme Options); that key must be unrestricted or IP-restricted for
 * server-side calls to be accepted.
 */
function geocodeViaGoogle($address, $apiKey, &$error = null)
{
    // add_query_arg url-encodes values, so pass the raw address.
    $url = add_query_arg([
        'address' => $address,
        'key'     => $apiKey,
    ], 'https://maps.googleapis.com/maps/api/geocode/json');

    $response = wp_remote_get($url, ['timeout' => 8]);
    if (is_wp_error($response)) {
        $error = $response->get_error_message();
        return null;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($body['results'][0]['geometry']['location'])) {
        $error = trim(($body['status'] ?? 'UNKNOWN_ERROR') . ' ' . ($body['error_message'] ?? ''));
        return null;
    }

    $location = $body['results'][0]['geometry']['location'];

    return [
        'lat'       => (float) $location['lat'],
        'lng'       => (float) $location['lng'],
        'formatted' => $body['results'][0]['formatted_address'] ?? $address,
        'provider'  => 'google',
    ];
}

/**
 * Nominatim (OpenStreetMap). Keyless, but the usage policy requires an
 * identifying User-Agent and at most one request per second — the throttle
 * below keeps bulk backfills compliant.
 *
 * @see https://operations.osmfoundation.org/policies/nominatim/
 */
function geocodeViaNominatim($address, &$error = null)
{
    static $lastRequest = 0.0;

    $wait = 1.0 - (microtime(true) - $lastRequest);
    if ($wait > 0) {
        usleep((int) ($wait * 1000000));
    }
    $lastRequest = microtime(true);

    $url = add_query_arg([
        'q'              => $address,
        'format'         => 'jsonv2',
        'limit'          => 1,
        'countrycodes'   => 'de',
        'addressdetails' => 0,
    ], 'https://nominatim.openstreetmap.org/search');

    $response = wp_remote_get($url, [
        'timeout' => 10,
        'headers' => [
            'User-Agent' => 'LOOPTOPIA/1.0 (' . home_url() . ')',
            'Referer'    => home_url(),
        ],
    ]);

    if (is_wp_error($response)) {
        $error = $response->get_error_message();
        return null;
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code !== 200) {
        $error = 'HTTP ' . $code;
        return null;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($body[0]['lat']) || empty($body[0]['lon'])) {
        $error = 'ZERO_RESULTS';
        return null;
    }

    return [
        'lat'       => (float) $body[0]['lat'],
        'lng'       => (float) $body[0]['lon'],
        'formatted' => $body[0]['display_name'] ?? $address,
        'provider'  => 'nominatim',
    ];
}

/**
 * Compose a geocodable address from the submitted street + postal code.
 * Berlin is appended to keep results within the city.
 */
function composeAddress($street, $postalCode)
{
    $parts = array_filter([trim((string) $street), trim((string) $postalCode), 'Berlin, Germany']);
    return implode(', ', $parts);
}

/**
 * Give one entry a map pin from its street + postal code.
 *
 * Shared by the save hook below and the backfill tool (inc/eventGeocodeTool.php)
 * so both behave identically.
 *
 * @param int  $postId
 * @param bool $force Re-geocode even if a pin is already set.
 * @return string One of: has-pin, no-address, pinned, failed.
 */
function geocodePost($postId, $force = false)
{
    $location = get_field('location', $postId);
    if (!$force && !empty($location['lat'])) {
        return 'has-pin';
    }

    $street = (string) get_field('street', $postId);
    $postalCode = (string) get_field('postalCode', $postId);

    // Nothing to geocode yet (venue still being sought) — not an error.
    if (trim($street) === '' && trim($postalCode) === '') {
        delete_post_meta($postId, GEOCODE_ERROR_META);
        return 'no-address';
    }

    $geo = geocodeAddress(composeAddress($street, $postalCode), $error);
    if (!$geo) {
        storeGeocodeError($postId, $error);
        return 'failed';
    }

    delete_post_meta($postId, GEOCODE_ERROR_META);
    update_field('location', [
        'address' => $geo['formatted'],
        'lat'     => $geo['lat'],
        'lng'     => $geo['lng'],
    ], $postId);

    return 'pinned';
}

/**
 * Every event that could get a pin but hasn't got one: an address on file and
 * no coordinates. The pin lives in a serialised array, so the filtering happens
 * in PHP rather than in a meta query.
 *
 * @param bool $skipFailed Leave out entries whose address already failed, so a
 *                         batch run drains instead of retrying them forever.
 *                         The error is cleared whenever the address is saved
 *                         again, which puts the entry back in the queue.
 * @return int[]
 */
function eventsNeedingPin($skipFailed = false)
{
    $posts = get_posts([
        'post_type'        => POST_TYPE,
        'post_status'      => ['publish', 'pending', 'draft', 'future'],
        'posts_per_page'   => -1,
        'fields'           => 'ids',
        'suppress_filters' => false,
    ]);

    return array_values(array_filter($posts, function ($postId) use ($skipFailed) {
        $location = get_field('location', $postId);
        if (!empty($location['lat'])) {
            return false;
        }

        if ($skipFailed && get_post_meta($postId, GEOCODE_ERROR_META, true)) {
            return false;
        }

        $address = trim((string) get_field('street', $postId) . (string) get_field('postalCode', $postId));

        return $address !== '';
    }));
}

/**
 * Re-geocode when an editor saves an entry with an address but no pin yet.
 * Lets the client adjust street/PLZ in wp-admin and get a marker automatically,
 * while still being able to drag the pin manually afterwards.
 */
add_action('acf/save_post', function ($postId) {
    if (get_post_type($postId) !== POST_TYPE) {
        return;
    }

    geocodePost($postId);
    geocodeAdditionalLocations($postId);
}, 20);

const ADDITIONAL_GEOCODE_ERROR_META = '_additionalGeocodeErrors';

/**
 * Pin every "Weitere Orte" row that has an address but no pin yet.
 * Backend only — the public form never fills this repeater.
 *
 * @return string[] Addresses that could not be geocoded.
 */
function geocodeAdditionalLocations($postId)
{
    $rows = get_field('additionalLocations', $postId) ?: [];
    $failed = [];

    foreach ($rows as $index => $row) {
        $address = trim((string) ($row['address'] ?? ''));
        if ($address === '' || !empty($row['location']['lat'])) {
            continue;
        }

        $geo = geocodeAddress(composeAddress($address, ''), $error);
        if (!$geo) {
            $failed[] = $address;
            error_log(sprintf('[looptopia] Geocoding failed for event %d, location "%s": %s', $postId, $address, $error));
            continue;
        }

        // Row numbers in update_sub_field() are 1-based.
        update_sub_field(['additionalLocations', $index + 1, 'location'], [
            'address' => $geo['formatted'],
            'lat'     => $geo['lat'],
            'lng'     => $geo['lng'],
        ], $postId);
    }

    if ($failed) {
        update_post_meta($postId, ADDITIONAL_GEOCODE_ERROR_META, $failed);
    } else {
        delete_post_meta($postId, ADDITIONAL_GEOCODE_ERROR_META);
    }

    return $failed;
}

/**
 * Lowercased haystack the search boxes match against. It holds what the entry
 * prints — title, organiser, tags and address — so any word the visitor can
 * read also finds it. Shared by the program list and the map so a term that
 * works in one works in the other.
 */
function buildSearchIndex(array $parts)
{
    $text = implode(' ', array_filter(array_map('strval', $parts)));

    return trim(preg_replace('/\s+/u', ' ', mb_strtolower($text)));
}

/**
 * Venue address as printed lines. Berlin is implied by the feature (the
 * geocoder appends it too), so only street and postal code are stored.
 */
function getAddressLines($postId)
{
    $street = trim((string) (get_field('street', $postId) ?: ''));
    $postalCode = trim((string) (get_field('postalCode', $postId) ?: ''));

    return array_values(array_filter([
        $street,
        $postalCode ? $postalCode . ' ' . __('Berlin', 'flynt') : '',
    ]));
}

/**
 * All map points of an entry: the main pin first, then "Weitere Orte".
 *
 * @return array<int, array{lat: float, lng: float, name: string}>
 */
function getMapPoints($postId)
{
    $points = [];

    $location = get_field('location', $postId);
    if (!empty($location['lat']) && !empty($location['lng'])) {
        $points[] = [
            'lat'  => (float) $location['lat'],
            'lng'  => (float) $location['lng'],
            'name' => (string) (get_field('venueName', $postId) ?: ''),
        ];
    }

    foreach (get_field('additionalLocations', $postId) ?: [] as $row) {
        if (empty($row['location']['lat']) || empty($row['location']['lng'])) {
            continue;
        }
        $points[] = [
            'lat'  => (float) $row['location']['lat'],
            'lng'  => (float) $row['location']['lng'],
            'name' => (string) ($row['name'] ?? ''),
        ];
    }

    return $points;
}

/**
 * Record why an entry has no pin: the error log for developers, post meta for
 * the notice on the edit screen. Without this the map just silently loses the
 * entry.
 */
function storeGeocodeError($postId, $error)
{
    $error = (string) $error;
    update_post_meta($postId, GEOCODE_ERROR_META, $error);
    error_log(sprintf('[looptopia] Geocoding failed for event %d: %s', $postId, $error));
}

/**
 * Warn the editor when an entry could not be placed on the map, so a missing
 * pin is noticed here rather than on the live map.
 */
add_action('admin_notices', function () {
    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'post' || $screen->post_type !== POST_TYPE) {
        return;
    }

    $postId = get_the_ID();

    $additionalFailed = (array) get_post_meta($postId, ADDITIONAL_GEOCODE_ERROR_META, true);
    $additionalFailed = array_filter($additionalFailed);
    if ($additionalFailed) {
        printf(
            '<div class="notice notice-warning"><p><strong>%s</strong> %s</p><p><code>%s</code></p></div>',
            esc_html__('Weitere Orte ohne Map-Pin:', 'flynt'),
            esc_html__('Diese Adressen konnten nicht in Koordinaten umgewandelt werden. Bitte Adresse prüfen oder den Pin manuell setzen.', 'flynt'),
            esc_html(implode(' · ', $additionalFailed))
        );
    }

    $error = get_post_meta($postId, GEOCODE_ERROR_META, true);
    if (!$error) {
        return;
    }

    printf(
        '<div class="notice notice-warning"><p><strong>%s</strong> %s</p><p><code>%s</code></p></div>',
        esc_html__('Kein Map-Pin:', 'flynt'),
        esc_html__('Die Adresse konnte nicht in Koordinaten umgewandelt werden. Der Eintrag erscheint nicht auf der Karte. Bitte Straße und PLZ prüfen oder den Pin manuell setzen.', 'flynt'),
        esc_html($error)
    );
});
