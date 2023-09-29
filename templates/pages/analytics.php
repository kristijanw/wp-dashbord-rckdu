<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php
$reservations = new ReservationClass();
?>

<div class="container p-5 analytics">

    <div class="pt-5 pb-5 d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <input type="date" id="date_filter" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>" class="form-control">
        </div>
        <div>
            <button type="button" id="exportAnalytics" class="export btn btn-outline-secondary btn-lg d-flex align-items-center">
                <img src="/wp-content/uploads/2023/09/export.svg" alt="export">
                Izvoz
            </button>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($reservations->reservationsAnalytics())) { ?>
            <?php foreach ($reservations->reservationsAnalytics() as $reservation) { ?>
                <div class="col-3 mb-4 stol">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="m-0 fs-3"><strong><?php echo $reservation['user']['name'] . ' ' . $reservation['user']['lastname']; ?></strong></p>
                                <img class="editAnalytics" data-id="<?php echo $reservation['id']; ?>" src="/wp-content/uploads/2023/09/edit-icon.svg" alt="edit">
                            </div>

                            <div class="mt-3">
                                <p class="fs-4 mb-2">Datum: <strong><?php echo $reservation['date_reservation']; ?></strong></p>
                                <p class="fs-4 mb-2">Broj stola: <strong><?php echo $reservation['tables']['title']; ?></strong></p>
                                <p class="fs-4 mb-2">
                                    Termin rezervacije:
                                    <strong><?php echo $reservation['time_reservation_from'] . ' - ' . $reservation['time_reservation_to']; ?></strong>
                                </p>
                                <?php if ($reservation['time_from'] != '00:00' && $reservation['time_to'] != '00:00') { ?>
                                    <p class="fs-4 mb-2">
                                        Dolazak/Odlazak:
                                        <strong><?php echo $reservation['time_from'] . ' - ' . $reservation['time_to']; ?></strong>
                                    </p>
                                <?php } ?>
                                <?php if ($reservation['time_from'] != '00:00' && $reservation['time_to'] != '00:00') { ?>
                                    <p class="fs-4 mb-2">
                                        Vrijeme zadržavanja:
                                        <strong>
                                            <?php
                                            $from_time = strtotime($reservation['time_from']);
                                            $to_time = strtotime($reservation['time_to']);
                                            $timeDifferenceInSeconds = $to_time - $from_time;
                                            echo floor($timeDifferenceInSeconds / 3600) . ':' . floor(($timeDifferenceInSeconds % 3600) / 60);
                                            ?>
                                        </strong>
                                    </p>
                                <?php } else { ?>
                                    <p class="fs-4 mb-2">
                                        Vrijeme zadržavanja:
                                        <strong>
                                            <?php
                                            $from_time = strtotime($reservation['time_reservation_from']);
                                            $to_time = strtotime($reservation['time_reservation_to']);
                                            $timeDifferenceInSeconds = $to_time - $from_time;
                                            echo floor($timeDifferenceInSeconds / 3600) . ':' . floor(($timeDifferenceInSeconds % 3600) / 60);
                                            ?>
                                        </strong>
                                    </p>
                                <?php } ?>
                                <p class="fs-4 mb-2">Država: <strong><?php echo $reservation['country']; ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php function add_pro_stolovi_script_to_footer()
{ ?>
    <script>
        jQuery(document).ready(function() {

            jQuery('#date_filter').change(function(e) {
                e.preventDefault();
                updateURL('date', jQuery(this).val());
            });

            jQuery('#exportAnalytics').click(function(e) {
                e.preventDefault();
                updateURL('exportAnalytics', 1);
            });

            jQuery(".editAnalytics").click(function(e) {
                e.preventDefault();

                jQuery('#editAnalytics').modal('show');
                jQuery('#editAnalytics').css('z-index', 99999);

                jQuery('.pro-loader').show();

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_get_analytic',
                        data: {
                            id: jQuery(this).attr('data-id'),
                        },
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();

                    if (data.success) {
                        var par = data.data.data;
                        jQuery('#editAnalytics').find('#id').val(par.id);
                        jQuery('#editAnalytics').find('#name').html(par.user.name);
                        jQuery('#editAnalytics').find('#lastname').html(par.user.lastname);
                        jQuery('#editAnalytics').find('#room').html(par.tables.room.title);
                        jQuery('#editAnalytics').find('#table').html(par.tables.title);
                        jQuery('#editAnalytics').find('#date').html(par.date_reservation);
                        jQuery('#editAnalytics').find('#timefrom').val(par.time_reservation_from);
                        jQuery('#editAnalytics').find('#timeto').val(par.time_reservation_to);
                        jQuery('#editAnalytics').find('#country').val(par.country);
                    } else {
                        alert('Greška');
                        return;
                    }
                });
            });

            jQuery('#editAnalytics .update-analytic').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.update-field-analytic');
                const params = {
                    id: formData.find('#id').val(),
                    timeFrom: formData.find('#time_from').val(),
                    timeTo: formData.find('#time_to').val(),
                    country: formData.find('#country').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_update_analytic',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#editAnalytics').modal('hide');
                    jQuery('#editAnalytics').css('z-index', -1);

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
add_action('wp_footer', 'add_pro_stolovi_script_to_footer', 999);
?>

<style>
    .analytics #date_filter {
        height: 35px !important;
    }
</style>

<!-- EDIT -->
<div class="modal fade modal-dialog modal-dialog-centered" id="editAnalytics" tabindex="-1" aria-labelledby="editAnalyticsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1 class="modal-title fs-3 w-100" id="editAnalyticsLabel">Uređivanje Analitike</h1>
            </div>
            <div class="modal-body">
                <div class="container update-field-analytic">
                    <input type="hidden" id="id" value="">
                    <div class="row mb-5">
                        <div class="col-6">
                            <label class="fw-bold m-0" for="name">Ime gosta</label>
                            <p class="fs-3 fw-light" id="name"></p>
                        </div>
                        <div class="col-6">
                            <label class="fw-bold m-0" for="lastname">Prezime gosta</label>
                            <p class="fs-3 fw-light" id="lastname"></p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-4">
                            <label class="fw-bold m-0" for="room">Prostorija</label>
                            <p class="fs-3 fw-light" id="room"></p>
                        </div>
                        <div class="col-4">
                            <label class="fw-bold m-0" for="table">Broj stola</label>
                            <p class="fs-3 fw-light" id="table"></p>
                        </div>
                        <div class="col-4">
                            <label class="fw-bold m-0" for="date">Datum</label>
                            <p class="fs-3 fw-light" id="date"></p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-12">
                            <label class="fw-bold m-0" for="timeform_timeto">Termin rezervacije</label>
                        </div>
                        <div class="col-4">
                            <input type="time" id="timefrom" disabled readonly>
                        </div>
                        <div class="col-4">
                            <input type="time" id="timeto" disabled readonly>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-12">
                            <label class="fw-bold m-0" for="timeform_timeto">Dolazak/Odlazak</label>
                        </div>
                        <div class="col-4">
                            <input type="time" id="time_from">
                        </div>
                        <div class="col-4">
                            <input type="time" id="time_to">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-12">
                            <label class="fw-bold m-0" for="country">Država</label>
                            <input type="text" id="country">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-primary update-analytic">Ažuriraj</button>
            </div>
        </div>
    </div>
</div>
<!-- EDIT -->