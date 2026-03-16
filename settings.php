<?php

/**
 * Custom settings for post types, TinyMCE styles, and image sizes
 */

use Flexx_Client_Plugin\Settings;
use Flexx_Client_Plugin\Settings\Image_Settings;

/**
 * Function: Registering custom post types
 *
 * @since 1.0.0
 */
    function setup_post_types() {
        $custom_post_types = array(
            'global_blocks' => array(
                'name'                => "Vaste blokken",
                'menu_name'           => "Vaste blokken",
                'singular'            => "Blok",
                'post_type_key'       => 'global_blocks',
                'public'              => true,
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'rewrite'             => false,
                'archive'             => false,
                'icon'                => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M352-120H200q-33 0-56.5-23.5T120-200v-152q48 0 84-30.5t36-77.5q0-47-36-77.5T120-568v-152q0-33 23.5-56.5T200-800h160q0-42 29-71t71-29q42 0 71 29t29 71h160q33 0 56.5 23.5T800-720v160q42 0 71 29t29 71q0 42-29 71t-71 29v160q0 33-23.5 56.5T720-120H568q0-50-31.5-85T460-240q-45 0-76.5 35T352-120Z"/></svg>',
                'has_vc'              => true,
            ),
            'terrace-solution'         => array(
                'name'                => "Terrasoverkappingen",
                'menu_name'           => "Overkappingen",
                'singular'            => "Terrasoverkapping",
                'post_type_key'       => 'terrace-solution',
                'public'              => true,
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'hierarchical'        => true,
                'rewrite'             => 'terrasoverkappingen',
                'archive'             => true,
                'icon'                => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M440-120v-480H143q-15 0-19-14t8-22l325-228q11-8 23-8t23 8l325 228q12 8 8 22t-19 14H520v480q0 17-11.5 28.5T480-80q-17 0-28.5-11.5T440-120Zm-320 0v-170L95-427q-3-17 6-30t26-16q16-3 29.5 6.5T173-441l23 121h124q17 0 28.5 11.5T360-280v160q0 17-11.5 28.5T320-80q-17 0-28.5-11.5T280-120v-120h-80v120q0 17-11.5 28.5T160-80q-17 0-28.5-11.5T120-120Zm480 0v-160q0-17 11.5-28.5T640-320h124l23-121q3-16 16-25.5t30-6.5q17 3 26 16t6 30l-25 137v170q0 17-11.5 28.5T800-80q-17 0-28.5-11.5T760-120v-120h-80v120q0 17-11.5 28.5T640-80q-17 0-28.5-11.5T600-120Z"/></svg>',
                'supports'            => array('title', 'editor', 'revisions', 'thumbnail', 'page-attributes'),
                'has_vc'              => true,
            ),
            'realizations'         => array(
                'name'                => "Realisaties",
                'menu_name'           => "Realisaties",
                'singular'            => "Realisatie",
                'post_type_key'       => 'realizations',
                'public'              => true,
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'rewrite'             => 'realisaties',
                'archive'             => true,
                'icon'                => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="m352-522 86-87-56-57-16 16q-11 11-27.5 11.5T310-650q-12-12-12-28.5t12-28.5l15-15-45-45-87 87 159 158Zm328 329 87-87-45-45-16 15q-12 12-28 12t-28-12q-12-12-12-28t12-28l15-16-57-56-86 86 158 159Zm-31-510 56 56 56-56-57-57-55 57ZM160-120q-17 0-28.5-11.5T120-160v-113q0-8 3-15.5t9-13.5l163-163-173-173q-17-17-17-42t17-42l116-116q17-17 42-16.5t42 17.5l174 173 151-152q12-12 27-18t31-6q16 0 31 6t27 18l53 54q12 12 18 27t6 31q0 16-6 30.5T816-647L665-495l173 173q17 17 17 42t-17 42L722-122q-17 17-42 17t-42-17L465-295 302-132q-6 6-13.5 9t-15.5 3H160Z"/></svg>',
                'supports'            => array('title', 'editor', 'revisions', 'thumbnail', 'page-attributes'),
                'has_vc'              => true,
            ),
            'faq'         => array(
                'name'                => "Veel gestelde vragen",
                'menu_name'           => "FAQ",
                'singular'            => "Vraag",
                'post_type_key'       => 'faq',
                'public'              => true,
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => false,
                'exclude_from_search' => false,
                'publicly_queryable'  => false,
                'rewrite'             => 'veel-gestelde-vragen',
                'archive'             => false,
                'icon'                => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M584-637q0-43-28.5-69T480-732q-29 0-52.5 12.5T387-683q-16 23-43.5 26.5T296-671q-14-13-15.5-32t9.5-36q32-48 81.5-74.5T480-840q97 0 157.5 55T698-641q0 45-19 81t-70 85q-37 35-50 54.5T542-376q-4 24-20.5 40T482-320q-23 0-39.5-15.5T426-374q0-39 17-71.5t57-68.5q51-45 67.5-69.5T584-637ZM480-80q-33 0-56.5-23.5T400-160q0-33 23.5-56.5T480-240q33 0 56.5 23.5T560-160q0 33-23.5 56.5T480-80Z"/></svg>',
                'supports'            => array('title', 'editor', 'page-attributes'),
                'has_vc'              => true,
            ),
        );

        if (!empty($custom_post_types)) {
            new Settings\Post_Types($custom_post_types);

            // Get current post types
            $current_post_types = get_option('flexx-post-types');

            // Store post type options
            $post_type_options = array_map(fn($v) => $v['has_vc'], $custom_post_types);

            // Update option if there are new post types
            if ($post_type_options !== $current_post_types) {
                update_option('flexx-post-types', $post_type_options);
            }
        }
    }

    // Run the function to set up post types
    setup_post_types();

