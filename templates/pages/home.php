<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php 
    $staff = new StaffClass();
?>

<div class="container p-5">
    <div class="pt-5 pb-5 d-flex align-items-center justify-content-between">
        <div>
            <select class="form-select form-select-lg" name="" id="roles">
                <option value="" <?php echo $_GET['roles'] == '' ? 'selected' : ''; ?>>Svi</option>
                <option value="administrator" <?php echo $_GET['roles'] == 'administrator' ? 'selected' : ''; ?>>Administrator</option>
                <option value="um_voditelj-smjene" <?php echo $_GET['roles'] == 'um_voditelj-smjene' ? 'selected' : ''; ?>>Voditelj smjene</option>
                <option value="um_konobar" <?php echo $_GET['roles'] == 'um_konobar' ? 'selected' : ''; ?>>Konobar</option>
            </select>
        </div>
        <div>
            <button type="button" class="addStaff btn btn-outline-danger btn-lg d-flex align-items-center">
                <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                Dodaj
            </button>
        </div>
    </div>

    <div class="row">
        <?php if(!empty($staff->loadAllStaff())){ ?>
            <?php foreach ($staff->loadAllStaff() as $staff) { ?>
                <div class="card mb-4 staff">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                               <h3 class="fw-semibold"><?php echo $staff['first_name'].' '.$staff['last_name']; ?></h3>
                            </div>
                            <div class="col-md-4">
                                <h5>
                                    <span class="badge text-bg-<?php echo $staff['color']; ?>"><?php echo $staff['roles']; ?></span>
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-end">
                                    <img class="editStaff" data-userid="<?php echo $staff['id']; ?>" 
                                    data-name="<?php echo $staff['first_name']; ?>" data-last="<?php echo $staff['last_name']; ?>"
                                    data-email="<?php echo $staff['email']; ?>" data-roles="<?php echo $staff['role_for_drop']; ?>"
                                    src="/wp-content/uploads/2023/09/edit-icon.svg" alt="">
                                    <div class="ms-2"></div>
                                    <img class="deleteStaff" data-userid="<?php echo $staff['id']; ?>" src="/wp-content/uploads/2023/09/delete-icon.svg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php function add_pro_home_script_to_footer() { ?>
    <script>
        jQuery(document).ready(function() {

            jQuery('#roles').change(function(e) {   
                e.preventDefault();
                updateURL('roles', jQuery(this).val());
            });

            // Clean modal form add staff
            jQuery('.close-modal').click(function(e) {
                const newStaff = jQuery(this).parent().parent().find('.new-field-staff');
                newStaff.find('#firstname').val('');
                newStaff.find('#lastname').val('');
                newStaff.find('#email').val('');
                newStaff.find('#role').val('');
            });

            // Create staff
            jQuery('.addStaff').click(function(e) {
                e.preventDefault();
                jQuery('#addStaff').modal('show');
                jQuery('#addStaff').css('z-index', 99999);
            });
            jQuery('.modal .add-staff').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const newStaff = jQuery(this).parent().parent().find('.new-field-staff');
                const params = {
                    first_name: newStaff.find('#firstname').val(),
                    last_name: newStaff.find('#lastname').val(),
                    email: newStaff.find('#email').val(),
                    role: newStaff.find('#role').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_user_registration', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#addStaff').modal('hide');
                    jQuery('#addStaff').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Edit Staff
            jQuery('.editStaff').click(function(e) {
                e.preventDefault();
                jQuery('#editStaff').modal('show'); 
                jQuery('#editStaff').css('z-index', 99999);

                jQuery('#editStaff').find('#user_id').val(jQuery(this).attr('data-userid'));
                jQuery('#editStaff').find('#firstname').val(jQuery(this).attr('data-name'));
                jQuery('#editStaff').find('#lastname').val(jQuery(this).attr('data-last'));
                jQuery('#editStaff').find('#email').val(jQuery(this).attr('data-email'));
                jQuery('#editStaff').find('#role').val(jQuery(this).attr('data-roles'));
            });
            jQuery('.modal .update-staff').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const updateStaff = jQuery(this).parent().parent().find('.update-field-staff');
                const params = {
                    user_id: updateStaff.find('#user_id').val(),
                    first_name: updateStaff.find('#firstname').val(),
                    last_name: updateStaff.find('#lastname').val(),
                    email: updateStaff.find('#email').val(),
                    role: updateStaff.find('#role').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_user_update', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#editStaff').modal('hide');
                    jQuery('#editStaff').css('z-index', -1);

                    if(data.success){
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Delete staff
            jQuery('.deleteStaff').click(function(e) {
                e.preventDefault();
                jQuery('#deleteStaff').modal('show'); 
                jQuery('#deleteStaff').css('z-index', 99999);

                jQuery('#deleteStaff').find('#user_id').val(jQuery(this).attr('data-userid'));
            });

            jQuery('#deleteStaff .delete-staff').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const updateStaff = jQuery(this).parent().parent().find('.delete-field-staff');
                const params = {
                    user_id: updateStaff.find('#user_id').val(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action' : 'pro_user_delete', 
                        data: params, 
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#deleteStaff').modal('hide');
                    jQuery('#deleteStaff').css('z-index', -1);

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
add_action('wp_footer', 'add_pro_home_script_to_footer', 999);
?>

<!-- ADD NEW STAFF MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="addStaff" tabindex="-1" aria-labelledby="addStaffLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5 w-100" id="addStaffLabel">Dodavanje člana osoblja</h1>
      </div>
      <div class="modal-body">
        <div class="container new-field-staff">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label for="firstname" class="form-label">Ime</label>
						<input type="text" class="form-control" id="firstname">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label for="lastname" class="form-label">Prezime</label>
						<input type="text" class="form-control" id="lastname">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label for="email" class="form-label">Email</label>
						<input type="email" class="form-control" id="email">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label for="role" class="form-label">Razina ovlasti</label>
						<select name="role" class="form-select" id="role">
							<option value="administrator">Administrator</option>
							<option value="um_voditelj-smjene">Voditelj smjene</option>
							<option value="um_konobar">Konobar</option>
						</select>
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary add-staff">Dodaj</button>
      </div>
    </div>
  </div>
</div>
<!-- ADD NEW STAFF MODAL -->

<!-- EDIT STAFF MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="editStaff" tabindex="-1" aria-labelledby="editStaffLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5 w-100" id="editStaffLabel">Uređivanje člana osoblja</h1>
      </div>
      <div class="modal-body">
          <div class="container update-field-staff">
            <input type="hidden" name="user_id" id="user_id" value="">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label for="firstname" class="form-label">Ime</label>
						<input type="text" class="form-control" id="firstname">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label for="lastname" class="form-label">Prezime</label>
						<input type="text" class="form-control" id="lastname">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label for="email" class="form-label">Email</label>
						<input type="email" class="form-control" id="email">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label for="role" class="form-label">Razina ovlasti</label>
						<select name="role" class="form-select" id="role">
							<option value="administrator">Administrator</option>
							<option value="um_voditelj-smjene">Voditelj smjene</option>
							<option value="um_konobar">Konobar</option>
						</select>
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary update-staff">Ažuriraj</button>
      </div>
    </div>
  </div>
</div>
<!-- EDIT STAFF MODAL -->

<!-- DELETE STAFF MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="deleteStaff" tabindex="-1" aria-labelledby="deleteStaffLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <div class="container delete-field-staff">
            <input type="hidden" name="user_id" id="user_id" value="">
			
            <h2>Želite li izbrisati člana osoblja?</h2>
		</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-primary delete-staff">Izbriši</button>
      </div>
    </div>
  </div>
</div>
<!-- DELETE STAFF MODAL -->