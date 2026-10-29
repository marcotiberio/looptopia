<?php

// function generate_custom_post_excerpt($post_id)
// {
//     // Check if it's a regular post (post_type = 'post')
//     if (get_post_type($post_id) === 'event') {
//         // Get ACF field values
//         $acf_field_1 = get_field('dateTimePost', $post_id);
//         $acf_field_2 = get_field('related_speakers', $post_id);

//         // Get custom taxonomies: event_stage, event_topic, and event_format
//         $event_stages = wp_get_post_terms($post_id, 'event_stage');
//         $event_topics = wp_get_post_terms($post_id, 'event_topic');
//         $event_formats = wp_get_post_terms($post_id, 'event_format');

//         // Create the custom excerpt with taxonomies and ACF field
//         $custom_excerpt = '';

//         $custom_excerpt .= '<div class="post--date-time">' . $acf_field_1 . '</div>';

//         if (!empty($event_topics)) {
//             $custom_excerpt .= '<div class="post--format"><div>';
//             foreach ($event_topics as $term) {
//                 $custom_excerpt .= $term->name . ', ';
//             }
//             $custom_excerpt = rtrim($custom_excerpt, ', ');
//             $custom_excerpt .= '</div>';
//         }

//         if (!empty($event_formats)) {
//             $custom_excerpt .= '<div>';
//             foreach ($event_formats as $term) {
//                 $custom_excerpt .= $term->name . ', ';
//             }
//             $custom_excerpt = rtrim($custom_excerpt, ', ');
//             $custom_excerpt .= '</div></div>';
//         }

//         if (!empty($event_stages)) {
//             $custom_excerpt .= '<div class="post--stage">';
//             foreach ($event_stages as $term) {
//                 $custom_excerpt .= $term->name . ', ';
//             }
//             $custom_excerpt = rtrim($custom_excerpt, ', ');
//             $custom_excerpt .= '</div>';
//         }

//         if (!empty($acf_field_2)) {
//             $custom_excerpt .= '<div class="post--speakers">';
//             foreach ($acf_field_2 as $related_speaker) {
//                 // Get the speaker post title
//                 $speaker_post_title = get_the_title($related_speaker->ID);
//                 $custom_excerpt .= '<div class="post--speaker">' . $speaker_post_title . '</div>';
//             }
//             $custom_excerpt = rtrim($custom_excerpt, ', ');
//             $custom_excerpt .= '</div>';
//         }

//         // Update the post excerpt
//         wp_update_post(array(
//             'ID' => $post_id,
//             'post_excerpt' => $custom_excerpt,
//         ));
//     }
// }

// // Hook the function to run when a post is updated or created
// add_action('save_post', 'generate_custom_post_excerpt');
