<?php

/**
 * Matomo tracking (analytics.circular.berlin, site ID 5).
 * Moved here from the Code Snippets plugin.
 * Skipped on local DDEV and non-production environments.
 */

namespace Flynt\Matomo;

add_action('wp_head', function () {
    if (getenv('IS_DDEV_PROJECT') || wp_get_environment_type() !== 'production') {
        return;
    }
    ?>
    <!-- Matomo -->
    <script>
      var _paq = window._paq = window._paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//analytics.circular.berlin/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '5']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <!-- End Matomo Code -->
    <?php
});
