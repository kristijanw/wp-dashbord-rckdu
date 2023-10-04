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
            <div class="col-12">
                <div class="d-flex align-items-center gap-3">
                    <a class="btn btn-secondary fs-4" href="/rezervacije">
                        <?php echo __('Tablični prikaz', 'edevus'); ?>
                    </a>
                    <a class="btn btn-outline-secondary fs-4" href="/rezervacije/vremenski">
                        <?php echo __('Vremenski prikaz', 'edevus'); ?>
                    </a>
                    <a class="btn btn-outline-secondary fs-4" href="/rezervacije-kalendar">
                        <?php echo __('Kalendarski prikaz', 'edevus'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-5 pb-5 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <input type="date" id="start_date" class="form-control" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                <span>-</span>
                <input type="date" id="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
            </div>
            <div>
                <select class="form-select form-select-lg" name="statsreservation" id="statsreservation">
                    <option value="">Sve rezervacije</option>
                    <option value="91" <?php echo isset($_GET['statsreservation']) && $_GET['statsreservation'] == 91 ? 'selected' : ''; ?>>Pregledane rezervacije</option>
                    <option value="99" <?php echo isset($_GET['statsreservation']) && $_GET['statsreservation'] == 99 ? 'selected' : ''; ?>>Nepregledane rezervacije</option>
                </select>
            </div>
        </div>
        <div>
            <a href="/rezervacije/add" class="text-decoration-none">
                <button type="button" class="addStaff btn btn-outline-danger btn-lg d-flex align-items-center">
                    <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                    Dodaj
                </button>
            </a>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-2 fs-4">Datum</div>
        <div class="col-2 fs-4">Termin</div>
        <div class="col-1 fs-4">Rezervacija</div>
        <div class="col-1 fs-4">Dolazak</div>
    </div>

    <div class="row mt-2">
        <?php if (!empty($reservations->loadAllReservations())) { ?>
            <?php foreach ($reservations->loadAllReservations() as $reservation) {?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center fs-4">
                            <div class="col-2"><?php echo $reservation['date_reservation']; ?></div>
                            <div class="col-2"><?php echo $reservation['time_reservation_from'] . ' - ' . $reservation['time_reservation_to']; ?></div>
                            <div class="col-1"><?php echo $reservation['title']; ?></div>
                            <div class="col-1"><?php
                                if(get_post_meta($reservation['id'], '_from_online', true) == 'yes') {
                                    echo "<span class='badge text-bg-light'>Web Forma</span>";
                                } else {
                                    echo "<span class='badge text-bg-light'>Sustav</span>";
                                }
                            ?></div>
                            <div class="col-2"><?php echo $reservation['user']['name'] . ' ' . $reservation['user']['lastname']; ?></div>
                            <div class="col-1"><?php echo !empty($reservation['tables']['id']) ? $reservation['tables']['room']['title'] : ''; ?></div>
                            <div class="col-1"><?php echo !empty($reservation['tables']['id']) ? $reservation['tables']['title'] : ''; ?></div>
                            <div class="col-1 d-flex align-items-center">
                                <a href="/rezervacije/edit/?id=<?php echo $reservation['id']; ?>">
                                    <img src="/wp-content/uploads/2023/09/edit-icon.svg" alt="">
                                </a>
                                <div class="ms-2"></div>
                                <img class="deleteReservation pe-auto" data-id="<?php echo $reservation['id']; ?>" src="/wp-content/uploads/2023/09/delete-icon.svg" alt="">
                            </div>
                            <div class="col-1">
                                <a href="/rezervacije/rezervacija/?id=<?php echo $reservation['id']; ?>">
                                    <button class="btn btn-light fs-5">Prikaži sve</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>


<!-- DELETE TABLE MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="deleteReservationm" tabindex="-1" aria-labelledby="deleteReservationmLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container delete-field-reservation">
                    <input type="hidden" name="id" id="id" value="">

                    <h2 class="text-center">Želite li izbrisati stol?</h2>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-primary delete-reservation">Izbriši</button>
            </div>
        </div>
    </div>
</div>
<!-- DELETE TABLE MODAL -->

<?php function add_pro_reservation_script_to_footer()
{ ?>
    <script>
        jQuery(document).ready(function() {

            jQuery('#start_date').change(function(e) {
                e.preventDefault();
                updateURL('start_date', jQuery(this).val());
            });
            jQuery('#end_date').change(function(e) {
                e.preventDefault();
                updateURL('end_date', jQuery(this).val());
            });
            jQuery('#statsreservation').change(function(e) {
                e.preventDefault();
                updateURL('statsreservation', jQuery(this).val());
            });

            // Delete reservation
            jQuery('.deleteReservation').click(function(e) {
                e.preventDefault();
                jQuery('#deleteReservationm').modal('show');
                jQuery('#deleteReservationm').css('z-index', 99999);

                jQuery('#deleteReservationm').find('#id').val(jQuery(this).attr('data-id'));
            });
            jQuery('#deleteReservationm .delete-reservation').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.delete-field-reservation');
                const params = {
                    id: formData.find('#id').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_delete',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#deleteReservationm').modal('hide');
                    jQuery('#deleteReservationm').css('z-index', -1);

                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_reservation_script_to_footer', 999);
?>