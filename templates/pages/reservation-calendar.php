<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php
$reservations = new ReservationClass();
?>

<div class="container p-5 reservations">
    <div class="row">
        <div class="col-12">
            <a class="btn btn-secondary fs-4" href="/rezervacije-kalendar">
                <?php echo __('Kalendarski prikaz', 'edevus'); ?>
            </a>
        </div>
    </div>
    <div class="pt-5 pb-5 d-flex align-items-center justify-content-end">
        <a href="/rezervacije/add" class="text-decoration-none">
            <button type="button" class="addStaff btn btn-outline-danger btn-lg d-flex align-items-center">
                <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                Dodaj
            </button>
        </a>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="m-5">
                <?php
                echo do_shortcode('[calendar template="card" color="red" category="" tag="" post_type="pro_reservations"]');
                ?>
            </div>
        </div>
    </div>
</div>

<?php function add_pro_reservation_script_to_footer()
{ ?>
    <script>
        jQuery(document).ready(function() {



        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_reservation_script_to_footer', 999);
?>