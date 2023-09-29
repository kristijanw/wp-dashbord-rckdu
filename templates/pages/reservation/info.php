<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php 
    $reservations = new ReservationClass();
    $reservation = $reservations->findById($_GET['id']);
?>

<div class="container p-5">

    <div class="row">
        <div class="col">
            <a href="/rezervacije" class="d-flex align-items-center gap-4 fs-4 fw-bold text-danger text-decoration-none">
                <img src="/wp-content/uploads/2023/09/back-icon.svg" alt="back">
                Natrag
            </a>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-4">
        <div>
            <h3>Pregled rezervacije</h3>
        </div>
        <div>
            <a href="/rezervacije/edit/?id=<?php echo $reservation['id']; ?>" class="btn btn-light fs-4 text-decoration-none">Uredi</a>
            <a class="btn btn-light fs-4 text-decoration-none deleteReservation" data-id="<?php echo $reservation['id']; ?>">
                Izbriši
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Ime</p>
                <span class="fs-5"><?php echo $reservation['user']['name']; ?></span>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Prezime</p>
                <span class="fs-5"><?php echo $reservation['user']['lastname']; ?></span>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Email</p>
                <span class="fs-5"><?php echo $reservation['user']['email']; ?></span>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Telefon</p>
                <span class="fs-5"><?php echo $reservation['user']['telephone']; ?></span>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Broj ljudi</p>
                <span class="fs-5"><?php echo $reservation['number_people']; ?></span>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Napomena gosta</p>
                <span class="fs-5"><?php echo $reservation['note']; ?></span>
            </div>
        </div>
    </div>

    <hr>

    <div class="row mt-4">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Broj stola</p>
                <span class="fs-5"><?php echo $reservation['tables']['title']; ?></span>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Datum</p>
                <span class="fs-5"><?php echo $reservation['date_reservation']; ?></span>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Termin rezervacije</p>
                <span class="fs-5"><?php echo $reservation['time_reservation_from'].' - '.$reservation['time_reservation_to']; ?></span>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Status rezervacije</p>
                <span class="fs-5"><?php echo $reservation['status_reservation']; ?></span>
            </div>
        </div>
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

<?php function add_pro_reservationinfo_script_to_footer() { ?>
    <script>
        jQuery(document).ready(function() {

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
                        'action' : 'pro_delete', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#deleteReservationm').modal('hide');
                    jQuery('#deleteReservationm').css('z-index', -1);

                    if(data.success){
                        window.location.href = '/rezervacije';
                    } else {
                        alert(data.data.message);
                    }
                });
            });

        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_reservationinfo_script_to_footer', 999);
?>