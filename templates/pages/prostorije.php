<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php 
    $rooms = new RoomsClass();
?>

<div class="container p-5">
    <div class="pt-5 pb-5 d-flex align-items-center justify-content-end">
        <div>
            <button type="button" class="addRoom btn btn-outline-danger btn-lg d-flex align-items-center">
                <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                Dodaj
            </button>
        </div>
    </div>

    <div class="row">
        <?php if(!empty($rooms->loadAllRooms())){ ?>
            <?php foreach ($rooms->loadAllRooms() as $room) { ?>
                <div class="card mb-4 room">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                               <h3 class="fw-semibold"><?php echo $room['title']; ?></h3>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-end">
                                    <img class="editRoom" data-id="<?php echo $room['id']; ?>" 
                                    data-title="<?php echo $room['title']; ?>" src="/wp-content/uploads/2023/09/edit-icon.svg" alt="">
                                    <div class="ms-2"></div>
                                    <img class="deleteRoom" data-id="<?php echo $room['id']; ?>" src="/wp-content/uploads/2023/09/delete-icon.svg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php function add_pro_prostorije_script_to_footer() { ?>
    <script>
        jQuery(document).ready(function() {

            // Create rooms
            jQuery('.addRoom').click(function(e) {
                e.preventDefault();
                jQuery('#addRoom').modal('show');
                jQuery('#addRoom').css('z-index', 99999);
            });
            jQuery('.modal .add-room').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formdata = jQuery(this).parent().parent().find('.new-field-room');
                const params = {
                    title: formdata.find('#title').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_room_create', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#addRoom').modal('hide');
                    jQuery('#addRoom').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Edit Room
            jQuery('.editRoom').click(function(e) {
                e.preventDefault();
                jQuery('#editRoom').modal('show'); 
                jQuery('#editRoom').css('z-index', 99999);

                jQuery('#editRoom').find('#id').val(jQuery(this).attr('data-id'));
                jQuery('#editRoom').find('#title').val(jQuery(this).attr('data-title'));
            });
            jQuery('.modal .update-room').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.update-field-room');
                const params = {
                    id: formData.find('#id').val(),
                    title: formData.find('#title').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_room_update', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#editRoom').modal('hide');
                    jQuery('#editRoom').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Delete room
            jQuery('.deleteRoom').click(function(e) {
                e.preventDefault();
                jQuery('#deleteRoom').modal('show'); 
                jQuery('#deleteRoom').css('z-index', 99999);

                jQuery('#deleteRoom').find('#id').val(jQuery(this).attr('data-id'));
            });
            jQuery('#deleteRoom .delete-room').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.delete-field-room');
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
                    jQuery('#deleteRoom').modal('hide');
                    jQuery('#deleteRoom').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_prostorije_script_to_footer', 999);
?>


<!-- ADD NEW ROOM MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="addRoom" tabindex="-1" aria-labelledby="addRoomLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5 w-100" id="addRoomLabel">Dodavanje prostorije</h1>
      </div>
      <div class="modal-body">
        <div class="container new-field-room">
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label for="title" class="form-label">Naziv prostorije</label>
						<input type="text" class="form-control" id="title">
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary add-room">Dodaj</button>
      </div>
    </div>
  </div>
</div>
<!-- ADD NEW ROOM MODAL -->

<!-- EDIT ROOM MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="editRoom" tabindex="-1" aria-labelledby="editRoomLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5 w-100" id="editRoomLabel">Uređivanje člana osoblja</h1>
      </div>
      <div class="modal-body">
          <div class="container update-field-room">
            <input type="hidden" name="id" id="id" value="">
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label for="title" class="form-label">Naziv prostorije</label>
						<input type="text" class="form-control" id="title">
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary update-room">Ažuriraj</button>
      </div>
    </div>
  </div>
</div>
<!-- EDIT ROOM MODAL -->

<!-- DELETE ROOM MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="deleteRoom" tabindex="-1" aria-labelledby="deleteRoomLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <div class="container delete-field-room">
            <input type="hidden" name="id" id="id" value="">
			
            <h2 class="text-center">Želite li izbrisati prostoriju?</h2>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary delete-room">Izbriši</button>
      </div>
    </div>
  </div>
</div>
<!-- DELETE ROOM MODAL -->