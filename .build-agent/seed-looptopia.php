<?php
/**
 * Idempotent seed for the Looptopia front page.
 * Run: ddev wp eval-file .../.build-agent/seed-looptopia.php --user=1
 * Image attachment IDs (from `wp media import`):
 *   about=5, zero-waste-badge=6, card-1=7, card-2=8, card-3=9,
 *   natuschutz=10 (Stiftung Naturschutz Berlin), circular-berlin=11
 */

if (!function_exists('update_field')) {
    WP_CLI::error('ACF not loaded.');
}

$ABOUT = 5;
$BADGE = 6;
$CARD1 = 7; $CARD2 = 8; $CARD3 = 9;
$FUND_NATURSCHUTZ = 10; $FUND_CIRCULAR = 11;

function lt_link($title, $url = '#', $target = '') {
    return ['title' => $title, 'url' => $url, 'target' => $target];
}

// --- 1. Front page (idempotent by slug) -------------------------------------
$slug = 'home';
$page = get_page_by_path($slug);
if ($page) {
    $pageId = $page->ID;
    WP_CLI::log("Using existing page #{$pageId}");
} else {
    $pageId = wp_insert_post([
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => 'Looptopia',
        'post_name'    => $slug,
        'post_content' => '',
    ]);
    WP_CLI::log("Created page #{$pageId}");
}

// Make it the static front page.
update_option('show_on_front', 'page');
update_option('page_on_front', $pageId);

// --- 2. Flexible content rows ------------------------------------------------
$rows = [];

// Hero
$rows[] = [
    'acf_fc_layout' => 'BlockHero',
    'kicker'        => 'BAU MIT UNS',
    'dateText'      => '13.–15.11.26',
    'headline'      => '48 Stunden Berlin neu entdecken',
    'scrollAnchor'  => '',
    'funders'       => [
        'label' => 'Gefördert durch',
        'logos' => [
            ['logo' => $FUND_NATURSCHUTZ],
            ['logo' => $FUND_CIRCULAR],
        ],
    ],
];

// Intro text
$rows[] = [
    'acf_fc_layout' => 'blockWysiwyg',
    'contentHtml'   => '<p>LOOPTOPIA ist die Stadt von morgen: wo zukunftsfähiges Wirtschaften normal ist; wo Menschen und vielfältige Communities vernetzt sind; wo Kreislaufwirtschaft gelebter Alltag ist. Berlin hat das Potenzial, diese Stadt zu werden. Von 13. bis 15. November 2026 stellen wir das gemeinsam unter Beweis. 48 Stunden lang, an Orten in der ganzen Stadt. Als dezentrales Stadtfestival bringt LOOPTOPIA zirkuläre Lösungen dorthin, wo Berliner:innen ihren Alltag verbringen: in Clubs, Museen, Spätis &amp; Co. Mit dir wird ganz Berlin zum Experimentierraum – und kreislauforientierte Lösungen werden sichtbar, erlebbar und angreifbar.</p>',
];

// Intro CTAs
$rows[] = [
    'acf_fc_layout' => 'BlockButtons',
    'buttons'       => [
        ['buttonLink' => lt_link('Programmidee einreichen', '#teilnehmen')],
        ['buttonLink' => lt_link('LOOPTOPIA unterstützen', '#partner')],
    ],
];

// Über Uns
$rows[] = [
    'acf_fc_layout' => 'BlockImageText',
    'image'         => $ABOUT,
    'contentHtml'   => '<h2 class="font-h2">ÜBER UNS</h2>'
        . '<p>LOOPTOPIA macht Berlins zirkuläres Potenzial für alle sichtbar und erlebbar. Von 13. bis 15. November 2026 zeigen wir das an Orten, die ohnehin Teil des Berliner Alltags sind: Werkstätten, Cafés, Clubs, Märkte, Spätis, Nachbarschaftshäuser, Unternehmen. Überall in der Stadt entstehen Formate, die Kreislaufwirtschaft greifbar machen. Reparieren, tauschen, herstellen, diskutieren – mit Menschen, die das schon tun, und allen, die das noch entdecken wollen.</p>'
        . '<p><strong>Das Ziel:</strong> Kreislaufwirtschaft raus aus der Bubble und rein in den Alltag. Mit Relevanz, weil zirkuläre Lösungen konkreten Mehrwert bieten. Und mit Resonanz, weil Kultur und Erlebnis niedrigschwelligen Zugang zu neuen Themen schaffen.</p>'
        . '<p>LOOPTOPIA ist ein Projekt von Circular City – Zirkuläre Stadt g.e.V. und wird ermöglicht durch eine Förderung der Stiftung Naturschutz Berlin. Jetzt fehlst nur noch du!</p>',
    'buttonLink1'   => lt_link('Programmidee einreichen', '#teilnehmen'),
];

