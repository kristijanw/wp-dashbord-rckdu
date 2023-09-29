<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }


/*
============================================================================
Insert custom actions and filters for child theme
============================================================================
*/
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles', 12 );
function child_theme_enqueue_styles() {

    // CSS
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    wp_enqueue_style( 'bs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' );
    wp_enqueue_style( 'fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css' );
    wp_enqueue_style( 'jui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css' );
    wp_enqueue_style( 'jui-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/theme.min.css' );
    wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
    wp_enqueue_style( 'slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css' );

    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'parent-style' ),
        wp_get_theme()->get('Version')
    );

    // JS
    wp_enqueue_script( 'bs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', '', '', true);
    wp_enqueue_script( 'jui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js', '', '', true);
    wp_enqueue_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', '', '', true);

    wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/assets/custom.js', array('jquery'), time(), true);
	wp_localize_script(
        'prospekt',
        'prospektAjax',
        array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'ajaxnonce' => wp_create_nonce( 'ajaxnonce' ),
        )
    );
    wp_enqueue_script( 'prospekt' );

}

?>