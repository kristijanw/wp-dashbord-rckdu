<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
    $proSidebar = new Prospekt_Sidebar();
?>

<div class="row align-items-start" id="oriLogoArea">
    <div class="col text-center mt-5 mb-5 ps-5 pe-5">
        <?php
            $pro_custom_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'full' );
        ?>
        <img src="<?php echo $pro_custom_logo[0] ?>" class="img-fluid" alt="Rezervacije Logo">
    </div>
</div>

<div class="row align-items-start">
    <div class="col mt-5 ps-5">
        <div class="d-flex align-items-center justify-content-between w-75 mr-auto">
            <img src="/wp-content/uploads/2023/09/user-icon.svg" alt="">
            <h2><?php echo wp_get_current_user()->first_name; ?></h2>
            <img src="/wp-content/uploads/2023/09/settings.svg" alt="">
        </div>
    </div>
</div>

<div class="row align-items-start">
    <div class="col left-sidebar">
        <?php
			$proSidebar->create_sidebar_menu();
        ?>
    </div>
</div>