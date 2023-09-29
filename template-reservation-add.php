<?php /* Template Name: Predložak za rezervacije add */ ?>

<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
    global $post;
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
                
                <?php include CHILD_TEMPLATE_ROOT . 'pages/reservation/create.php'; ?>

            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>