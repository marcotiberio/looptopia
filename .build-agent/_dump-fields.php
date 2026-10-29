<?php
use Flynt\Components;
$comps = ['BlockWysiwyg','BlockButtons','BlockImageText','BlockWysiwygColumns','BlockBannerCta','BlockAccordionDefault','BlockAnchor'];
foreach ($comps as $c) {
    $fn = "Flynt\\Components\\$c\\getACFLayout";
    if (!function_exists($fn)) { echo "MISSING: $c\n"; continue; }
    $l = $fn();
    echo "\n### $c  (layout: {$l['name']})\n";
    foreach ($l['sub_fields'] as $f) {
        if (($f['type'] ?? '') === 'tab') continue;
        echo "  - {$f['name']} : {$f['type']}\n";
        if (in_array($f['type'] ?? '', ['repeater','group','flexible_content']) && !empty($f['sub_fields'])) {
            foreach ($f['sub_fields'] as $sf) {
                if (($sf['type'] ?? '')==='tab') continue;
                echo "      . {$sf['name']} : {$sf['type']}\n";
            }
        }
    }
}
