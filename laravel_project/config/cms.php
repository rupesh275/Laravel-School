<?php

return [
    // Values from ci-blog.php
    'balance_group' => 'Balance Master', // Review purpose and placement
    'balance_type' => 'Previous Session Balance', // Review purpose and placement

    'theme' => 'default', // from ci_blog_theme

    'front_page_url' => 'page/', // from ci_front_page_url
    'front_page_read_url' => 'read/', // from ci_front_page_read_url

    'content_types' => [
        'event'         => 'events',        // from ci_front_event_content
        'notice'        => 'notice',        // from ci_front_notice_content
        'news'          => 'news',          // from ci_front_news_content
        'facility'      => 'facility',      // from ci_front_facility_content
        'co_curriculam' => 'co-curriculam', // from ci_front_co-curriculam_content
        'testimonial'   => 'testimonials',  // from ci_front_testimonials_content
        'sport'         => 'sports',        // from ci_front_sports_content
        'gallery'       => 'gallery',       // from ci_front_gallery_content
        'banner'        => 'banner',        // from ci_front_banner_content
        'achievement'   => 'achievements',  // from ci_front_achievements_content
    ],

    'home_page_slug' => 'home', // from ci_front_home_page_slug

    'themes_list' => [ // from ci_front_themes
        'default'       => 'theme_default.jpg',
        'yellow'        => 'theme_yellow.jpg',
        'darkgray'      => 'theme_darkgray.jpg',
        'bold_blue'     => 'theme_bold_blue.jpg',
        'shadow_white'  => 'theme_shadow_white.jpg',
        'material_pink' => 'theme_material_pink.jpg',
    ],
];