/**
 * Function: Registering custom taxonomies
 *
 * @since 1.0.0
 */
    function setup_taxonomies() {
        $custom_taxonomies = array(
            'solution_tags' => array(
                'name'              => 'Tags',
                'menu_name'         => 'Tags',
                'taxonomy_key'      => 'solution_tags',
                'singular'          => 'Tag',
                'hierarchical'      => true,
                'public'            => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => false,
                'rewrite'           => false,
                'post_types'        => array( 'terrace-solution' ),
            ),
            'solutions' => array(
                'name'              => 'Oplossingen',
                'menu_name'         => 'Oplossingen',
                'taxonomy_key'      => 'solutions',
                'singular'          => 'Oplossing',
                'hierarchical'      => true,
                'public'            => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => false,
                'rewrite'           => false,
                'post_types'        => array( 'realizations' ),
            ),
        );

        if (!empty($custom_taxonomies)) {
            new Settings\Taxonomies($custom_taxonomies);
        }
    }

    // Run the function to set up custom taxonomies
    setup_taxonomies();


/**
 * Function: setting up custom TinyMCE editor styles
 *
 * @since 1.0.0
 */
    function setup_tinymce_styles() {

        $style_formats = array(

            // List styles
//            array(
//                'title' => 'Opsommingslijst stijlen',
//                'items' => array(
//                    array(
//                        'title'    => 'Lijst met checkmarks',
//                        'selector' => 'ul, ol',
//                        'classes'  => 'flexx-usp-list'
//                    ),
//                )
//            ),

            // Text styles
//            array(
//                'title' => 'Tekst stijlen',
//                'items' => array(
//                    array(
//                        'title'    => 'Blauwe tekst',
//                        'inline'   => 'span',
//                        'classes'  => 'color-text-blue'
//                    ),
//                )
//            ),

            // Paragraph styles
            array(
                'title' => 'Paragraaf stijlen',
                'items' => array(
                    array(
                        'title'    => 'Intro tekst',
                        'selector' => 'p',
                        'classes'  => 'flexx-intro-text'
                    ),
                )
            ),

            // Heading styles
            array(
                'title' => 'Koptekst stijlen',
                'items' => array(
                    array(
                        'title'    => 'Koptekst 1 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'like-h1'
                    ),
                    array(
                        'title'    => 'Koptekst 2 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'like-h2'
                    ),
                    array(
                        'title'    => 'Koptekst 3 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'like-h3'
                    ),
                    array(
                        'title'    => 'Koptekst 4 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'like-h4'
                    ),
                    array(
                        'title'    => 'Display 1 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'display-1'
                    ),
                    array(
                        'title'    => 'Display 2 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'display-2'
                    ),
                    array(
                        'title'    => 'Display 3 stijl',
                        'selector' => 'p,h1,h2,h3,h4,h5',
                        'classes'  => 'display-3'
                    ),
                )
            ),

        );

        if ( ! empty( $style_formats ) ) {
            new Settings\Editor_Settings( $style_formats );
        }

    }

    // Run the function to setup style formats
    setup_tinymce_styles();