// Teilnehmen intro
$rows[] = [
    'acf_fc_layout' => 'blockWysiwyg',
    'contentHtml'   => '<h2 class="font-h2">TEILNEHMEN</h2>'
        . '<p>LOOPTOPIA entsteht nicht an einem Ort, sondern in der ganzen Stadt – und nur mit dir! Egal ob Initiative, Unternehmen, Institution, Kulturort, Werkstätte, Bar, Späti oder Bildungseinrichtung: Du möchtest unser dezentrales Stadtfestival mitgestalten?</p>',
];

// Themenfeld cards
$rows[] = [
    'acf_fc_layout' => 'BlockCards',
    'title'         => 'Deine Programmidee sollte in eines der drei Themenfelder passen:',
    'showNumbers'   => 1,
    'cards'         => [
        [
            'image' => $CARD1,
            'title' => 'Kreislaufwirtschaft, die erlebbar ist',
            'text'  => '<p>Du bist Alltagsort, Begegnungsraum oder lokale Initiative und lädst Menschen zum Machen und Mitgestalten ein? Formatbeispiele: Workshops, Mitmach-Aktionen, Open House, Behind-the-Scenes, Kunst &amp; Kultur, Walking Tours, …</p>',
        ],
        [
            'image' => $CARD2,
            'title' => 'Kreislaufwirtschaft, die sich lohnt',
            'text'  => '<p>Du hast Erfolgsgeschichten und Beispiele, die zeigen, dass die Circular Economy auch wirtschaftlich Sinn macht oder Ideen, wie wir dorthin kommen? Formatbeispiele: Showcases, Open House, Projektpräsentationen.</p>',
        ],
        [
            'image' => $CARD3,
            'title' => 'Kreislaufwirtschaft, die Zukunft gestaltet',
            'text'  => '<p>Du hast Impulse oder Projekte, die politisch etwas verändern oder Politik, Unternehmen und Menschen in den Dialog bringen? Formatbeispiele: Vorträge, Panels, Roundtables, Dialogformate, Projektpräsentationen, Community &amp; Networking Event, …</p>',
        ],
    ],
];

// Was auf dich wartet / So wirst du Teil
$rows[] = [
    'acf_fc_layout' => 'BlockWysiwygColumns',
    'columns'       => [
        [
            'contentHtml' => '<h3 class="font-h3">Was auf dich wartet</h3>'
                . '<ul>'
                . '<li>Teil des Programms und der Kampagne mit Ankündigung und Logoplatzierung (Website, Presse, Multiplikatoren)</li>'
                . '<li>Feature auf Social Media</li>'
                . '<li>Kommunikations-Kit mit Text- und Bildmaterial</li>'
                . '<li>Zugang zu einem stadtweiten Netzwerk aus Initiativen, Unternehmen und politischen Akteur:innen</li>'
                . '</ul>',
        ],
        [
            'contentHtml' => '<h3 class="font-h3">So wirst du Teil von LOOPTOPIA</h3>'
                . '<p>Reiche deine Programmidee bis 1.9. über das Formular unten ein. Wir kuratieren das Programm Schritt-für-Schritt und melden uns innerhalb von 2-3 Wochen bei dir. Am 1.10. geht das Programm online – und unsere Kampagne live.</p>'
                . '<p><a href="#kriterien">Teilnahmekriterien</a></p>',
            'buttonLink'  => lt_link('Programmidee einreichen', '#teilnehmen'),
        ],
    ],
];

