<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php 
    $rooms = new RoomsClass();
    $reservations = new ReservationClass();
    $reservation = $reservations->findById($_GET['id']);
?>

<div class="container p-5 reservation-edit">

    <input type="hidden" id="reservationUpdateOrCreate" name="reservationUpdateOrCreate" value="update">
    <input type="hidden" id="post_id" value="<?php echo $reservation['id']; ?>">

    <div class="row">
        <div class="col">
            <a href="/rezervacije" class="d-flex align-items-center gap-4 fs-4 fw-bold text-danger text-decoration-none">
                <img src="/wp-content/uploads/2023/09/back-icon.svg" alt="back">
                Natrag
            </a>
        </div>
    </div>

    <div class="d-flex mt-5">
        <p class="fs-1">Uređivanje rezervacije</p>
    </div>

    <div class="row mt-5">
        <div class="col-4">
            <div>
                <p class="fw-bold fs-4">Ime</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="user_name" value="<?php echo $reservation['user']['name']; ?>">
                </div>
            </div>
        </div>
        <div class="col-4">
            <div>
                <p class="fw-bold fs-4">Prezime</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="user_lastname" value="<?php echo $reservation['user']['lastname']; ?>">
                </div>
            </div>
        </div>
        <div class="col-4">
            <div>
                <p class="fw-bold fs-4">Email</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="user_email" value="<?php echo $reservation['user']['email']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Telefon</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="user_phone" value="<?php echo $reservation['user']['telephone']; ?>">
                </div>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Broj ljudi</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="number_people" value="<?php echo $reservation['number_people']; ?>">
                </div>
            </div>
        </div>
        <div class="col-8">
            <div>
                <p class="fw-bold fs-4">Napomena gosta</p>
                <div class="form-group">
                    <textarea name="note" class="form-control" id="note" cols="30" rows="10"><?php echo $reservation['note']; ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <hr class="mt-5">

    <div class="row mt-5">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Prostorija</p>
                <div class="form-group">
                    <select class="form-select" name="rooms" id="rooms">
                        <?php foreach($rooms->loadAllRooms() as $room){ ?>
                            <option value="<?php echo $room['id']; ?>" 
                            <?php echo $room['id'] == $reservation['tables']['room']['id'] ? 'selected': ''; ?>>
                                <?php echo $room['title']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Broj stola</p>
                <div class="form-group">
                    <select class="form-select" name="table_id" id="table_id">
                        <option value="<?php echo $reservation['tables']['id']; ?>">
                            <?php echo $reservation['tables']['title']; ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Datum</p>
                <div class="form-group">
                    <input type="date" class="form-control" id="date_reservation" 
                    value="<?php echo date('Y-m-d', strtotime($reservation['date_reservation'])); ?>">
                </div>
            </div>
        </div>
        <div class="col-2 w-25">
            <div>
                <p class="fw-bold fs-4">Termin rezervacije</p>
                <div class="d-flex align-items-center gap-5">
                    <div class="form-group">
                        <input type="time" class="form-control" id="time_reservation_from" value="<?php echo $reservation['time_reservation_from']; ?>">
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <input type="time" class="form-control" id="time_reservation_to" value="<?php echo $reservation['time_reservation_to']; ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="form-check mt-5">
                <input class="form-check-input" type="checkbox" value="exclusive_option" 
                id="exclusive_option" <?php echo $reservation['exclusive_option'] == true ? 'checked' : ''; ?>>
                <label class="form-check-label" for="exclusive_option">
                    Ekskluzivna rezervacija
                </label>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="form-group">
                <label for="intern_note">Interna napomena / Važnost događaja</label>
                <textarea name="intern_note" class="form-control" id="intern_note" cols="30" rows="10"><?php echo $reservation['intern_note']; ?></textarea>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-2">
            <div>
                <p class="fw-bold fs-4">Status rezervacije</p>
                <select class="form-select" name="status_reservation" id="status_reservation">
                    <option value="Rezervirano" <?php echo $reservation['status_reservation'] == 'Rezervirano' ? 'selected' : ''; ?>>
                        Rezervirano
                    </option>
                    <option value="Gost došao" <?php echo $reservation['status_reservation'] == 'Gost došao' ? 'selected' : ''; ?>>
                        Gost došao
                    </option>
                    <option value="Gost nije došao" <?php echo $reservation['status_reservation'] == 'Gost nije došao' ? 'selected' : ''; ?>>
                        Gost nije došao
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="btn btn-danger updateReservation mt-5 fs-4">
        Ažuriraj
    </div>

    <div class="mt-5"></div>

</div>

<?php function add_pro_reservationedit_script_to_footer() { ?>
    <script>
        jQuery(document).ready(function() {

            jQuery('#rooms').on('change', function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_load_tables_by_room_id', 
                        data: {
                            room_id: jQuery(this).val()
                        },
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();

                    if(data.data.status == 'success') {
                        const tables = data.data.data;
                        jQuery('#table_id').empty();

                        tables.forEach(function(currentValue, index, arr) {
                            const optionElement = jQuery('<option></option>');
                            optionElement.val(currentValue.id);
                            optionElement.text(currentValue.title);
                            jQuery('#table_id').append(optionElement);
                        });
                    }
                });
            });

            jQuery('.updateReservation').on('click', function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const form = jQuery('.reservation-edit');
                const params = {
                    actions: form.find('#reservationUpdateOrCreate').val(),
                    post_id: form.find('#post_id').val(),
                    user_name: form.find('#user_name').val(),
                    user_lastname: form.find('#user_lastname').val(),
                    user_email: form.find('#user_email').val(),
                    telephone: form.find('#user_phone').val(),
                    number_people: form.find('#number_people').val(),
                    note: form.find('#note').val(),
                    room_id: form.find('#rooms').val(),
                    tables_id: form.find('#table_id').val(),
                    date_reservation: form.find('#date_reservation').val(),
                    time_reservation_from: form.find('#time_reservation_from').val(),
                    time_reservation_to: form.find('#time_reservation_to').val(),
                    exclusive_option: form.find('#exclusive_option').is(":checked") ? true : false,
                    intern_note: form.find('#intern_note').val(),
                    status_reservation: form.find('#status_reservation').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_reservation_update_or_create', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();

                    if(data.data.status == 'success') {
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_reservationedit_script_to_footer', 999);
?>