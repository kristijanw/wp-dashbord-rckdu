<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php
$responsibilities = new ResponsibilitiesClass();
$staff = new StaffClass();
$rooms = new RoomsClass();
?>

<div class="container p-5 responsibilities">

    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center justify-content-between">
                        <?php if (!empty($staff->loadKonobari())) { ?>
                            <?php foreach ($staff->loadKonobari() as $kon) { ?>
                                <div class="konobar fs-4 <?php echo (isset($_GET['user']) && $_GET['user'] == $kon['id']) ? 'selected' : ''; ?>" data-id="<?php echo $kon['id']; ?>">
                                    <?php echo $kon['first_name'] . ' ' . $kon['last_name']; ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-10">
            <div class="row">
                <div class="col">
                    <div class="pb-5 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <input type="date" id="start_date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>" class="form-control">
                        </div>
                        <div>
                            <button type="button" class="addResponsibilities btn btn-outline-danger btn-lg d-flex align-items-center">
                                <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                                Dodaj
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php if (isset($_GET['user'])) { ?>
                    <?php if (!empty($responsibilities->loadAll())) { ?>
                        <?php foreach ($responsibilities->loadAll() as $responsibilitie) { ?>
                            <div class="col-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="fs-3"><?php echo $responsibilitie['title']; ?></p>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <img class="editResponsibilities" src="/wp-content/uploads/2023/09/edit-icon.svg" alt="" data-id="<?php echo $responsibilitie['id']; ?>">
                                                <img class="deleteResponsibilities" data-id="<?php echo $responsibilitie['id']; ?>" src="/wp-content/uploads/2023/09/delete-icon.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <strong class="fs-4"><?php echo $responsibilitie['table']['title']; ?></strong>
                                        </div>
                                        <div class="mt-4">
                                            <strong class="fs-4">Zaduženja</strong>
                                            <div class="list_responsibilities">
                                                <?php echo $responsibilitie['list_responsibilities']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="col">
                            <p class="fs-3">Odabrani konobar trenutno nema zaduženja.</p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col">
                        <p class="fs-3">Odaberite konobara kako bi vidjeli popis zaduženja.</p>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

</div>

<?php function add_pro_zaduzenja_script_to_footer()
{ ?>
    <script>
        jQuery(document).ready(function() {

            // Selected konobar
            jQuery('.konobar').click(function(e) {
                e.preventDefault();
                jQuery('.konobar').removeClass('selected');
                jQuery(this).addClass('selected');
                jQuery('.selected-konobar').html(jQuery(this).html());

                updateURL('user', jQuery(this).attr('data-id'));
            });

            jQuery('#start_date').change(function(e) {
                e.preventDefault();
                updateURL('date', jQuery(this).val());
            });

            // Create
            jQuery('.addResponsibilities').click(function(e) {
                e.preventDefault();
                jQuery('#addResponsibilities').modal('show');
                jQuery('#addResponsibilities').css('z-index', 99999);

                jQuery('select#room').change(function(e) {
                    e.preventDefault();
                    jQuery('.pro-loader').show();

                    jQuery.ajax({
                        type: "POST",
                        dataType: 'JSON',
                        url: prospektAjax.ajaxurl,
                        data: {
                            'action': 'pro_get_tables_by_room',
                            data: {
                                id: jQuery(this).val(),
                            },
                            'security': prospektAjax.ajaxnonce
                        },
                    }).done(function(data) {
                        jQuery('.pro-loader').hide();
                        var par = data.data;
                        par.data.forEach(element => {
                            jQuery('#addResponsibilities #table').append(jQuery('<option>', {
                                value: element.id,
                                text: element.title
                            }));
                        });
                    });
                });
            });
            jQuery('.modal .add-responsibilities').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.new-field-table');
                const params = {
                    user: formData.find('#user_id').val(),
                    table: formData.find('#table').val(),
                    room: formData.find('#room').val(),
                    date: formData.find('#date').val(),
                    list_responsibilities: tinymce.get('list_responsibilities').getContent(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_create_responsibilities',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#addTable').modal('hide');
                    jQuery('#addTable').css('z-index', -1);

                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Edit
            jQuery('.editResponsibilities').click(function(e) {
                e.preventDefault();
                jQuery('#editResponsibilities').modal('show');
                jQuery('#editResponsibilities').css('z-index', 99999);

                jQuery('.pro-loader').show();

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_get_responsibiliti',
                        data: {
                            id: jQuery(this).attr('data-id'),
                        },
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    if (data.success) {
                        var par = data.data.data;

                        jQuery('#editResponsibilities').find('#id').val(par.id);
                        jQuery('#editResponsibilities').find('#user_id').val(par.user.id);
                        jQuery('#editResponsibilities').find('#date').val(par.date);
                        jQuery('#editResponsibilities').find('#room').val(par.room.id);

                        tinyMCE.get("list_responsibilities_edit").setContent(par.list_responsibilities);

                        jQuery('#editResponsibilities #table').empty();

                        jQuery.ajax({
                            type: "POST",
                            dataType: 'JSON',
                            url: prospektAjax.ajaxurl,
                            data: {
                                'action': 'pro_get_tables_by_room',
                                data: {
                                    id: par.room.id,
                                },
                                'security': prospektAjax.ajaxnonce
                            },
                        }).done(function(data) {
                            data.data.data.forEach(element => {
                                jQuery('#editResponsibilities #table').append(jQuery('<option>', {
                                    value: element.id,
                                    text: element.title
                                }));
                            });

                            jQuery('#editResponsibilities').find('#table').val(par.table.id);
                            jQuery('.pro-loader').hide();
                        });
                    } else {
                        alert('Greška');
                        return;
                    }
                });
            });
            jQuery('#editResponsibilities select#room').change(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();
                jQuery('#editResponsibilities #table').empty();

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_get_tables_by_room',
                        data: {
                            id: jQuery(this).val(),
                        },
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    var par = data.data;
                    par.data.forEach(element => {
                        jQuery('#editResponsibilities #table').append(jQuery('<option>', {
                            value: element.id,
                            text: element.title
                        }));
                    });
                });
            });
            jQuery('#editResponsibilities .update-responsibilities').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const formData = jQuery(this).parent().parent().find('.update-field-table');
                const params = {
                    id: formData.find('#id').val(),
                    user: formData.find('#user_id').val(),
                    table: formData.find('#table').val(),
                    room: formData.find('#room').val(),
                    date: formData.find('#date').val(),
                    list_responsibilities: tinymce.get('list_responsibilities_edit').getContent(),
                };

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_update_responsibilities',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#editResponsibilities').modal('hide');
                    jQuery('#editResponsibilities').css('z-index', -1);

                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            });

            // Delete
            jQuery('.deleteResponsibilities').click(function(e) {
                e.preventDefault();
                jQuery('#deleteResponsibilities').modal('show');
                jQuery('#deleteResponsibilities').css('z-index', 99999);

                jQuery('#deleteResponsibilities').find('#id').val(jQuery(this).attr('data-id'));
            });
            jQuery('#deleteResponsibilities .delete-table').click(function(e) {
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
                        'action': 'pro_delete',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();
                    jQuery('#deleteResponsibilities').modal('hide');
                    jQuery('#deleteResponsibilities').css('z-index', -1);

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
add_action('wp_footer', 'add_pro_zaduzenja_script_to_footer', 999);
?>

<style>
    .list_responsibilities ul {
        margin: 0;
    }

    .deleteResponsibilities,
    .editResponsibilities {
        cursor: pointer;
    }

    .responsibilities .konobar {
        padding: 1rem;
        width: 12rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        text-align: center;
        font-size: 14px;
        font-weight: 400;
        cursor: pointer;
        transition: all 0.5s;
    }

    .responsibilities .konobar.selected {
        background-color: #FFF5F7;
        font-weight: 600;
    }
</style>

<div class="modal fade modal-dialog modal-dialog-centered" id="addResponsibilities" tabindex="-1" aria-labelledby="addResponsibilitiesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1 class="modal-title fs-3 w-100" id="addResponsibilitiesLabel">Dodavanje zaduženja</h1>
            </div>
            <div class="modal-body">
                <div class="container new-field-table">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Ime konobara</label>
                                <select class="form-select form-select-lg" name="user_id" id="user_id">
                                    <option value="">Svi</option>
                                    <?php if (!empty($staff->loadKonobari())) { ?>
                                        <?php foreach ($staff->loadKonobari() as $user) { ?>
                                            <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="room" class="form-label">Prostorija</label>
                                <select class="form-select form-select-lg" name="room" id="room">
                                    <option value="">----</option>
                                    <?php if (!empty($rooms->loadAllRooms())) { ?>
                                        <?php foreach ($rooms->loadAllRooms() as $room) { ?>
                                            <option value="<?php echo $room['id'] ?>"><?php echo $room['title']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="table" class="form-label">Broj stola</label>
                                <select class="form-select form-select-lg" name="table" id="table">
                                    <option value="">Svi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="availability" class="form-label">Datum</label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="list_responsibilities" class="form-label">Zaduženja</label>
                                <?php
                                $content_scb = '';
                                $editor_scb_id = 'list_responsibilities';
                                $setup_scb =   array(
                                    'wpautop' => true,
                                    'media_buttons' => true,
                                    'textarea_name' => $editor_scb_id,
                                    'textarea_rows' => 20,
                                    'tabindex' => '',
                                    'editor_css' => '',
                                    'editor_class' => 'form-control validate-sc'
                                );
                                wp_editor($content_scb, $editor_scb_id, $setup_scb);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-primary add-responsibilities">Dodaj</button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT -->
<div class="modal fade modal-dialog modal-dialog-centered" id="editResponsibilities" tabindex="-1" aria-labelledby="editResponsibilitiesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1 class="modal-title fs-3 w-100" id="editResponsibilitiesLabel">Uređivanje zaduženja</h1>
            </div>
            <div class="modal-body">
                <div class="container update-field-table">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Ime konobara</label>
                                <select class="form-select form-select-lg" name="user_id" id="user_id">
                                    <option value="">Svi</option>
                                    <?php if (!empty($staff->loadKonobari())) { ?>
                                        <?php foreach ($staff->loadKonobari() as $user) { ?>
                                            <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="room" class="form-label">Prostorija</label>
                                <select class="form-select form-select-lg" name="room" id="room">
                                    <option value="">----</option>
                                    <?php if (!empty($rooms->loadAllRooms())) { ?>
                                        <?php foreach ($rooms->loadAllRooms() as $room) { ?>
                                            <option value="<?php echo $room['id'] ?>"><?php echo $room['title']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="table" class="form-label">Broj stola</label>
                                <select class="form-select form-select-lg" name="table" id="table">
                                    <option value="">Svi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="availability" class="form-label">Datum</label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>
                        </div>
                        <div class="col">
                            <label for="list_responsibilities_edit" class="form-label">Zaduženja</label>
                            <?php
                            $content_scb = '';
                            $editor_scb_id = 'list_responsibilities_edit';
                            $setup_scb =   array(
                                'wpautop' => true,
                                'media_buttons' => true,
                                'textarea_name' => $editor_scb_id,
                                'textarea_rows' => 20,
                                'tabindex' => '',
                                'editor_css' => '',
                                'editor_class' => 'form-control validate-sc'
                            );
                            wp_editor($content_scb, $editor_scb_id, $setup_scb);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-primary update-responsibilities">Ažuriraj</button>
            </div>
        </div>
    </div>
</div>
<!-- EDIT -->

<!-- DELETE TABLE MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="deleteResponsibilities" tabindex="-1" aria-labelledby="deleteResponsibilitiesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container delete-field-table">
                    <input type="hidden" name="id" id="id" value="">

                    <h2 class="text-center">Želite li izbrisati zaduženje?</h2>
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