<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('templates/404.twig', $context);

// Redirect to homepage instead of showing 404
// wp_redirect(home_url());
// exit;
