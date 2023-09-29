<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
    global $post;
    $content_check_user = wp_get_current_user();
?>

<?php get_header(); ?>

<div class="container-fluid">
    <div class="row">

        <?php if(!wp_is_mobile()): ?>
            <div class="col-md-3 col-xs-12 sidebar-bg">
                <?php require_once CHILD_TEMPLATE_ROOT.'layout/sidebar.php'; ?>
            </div>
        <?php endif; ?>


        <div class="col-md-9 col-xs-12">
            <?php include CHILD_TEMPLATE_ROOT . 'layout/loader.php'; ?>
            <div class="row p-0-50">

                <div id="proBodyArea" class="mb-5"></div>

                <div class="col-md-12 col-xs-12">
                
                    <div class="row">
                        <div class="col-md-8 offset-md-2 col-xs-12">
                            <h2 class="rezervacije-h2 rezervacije-c-blue m-bottom-40"><?php echo get_the_title($post->ID); ?></h2>
                        </div>
                        <div class="col-md-8 offset-md-2 col-xs-12 rezervacije-page-bg p-3">

                            <?php
                                while ( have_posts() ) :
                                    the_post();
                                    the_content();
                                endwhile;
                            ?>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>