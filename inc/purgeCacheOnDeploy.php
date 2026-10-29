<?php

/**
 * Purge LiteSpeed Cache whenever a new theme build is deployed.
 *
 * Vite emits hashed asset filenames, so cached HTML pointing at the previous
 * build's CSS/JS breaks as soon as the new dist/ lands on the server.
 *
 * GitHub Actions purges via wp-cli after each deploy; as a fallback this
 * purges on the first uncached request (admin, cron, logged-in) after
 * dist/manifest.json changes.
 */

namespace Flynt\PurgeCacheOnDeploy;

const OPTION_NAME = 'flynt_deployed_manifest_hash';

function purgeAll()
{
    // LiteSpeed Cache public API; no-op if the plugin is inactive.
    do_action('litespeed_purge_all');
}

add_action('init', function () {
    $manifestPath = get_template_directory() . '/dist/manifest.json';
    if (!is_file($manifestPath)) {
        return;
    }

    $hash = md5_file($manifestPath);
    if (get_option(OPTION_NAME) === $hash) {
        return;
    }

    update_option(OPTION_NAME, $hash, true);
    purgeAll();
});
