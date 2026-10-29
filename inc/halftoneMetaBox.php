<?php

// ── Register meta box on all public post types that support thumbnails ────────

add_action('add_meta_boxes', function () {
    add_meta_box(
        'halftone_featured_image',
        'Halftone Featured Image Generator',
        'halftone_meta_box_render',
        'post',
        'normal',
        'high'
    );
});

// ── Enqueue p5.js from CDN only on post edit screens ─────────────────────────

add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }
    wp_enqueue_script(
        'p5js',
        'https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.4/p5.min.js',
        [],
        '1.9.4',
        true
    );
});

// ── Custom REST endpoint: save SVG string as post meta ────────────────────────

add_action('rest_api_init', function () {
    register_rest_route('halftone/v1', '/save/(?P<post_id>\d+)', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'halftone_save_svg_handler',
        'permission_callback' => function (WP_REST_Request $request) {
            return current_user_can('edit_post', (int) $request['post_id']);
        },
        'args' => [
            'post_id' => [
                'validate_callback' => fn($v) => is_numeric($v) && (int) $v > 0,
                'sanitize_callback' => 'absint',
            ],
            'svg' => [
                'required'          => true,
                'validate_callback' => fn($v) => is_string($v) && strlen($v) > 0,
            ],
        ],
    ]);
});

function halftone_save_svg_handler(WP_REST_Request $request): WP_REST_Response
{
    $post_id = (int) $request['post_id'];
    $svg     = $request->get_param('svg');

    // Ensure it's actually SVG markup
    if (stripos(trim($svg), '<svg') !== 0) {
        return new WP_REST_Response(['error' => 'Invalid SVG content'], 400);
    }

    // Strip any script blocks and event-handler attributes before storage
    $svg = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $svg);
    $svg = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $svg);

    update_post_meta($post_id, 'halftone_svg', $svg);

    return new WP_REST_Response(['success' => true, 'post_id' => $post_id], 200);
}

// ── Meta box render ───────────────────────────────────────────────────────────

