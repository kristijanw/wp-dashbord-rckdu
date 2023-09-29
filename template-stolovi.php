<?php /* Template Name: Predložak za stolove */ ?>
<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php 
    if(!is_user_logged_in()) {
        wp_redirect( home_url('/prijava'), 302 );
        exit;
    }

    $content_check_user = wp_get_current_user();
?>

<?php get_header(); ?>

<div class="container-fluid">
    <div class="row">

        <?php if(!wp_is_mobile()): ?>
            <div class="col-md-2 col-xs-12 sidebar-bg">
                <?php require_once CHILD_TEMPLATE_ROOT.'layout/sidebar.php'; ?>
            </div>
        <?php endif; ?>


        <div class="col-md-10 col-xs-12">
            <?php include CHILD_TEMPLATE_ROOT . 'layout/loader.php'; ?>
            <div class="row p-0-50">
                
                <?php include CHILD_TEMPLATE_ROOT . 'pages/stolovi.php'; ?>

            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>