/**
 * Function: setting up custom image sizes
 */
    function setup_custom_image_sizes() {

        $image_sizes = array(

            'huge' => array(
                'width'  => 2560,
                'height' => '',
                'crop'   => true,
            ),

            'ultra-large' => array(
                'width'  => 2000,
                'height' => '',
                'crop'   => true,
            ),

            'extra-large' => array(
                'width'  => 1400,
                'height' => '',
                'crop'   => true,
            ),

            'large' => array(
                'width'  => 1000,
                'height' => '',
                'crop'   => true,
            ),

            'medium' => array(
                'width'  => 700,
                'height' => '',
                'crop'   => true,
            ),

            'small' => array(
                'width'  => 350,
                'height' => '',
                'crop'   => true,
            ),

            'tiny' => array(
                'width'  => 200,
                'height' => '',
                'crop'   => true,
            ),

        );

        if ( ! empty( $image_sizes ) ) {
            new Settings\Image_Settings( $image_sizes );
        }

    }

    // Run the function to setup custom image sizes
    setup_custom_image_sizes();


/**
 * Function: setting up custom params voor VC elements
 */
    function setup_custom_vc_params() {

        $params = array (

            // Custom VC param: row top padding
            array(
                'vc_element'  => 'vc_row',
                'type'        => 'dropdown',
                'heading'     => 'Ruimte (marge) boven rij',
                'description' => 'Kies hier de (extra) marge boven deze rij (optioneel).',
                'param_name'  => 'row_top_padding',
                'weight'      => 20,
                'values'      => array(
                    'Standaard' => '',
                    'Ruim' => 'large',
                    'Extra ruim' => 'huge',
                ),
            ),

            // Custom VC param: row bottom padding
            array(
                'vc_element'  => 'vc_row',
                'type'        => 'dropdown',
                'heading'     => 'Ruimte (marge) onder rij',
                'description' => 'Kies hier de (extra) marge onder deze rij (optioneel).',
                'param_name'  => 'row_bottom_padding',
                'weight'      => 15,
                'values'      => array(
                    'Standaard' => '',
                    'Ruim' => 'large',
                    'Extra ruim' => 'huge',
                ),
            ),

            // Custom VC param: row background
            array (
                'vc_element' => 'vc_row',
                'type' => 'dropdown',
                'heading' => 'Achtergrond',
                'param_name' => 'row_bg',
                'weight' => 10,
                'values' => array (
                    'Geen' => '',
                    'Donkergrijs' => 'dark-gray',
                    'Lichter donkergrijs' => 'light-dark-gray',
                ),
                'description' => 'Kies de achtergrondkleur voor deze rij (optioneel).',
            ),

            // Custom VC param: row direction on mobile
            array(
                'vc_element'  => 'vc_row',
                'type'        => 'dropdown',
                'heading'     => 'Volgorde op mobiele apparaten',
                'description' => 'Kies hier of de volgorde van de kolommen binnen deze rij op mobiele apparaten moet worden aangepast (optioneel)',
                'param_name'  => 'row_mobile_direction',
                'weight'      => 5,
                'values'      => array(
                    'Standaard' => '',
                    'Omgekeerd' => 'reverse',
                ),
            ),

            // Custom VC param: custom image sizes for standard VC single image
            array(
                'vc_element'  => 'vc_single_image',
                'type'        => 'dropdown',
                'heading'     => 'Afbeeldingsgrootte',
                'description' => 'Kies de grootte van de afbeelding',
                'param_name'  => 'img_size',
                'weight'      => 10,
                'values'      => array (
                    'Standaard (1000px)' => 'flexx-large',
                    'Klein (700px)' => 'flexx-medium',
                    'Groot (2000px)' => 'flexx-ultra-large',
                )
            ),

            // Custom VC param: aspect ratio for standard VC single image
            array(
                'vc_element'  => 'vc_single_image',
                'type'        => 'dropdown',
                'heading'     => 'Aspect ratio',
                'description' => 'Kies de verhouding voor de afbeelding',
                'param_name'  => 'aspect_ratio',
                'weight'      => 11,
                'values'      => array(
                    'Origineel'   => 'original',
                    '1:1 (vierkant)' => '1-1',
                    '4:3 (klassiek)' => '4-3',
                    '16:9 (breedbeeld)' => '16-9',
                    '3:2 (fotografie)' => '3-2',
                    '21:9 (cinema)' => '21-9',
                )
            ),

            // Custom VC param: full width for standard VC single image
            array(
                'vc_element'  => 'vc_single_image',
                'type'        => 'checkbox',
                'heading'     => 'Full width',
                'description' => 'Kies of de afbeelding full width moet zijn',
                'param_name'  => 'full_width',
                'weight'      => 10,
                'values'      => array(
                    'Ja, maak de afbeelding full width' => 'yes',
                )
            ),

        );

        new Settings\VC_Settings( $params );

    }

    // Run the function to setup the custom VC params
    setup_custom_vc_params();