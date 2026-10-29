<?php

namespace Flynt\Utils;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtensionLocaleFormat extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('locale_format', [$this, 'formatLocale']),
        ];
    }

    /**
     * Converts locale code from underscore format (en_US) to hyphen format (en-US)
     * to comply with BCP 47 language tag standards.
     *
     * @param string $locale The locale string (e.g., 'en_US', 'de_DE')
     * @return string The formatted locale string (e.g., 'en-US', 'de-DE')
     */
    public function formatLocale($locale)
    {
        if (empty($locale)) {
            return $locale;
        }
        
        return str_replace('_', '-', $locale);
    }
}