function halftone_meta_box_render($post): void
{
    $nonce     = wp_create_nonce('wp_rest');
    $post_id   = $post->ID;
    $rest_url  = rest_url();
    $pt_obj    = get_post_type_object($post->post_type);
    $rest_base = (!empty($pt_obj->rest_base)) ? $pt_obj->rest_base : 'posts';

    $bg_colors = ['#FB8666', '#FFC3A3', '#BEE1FF', '#7ABDEA'];
    ?>

    <style>
        #halftone-tool {
            font-family: system-ui, -apple-system, sans-serif;
            margin: -6px -12px -12px;
        }
        #halftone-canvas-container canvas {
            display: block;
            max-width: 100%;
        }
        #halftone-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 20px;
            background: #1a1a1a;
            padding: 10px 14px;
        }
        .ht-group { display: flex; flex-direction: column; gap: 5px; }
        .ht-group label { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .04em; }
        .ht-group input[type=range] { accent-color: #FB8666; cursor: pointer; width: 130px; }
        .ht-group input[type=file]  { font-size: 12px; color: #ccc; cursor: pointer; }
        .ht-swatches { display: flex; gap: 8px; }
        .ht-swatch {
            width: 28px; height: 28px; border-radius: 6px; cursor: pointer;
            box-sizing: border-box; transition: border .1s;
        }
        #ht-set-featured   { white-space: nowrap; }
        #halftone-status   { font-size: 12px; margin-left: 8px; }

    </style>

    <div id="halftone-tool">

        <div id="halftone-canvas-container"></div>

        <div id="halftone-controls">

            <div class="ht-group">
                <label>Upload Image</label>
                <input type="file" id="ht-file-input" accept="image/*">
            </div>

            <div class="ht-group">
                <label>Detail: <span id="ht-detail-val" style="color:#FB8666;">10</span></label>
                <input type="range" id="ht-spacing" min="4" max="30" value="10" step="1">
            </div>

            <div class="ht-group">
                <label>Zoom: <span id="ht-zoom-val" style="color:#FB8666;">100</span>%</label>
                <input type="range" id="ht-zoom" min="10" max="300" value="100" step="1">
            </div>

            <div class="ht-group">
                <label>Background</label>
                <div class="ht-swatches">
                    <?php foreach ($bg_colors as $i => $hex) : ?>
                        <div
                            class="ht-swatch"
                            id="ht-swatch-<?php echo $i; ?>"
                            style="background:<?php echo esc_attr($hex); ?>;border:<?php echo $i === 0 ? '3px solid #fff' : '2px solid transparent'; ?>;"
                            data-color="<?php echo esc_attr($hex); ?>"
                        ></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="ht-group" style="flex-direction:row;align-items:center;margin-left:auto;">
                <button id="ht-set-featured" class="button button-primary" disabled>
                    Save as Featured SVG
                </button>
                <span id="halftone-status"></span>
            </div>

        </div>

    </div>

    <script>
    (function () {
        var HT_POST_ID   = <?php echo (int) $post_id; ?>;
        var HT_NONCE     = <?php echo wp_json_encode($nonce); ?>;
        var HT_REST_URL  = <?php echo wp_json_encode($rest_url); ?>;
        var HT_REST_BASE = <?php echo wp_json_encode($rest_base); ?>;

        function init() {
            if (typeof p5 === 'undefined') { setTimeout(init, 80); return; }

            new p5(function (p) {

                // ── Constants ──────────────────────────────────────────────
                var INTENSITY        = 1.2;
                var INACTIVE_SIZE    = 1.0;
                var ACTIVE_SIZE      = 0.6;
                var TRANSITION_SPEED = 0.03;
                var DOT_COLOR        = '#000000';
                var CANVAS_W         = 800;
                var CANVAS_H         = 500;
                var EX               = 1920; // longest side of JPEG export

                // ── State ──────────────────────────────────────────────────
                var img, fitScale = 1, currentSizeMul = 1;
                var selectedBgColor = '#FB8666';
                var spacingInput, zoomInput;

                // ── Setup ──────────────────────────────────────────────────
                p.setup = function () {
                    var canvas = p.createCanvas(CANVAS_W, CANVAS_H);
                    canvas.parent('halftone-canvas-container');

                    spacingInput = document.getElementById('ht-spacing');
                    zoomInput    = document.getElementById('ht-zoom');

                    // File upload
                    document.getElementById('ht-file-input').addEventListener('change', function (e) {
                        var file = e.target.files[0];
                        if (!file || !file.type.startsWith('image/')) return;
                        var url = URL.createObjectURL(file);
                        p.loadImage(url, function (loaded) {
                            img      = loaded;
                            fitScale = p.min(CANVAS_W / img.width, CANVAS_H / img.height);
                            zoomInput.value = 100;
                            document.getElementById('ht-zoom-val').textContent = '100';
                            document.getElementById('ht-set-featured').disabled = false;
                        });
                    });

                    // Slider labels
                    spacingInput.addEventListener('input', function () {
                        document.getElementById('ht-detail-val').textContent = spacingInput.value;
                    });
                    zoomInput.addEventListener('input', function () {
                        document.getElementById('ht-zoom-val').textContent = zoomInput.value;
                    });

                    // Color swatches
                    document.querySelectorAll('.ht-swatch').forEach(function (sw, i) {
                        sw.addEventListener('click', function () {
                            selectedBgColor = sw.dataset.color;
                            document.querySelectorAll('.ht-swatch').forEach(function (s, j) {
                                s.style.border = j === i ? '3px solid #fff' : '2px solid transparent';
                            });
                        });
                    });

                    document.getElementById('ht-set-featured').addEventListener('click', saveAsSvg);
                };

                // ── Draw loop ──────────────────────────────────────────────
                p.draw = function () {
                    p.background(p.color(selectedBgColor));

                    if (!img) {
                        p.noStroke();
                        p.fill(0, 0, 0, 70);
                        p.textAlign(p.CENTER, p.CENTER);
                        p.textSize(20);
                        p.text('Upload an image to begin', CANVAS_W / 2, CANVAS_H / 2);
                        return;
                    }

                    var spacing = parseInt(spacingInput.value);
                    var zoom    = fitScale * (parseInt(zoomInput.value) / 100);
                    var hover   = p.mouseX >= 0 && p.mouseX <= CANVAS_W &&
                                  p.mouseY >= 0 && p.mouseY <= CANVAS_H;

                    currentSizeMul = p.lerp(
                        currentSizeMul,
                        hover ? ACTIVE_SIZE : INACTIVE_SIZE,
                        TRANSITION_SPEED
                    );

                    p.noStroke();
                    p.fill(DOT_COLOR);

                    p.drawingContext.save();
                    p.drawingContext.beginPath();
                    p.drawingContext.rect(0, 0, CANVAS_W, CANVAS_H);
                    p.drawingContext.clip();
                    renderHalftone(p, CANVAS_W, CANVAS_H, spacing, zoom, currentSizeMul);
                    p.drawingContext.restore();
                };

                // ── Shared render — ctx is p (main canvas) or p5.Graphics ──
                function renderHalftone(ctx, cW, cH, spacing, zoom, sizeMul) {
                    if (!img) return;
                    var ox = (cW - img.width  * zoom) / 2;
                    var oy = (cH - img.height * zoom) / 2;
                    img.loadPixels();
                    for (var cy = 0; cy < cH; cy += spacing) {
                        for (var cx = 0; cx < cW; cx += spacing) {
                            var ix = p.floor((cx - ox) / zoom);
                            var iy = p.floor((cy - oy) / zoom);
                            if (ix < 0 || ix >= img.width || iy < 0 || iy >= img.height) continue;
                            var pi = (ix + iy * img.width) * 4;
                            var sz = p.map(
                                (img.pixels[pi] + img.pixels[pi + 1] + img.pixels[pi + 2]) / 3,
                                255, 0, 0, spacing * INTENSITY
                            ) * sizeMul;
                            if (sz <= 0) continue;
                            ctx.circle(cx, cy, sz);
                        }
                    }
                }

                // ── Build SVG string from current settings ─────────────────
                // Uses canvas dimensions + viewBox → scales to any size on the front end.
                function buildSvg() {
                    if (!img) return null;

                    var spacing = parseInt(spacingInput.value);
                    var zoom    = fitScale * (parseInt(zoomInput.value) / 100);
                    var cW      = CANVAS_W;
                    var cH      = CANVAS_H;
                    var ox      = (cW - img.width  * zoom) / 2;
                    var oy      = (cH - img.height * zoom) / 2;

                    img.loadPixels();

                    var circles = [];
                    for (var cy = 0; cy < cH; cy += spacing) {
                        for (var cx = 0; cx < cW; cx += spacing) {
                            var ix = p.floor((cx - ox) / zoom);
                            var iy = p.floor((cy - oy) / zoom);
                            if (ix < 0 || ix >= img.width || iy < 0 || iy >= img.height) continue;
                            var pi = (ix + iy * img.width) * 4;
                            var sz = p.map(
                                (img.pixels[pi] + img.pixels[pi + 1] + img.pixels[pi + 2]) / 3,
                                255, 0, 0, spacing * INTENSITY
                            ) * INACTIVE_SIZE;
                            if (sz <= 0) continue;
                            circles.push(
                                '<circle cx="' + cx.toFixed(1) + '" cy="' + cy.toFixed(1) +
                                '" r="' + (sz / 2).toFixed(1) + '"/>'
                            );
                        }
                    }

                    return '<svg viewBox="0 0 ' + cW + ' ' + cH +
                           '" preserveAspectRatio="xMidYMid slice"' +
                           ' xmlns="http://www.w3.org/2000/svg">' +
                           '<rect width="' + cW + '" height="' + cH + '" fill="' + selectedBgColor + '"/>' +
                           '<g fill="' + DOT_COLOR + '">' + circles.join('') + '</g>' +
                           '</svg>';
                }

                // ── Save: SVG to post meta + JPEG to media library ────────
                function saveAsSvg() {
                    if (!img) return;

                    var btn    = document.getElementById('ht-set-featured');
                    var status = document.getElementById('halftone-status');

                    btn.disabled       = true;
                    status.style.color = '#aaa';

                    // ── Step 1: save SVG to post meta ──────────────────────
                    status.textContent = 'Building SVG…';
                    var svgString = buildSvg();

                    status.textContent = 'Saving SVG…';

                    fetch(HT_REST_URL + 'halftone/v1/save/' + HT_POST_ID, {
                        method:  'POST',
                        headers: {
                            'X-WP-Nonce':   HT_NONCE,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ svg: svgString })
                    })
                    .then(function (r) {
                        if (!r.ok) throw new Error('SVG save failed (' + r.status + ')');
                        return r.json();
                    })

                    // ── Step 2: render high-res JPEG for admin thumbnail ────
                    .then(function () {
                        status.textContent = 'Rendering JPEG for admin…';
                        return new Promise(function (resolve, reject) {
                            var aspect = img.width / img.height;
                            var exW    = aspect >= 1 ? EX : Math.round(EX * aspect);
                            var exH    = aspect >= 1 ? Math.round(EX / aspect) : EX;
                            var up     = exW / CANVAS_W;
                            var sp     = parseInt(spacingInput.value) * up;
                            var zm     = fitScale * (parseInt(zoomInput.value) / 100) * up;

                            var g = p.createGraphics(exW, exH);
                            g.background(p.color(selectedBgColor));
                            g.noStroke();
                            g.fill(DOT_COLOR);
                            renderHalftone(g, exW, exH, sp, zm, INACTIVE_SIZE);

                            g.elt.toBlob(function (blob) {
                                g.remove();
                                resolve(blob);
                            }, 'image/jpeg', 0.92);
                        });
                    })

                    // ── Step 3: upload JPEG to media library ───────────────
                    .then(function (blob) {
                        status.textContent = 'Uploading JPEG…';
                        var formData = new FormData();
                        formData.append('file', blob, 'halftone-featured.jpg');
                        formData.append('title', 'Halftone Featured Image');

                        return fetch(HT_REST_URL + 'wp/v2/media', {
                            method:  'POST',
                            headers: { 'X-WP-Nonce': HT_NONCE },
                            body:    formData
                        })
                        .then(function (r) {
                            if (!r.ok) throw new Error('JPEG upload failed (' + r.status + ')');
                            return r.json();
                        });
                    })

                    // ── Step 4: set JPEG as featured_media on the post ─────
                    .then(function (mediaData) {
                        status.textContent = 'Setting admin thumbnail…';
                        return fetch(HT_REST_URL + 'wp/v2/' + HT_REST_BASE + '/' + HT_POST_ID, {
                            method:  'POST',
                            headers: {
                                'X-WP-Nonce':   HT_NONCE,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ featured_media: mediaData.id })
                        })
                        .then(function (r) {
                            if (!r.ok) throw new Error('Could not set featured image (' + r.status + ')');
                            return mediaData;
                        });
                    })

                    .then(function (mediaData) {
                        status.style.color = '#46b450';
                        status.textContent = '✓ Done!';
                        updateAdminThumbnail(mediaData);
                    })
                    .catch(function (err) {
                        status.style.color = '#dc3232';
                        status.textContent = '✗ ' + err.message;
                    })
                    .finally(function () {
                        btn.disabled = false;
                    });
                }

                // ── Update WP admin featured-image sidebar ─────────────────
                function updateAdminThumbnail(mediaData) {
                    // Block Editor
                    if (window.wp && window.wp.data && window.wp.data.dispatch) {
                        try {
                            wp.data.dispatch('core/editor').editPost({ featured_media: mediaData.id });
                        } catch (e) {}
                    }

                    // Classic Editor
                    var hiddenInput = document.getElementById('_thumbnail_id');
                    if (hiddenInput) hiddenInput.value = mediaData.id;

                    var thumbUrl = (
                        mediaData.media_details &&
                        mediaData.media_details.sizes &&
                        mediaData.media_details.sizes.thumbnail
                    )
                        ? mediaData.media_details.sizes.thumbnail.source_url
                        : mediaData.source_url;

                    var container = document.getElementById('set-post-thumbnail');
                    if (container) {
                        container.innerHTML = '<img src="' + thumbUrl + '" style="max-width:100%;" alt="">';
                    }

                    var removeLink = document.getElementById('remove-post-thumbnail');
                    if (removeLink) removeLink.style.display = '';
                }

            }, 'halftone-canvas-container');
        }

        init();
    }());
    </script>

    <?php
}