// SPECIAL (microgrant)
$rows[] = [
    'acf_fc_layout' => 'BlockBannerCta',
    'title'         => 'SPECIAL (First come, first serve)',
    'contentHtml'   => '<p>Du hast ein aktives Mitmachformat geplant? Dann hast du eine Chance auf 100,- € Mikroförderung.</p>',
    'buttonLink'    => lt_link('Mehr erfahren', '#'),
    'options'       => ['colorBackground' => '#c7f59a'],
];

// CALL FOR SPACES
$rows[] = [
    'acf_fc_layout' => 'BlockBannerCta',
    'title'         => 'CALL FOR SPACES',
    'contentHtml'   => '<p>Du hast einen Ort, den du zur Verfügung stellen kannst, aber keine Idee? Dann schreib uns und wir vernetzen dich mit passenden Akteuren: <a href="mailto:looptopia@circular.berlin">looptopia@circular.berlin</a></p>',
];

// Partner & Förderer heading
$rows[] = [
    'acf_fc_layout' => 'blockWysiwyg',
    'contentHtml'   => '<h2 class="font-h2">Partner &amp; Förderer</h2>',
];

// Partner logos (tiers). Only "Förderer" has provided logos; other tiers are
// placeholders until logos are supplied.
$rows[] = [
    'acf_fc_layout' => 'BlockPartnerLogos',
    'logoRows'      => [
        ['blockTitle' => 'Förderer',     'columns' => '5', 'logos' => [['logo' => $FUND_NATURSCHUTZ], ['logo' => $FUND_CIRCULAR]]],
        ['blockTitle' => 'Vorreiter',    'columns' => '5', 'logos' => []],
        ['blockTitle' => 'Gestalter',    'columns' => '6', 'logos' => []],
        ['blockTitle' => 'Unterstützer', 'columns' => '5', 'logos' => []],
    ],
    'contentHtml'   => '<p>Du willst LOOPTOPIA aktiv mitgestalten und deine Organisation als Teil der Berliner Kreislaufwirtschaft positionieren? Als Partner:in erreichst du ein breites Publikum, wirst Teil einer wachsenden Community und setzt ein sichtbares Zeichen für eine zukunftsfähige Stadt.</p>',
    'buttonLink'    => lt_link('LOOPTOPIA unterstützen', '#'),
    'badge'         => $BADGE,
    'badgeCaption'  => 'LOOPTOPIA ist Teil der Zero-Waste-Aktionswochen 2026.',
];

// FAQs
$faq = [
    'Wer kann an Looptopia teilnehmen?',
    'Muss mein Angebot etwas mit Kreislaufwirtschaft zu tun haben?',
    'Welche Formate sind möglich?',
    'Was kostet die Teilnahme?',
    'Gibt es eine Förderung?',
    'Was muss ich als Programmpartner:in leisten?',
    'Bis wann kann ich mein Programm einreichen?',
    'Was passiert nach meiner Anmeldung?',
    'Ich habe noch keine konkrete Idee – kann ich mich trotzdem melden?',
];
$panels = [];
foreach ($faq as $q) {
    $panels[] = ['panelTitle' => $q, 'panelContent' => '<p>Antwort folgt.</p>'];
}
$rows[] = [
    'acf_fc_layout'   => 'BlockAccordionDefault',
    'blockTitleHtml'  => '<h2 class="font-h2">FAQs</h2>',
    'accordionPanels' => $panels,
];

// Kontakt
$rows[] = [
    'acf_fc_layout' => 'blockWysiwyg',
    'contentHtml'   => '<h2 class="font-h2">Kontakt</h2>'
        . '<p>Hast du eine Frage zu LOOPTOPIA oder eine Idee für ein Programm, über die du dich mit uns austauschen möchtest? Dann schreib uns gerne an <a href="mailto:looptopia@circular.berlin">looptopia@circular.berlin</a>. Für kurze Updates und aktuelle Infos folge uns auf Instagram und abonniere unseren Newsletter.</p>',
];

// --- 3. Write -----------------------------------------------------------------
update_field('pageComponents', $rows, $pageId);

WP_CLI::success(count($rows) . ' components written to page #' . $pageId . ' (' . get_permalink($pageId) . ')');
