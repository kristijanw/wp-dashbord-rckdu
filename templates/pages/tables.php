<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php 
    $tables = new TablesClass();
    $rooms = new RoomsClass();
?>

<div class="container p-5">
    <div class="pt-5 pb-5 d-flex align-items-center justify-content-between">
        <div>
            <?php if(!empty($rooms->loadAllRooms())){ ?>
                <select class="form-select form-select-lg" name="room" id="room">
                    <option value="">Svi</option>
                    <?php foreach($rooms->loadAllRooms() as $room){ ?>
                        <option value="<?php echo $room['id'] ?>" <?php echo $_GET['room'] == $room['id'] ? 'selected' : ''; ?>><?php echo $room['title']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
        </div>
        <div>
            <button type="button" class="addTable btn btn-outline-danger btn-lg d-flex align-items-center">
                <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                Dodaj
            </button>
        </div>
    </div>

    <div class="row">
        <?php if(!empty($tables->loadTables())){ ?>
            <?php foreach ($tables->loadTables() as $table) { ?>
                <div class="col-3 mb-4 stol">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3><?php echo $table['title']; ?></h3>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <img class="editTable" src="/wp-content/uploads/2023/09/edit-icon.svg" alt=""
                                        data-id="<?php echo $table['id']; ?>" data-title="<?php echo $table['title']; ?>"
                                        data-room="<?php echo $table['room_id']; ?>" data-total="<?php echo $table['total_places']; ?>"
                                        data-availability="<?php echo $table['availability_status'] ? '1' : '2'; ?>">
                                        <img class="deleteTable" data-id="<?php echo $table['id']; ?>" src="/wp-content/uploads/2023/09/delete-icon.svg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h4><?php echo __('Sljedeća mjesta:'); ?> <strong><?php echo $table['total_places'] ?></strong></h4>
                            </div>
                            <div class="mt-3">
                                <h4><?php echo __('Prostorija:'); ?> <strong><?php echo $table['room_title'] ?></strong></h4>
                            </div>
                            <div class="mt-4">
                                <h5>
                                    <span class="badge text-bg-<?php echo $table['availability_color']; ?>">
                                        <?php echo $table['availability']; ?>
                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php function add_pro_stolovi_script_to_footer() { ?>
    <script>
        jQuery(document).ready(function() {

            jQuery('#room').change(function(e) {   
                e.preventDefault();
                updateURL('room', jQuery(this).val());
            });

            // Create table
            jQuery('.addTable').click(function(e) {
                e.preventDefault();
                jQuery('#addTable').modal('show');
                jQuery('#addTable').css('z-index', 99999);
            });
            jQuery('.modal .add-table').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.new-field-table');
                const params = {
                    title: formData.find('#title').val(),
                    room_id: formData.find('#room_id').val(),
                    total_places: formData.find('#total_places').val(),
                    availability: formData.find('#availability').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_table_create', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#addTable').modal('hide');
                    jQuery('#addTable').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Edit Room
            jQuery('.editTable').click(function(e) {
                e.preventDefault();
                jQuery('#editTable').modal('show'); 
                jQuery('#editTable').css('z-index', 99999);

                jQuery('#editTable').find('#id').val(jQuery(this).attr('data-id'));
                jQuery('#editTable').find('#title').val(jQuery(this).attr('data-title'));
                jQuery('#editTable').find('#room_id').val(jQuery(this).attr('data-room'));
                jQuery('#editTable').find('#total_places').val(jQuery(this).attr('data-total'));
                jQuery('#editTable').find('#availability').val(jQuery(this).attr('data-availability'));
            });
            jQuery('.modal .update-table').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.update-field-table');
                const params = {
                    id: formData.find('#id').val(),
                    title: formData.find('#title').val(),
                    room_id: formData.find('#room_id').val(),
                    total_places: formData.find('#total_places').val(),
                    availability: formData.find('#availability').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_table_update', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#editTable').modal('hide');
                    jQuery('#editTable').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Delete table
            jQuery('.deleteTable').click(function(e) {
                e.preventDefault();
                jQuery('#deleteTable').modal('show'); 
                jQuery('#deleteTable').css('z-index', 99999);

                jQuery('#deleteTable').find('#id').val(jQuery(this).attr('data-id'));
            });
            jQuery('#deleteTable .delete-table').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.delete-field-table');
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
                    jQuery('#deleteTable').modal('hide');
                    jQuery('#deleteTable').css('z-index', -1);

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
add_action('wp_footer', 'add_pro_stolovi_script_to_footer', 999);
?>


<!-- ADD NEW TABLE MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="addTable" tabindex="-1" aria-labelledby="addTableLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-3 w-100" id="addTableLabel">Dodavanje stola</h1>
      </div>
      <div class="modal-body">
        <div class="container new-field-table">
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label for="title" class="form-label">Naziv stola</label>
						<input type="text" class="form-control" id="title">
					</div>
				</div>
                <div class="col-6">
					<div class="mb-3">
						<label for="room_id" class="form-label">Prostorija</label>
						<?php if(!empty($rooms->loadAllRooms())){ ?>
                            <select class="form-select form-select-lg" name="room_id" id="room_id">
                                <option value="">Svi</option>
                                <?php foreach($rooms->loadAllRooms() as $room){ ?>
                                    <option value="<?php echo $room['id'] ?>" <?php echo $_GET['room'] == $room['id'] ? 'selected' : ''; ?>><?php echo $room['title']; ?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
					</div>
				</div>
                <div class="col-6">
                    <div class="mb-3">
						<label for="total_places" class="form-label">Sjedeća mjesta</label>
						<input type="text" class="form-control" id="total_places">
					</div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
						<label for="availability" class="form-label">Dostupnost</label>
                        <select class="form-select form-select-lg" name="availability" id="availability">
                            <option value="1">Dostupan online</option>
                            <option value="2">Nije dostupan online</option>
                        </select>
					</div>
                </div>
			</div>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary add-table">Dodaj</button>
      </div>
    </div>
  </div>
</div>
<!-- ADD NEW TABLE MODAL -->

<!-- UPDATE TABLE MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="editTable" tabindex="-1" aria-labelledby="editTableLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-3 w-100" id="editTableLabel">Ažuriranje stola</h1>
      </div>
      <div class="modal-body">
        <div class="container update-field-table">
            <input type="hidden" name="id" id="id" value="">
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label for="title" class="form-label">Naziv stola</label>
						<input type="text" class="form-control" id="title">
					</div>
				</div>
                <div class="col-6">
					<div class="mb-3">
						<label for="room_id" class="form-label">Prostorija</label>
						<?php if(!empty($rooms->loadAllRooms())){ ?>
                            <select class="form-select form-select-lg" name="room_id" id="room_id">
                                <option value="">Svi</option>
                                <?php foreach($rooms->loadAllRooms() as $room){ ?>
                                    <option value="<?php echo $room['id'] ?>" <?php echo $_GET['room'] == $room['id'] ? 'selected' : ''; ?>><?php echo $room['title']; ?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
					</div>
				</div>
                <div class="col-6">
                    <div class="mb-3">
						<label for="total_places" class="form-label">Sjedeća mjesta</label>
						<input type="text" class="form-control" id="total_places">
					</div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
						<label for="availability" class="form-label">Dostupnost</label>
                        <select class="form-select form-select-lg" name="availability" id="availability">
                            <option value="1">Dostupan online</option>
                            <option value="2">Nije dostupan online</option>
                        </select>
					</div>
                </div>
			</div>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary update-table">Ažuriraj</button>
      </div>
    </div>
  </div>
</div>
<!-- UPDATE TABLE MODAL -->

<!-- DELETE TABLE MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="deleteTable" tabindex="-1" aria-labelledby="deleteTableLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <div class="container delete-field-table">
            <input type="hidden" name="id" id="id" value="">
			
            <h2 class="text-center">Želite li izbrisati stol?</h2>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary delete-table">Izbriši</button>
      </div>
    </div>
  </div>
</div>
<!-- DELETE TABLE MODAL -->