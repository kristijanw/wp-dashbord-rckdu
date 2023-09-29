<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<?php
$reservations = new ReservationClass();
$tables = new TablesClass();
?>

<div class="container p-5 reservations">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3">
                <a class="btn btn-outline-secondary fs-4" href="/rezervacije">
                    <?php echo __('Tablični prikaz', 'edevus'); ?>
                </a>
                <a class="btn btn-secondary fs-4" href="/rezervacije-vremenski">
                    <?php echo __('Vremenski prikaz', 'edevus'); ?>
                </a>
                <a class="btn btn-outline-secondary fs-4" href="/rezervacije-kalendar">
                    <?php echo __('Kalendarski prikaz', 'edevus'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="pt-5 pb-5 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <input type="date" id="date" class="form-control" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
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

    <div class="row mt-2">
        <div class="col-12">
            <div class="mt-5 mb-5">

                <?php if (!empty($tables->loadTables())) { ?>
                    <?php foreach ($tables->loadTables() as $table) { ?>
                        <div class="row">
                            <div class="col-2 p-3 d-flex flex-column justify-content-center">
                                <p class="m-0 fs-4"><?php echo $table['title']; ?></p>
                                <p class="m-0 fs-4"><?php echo $table['room_title']; ?></p>
                            </div>
                            <div class="col-4 m-2" style="background-color: #f5f5f5;">
                                <ul id="sortable<?php echo $table['id']; ?>" class="connectedSortable connectedSortablePersonnel" data-table="<?php echo $table['id']; ?>" data-time="11" style="height: 80px; margin: 15px 0;">

                                    <?php if (!empty($reservations->reservationByTableId($table['id']))) { ?>
                                        <?php foreach ($reservations->reservationByTableId($table['id']) as $res) { ?>
                                            <li id="sorder_<?php echo $table['ID']; ?>" class="ui-state-default list-group-item" data-res="<?php echo $res['id']; ?>">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">
                                                        # ID <?php echo $res['id']; ?>
                                                    </div>
                                                    <hr>
                                                    <h5><?php echo $res['user']['name'] . ' ' . $res['user']['lastname']; ?></h5>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>

                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>


            </div>
        </div>
    </div>
</div>

<style>
    .calendar.red .reservation-info {
        margin: 8rem 0;
        font-size: 2.2rem;
    }

    .calendar.red .reservation-info p {
        font-family: Poppins;
        line-height: 6rem;
        font-weight: 400;
    }
</style>

<?php function add_pro_reservation_script_to_footer()
{ ?>
    <script>
        jQuery(document).ready(function() {

            sortableIDs = [];

            var dataIds = jQuery('.connectedSortable').map(function() {
                return jQuery(this).data('table');
            }).get();

            dataIds.forEach(function(dataId) {
                sortableIDs.push('#sortable' + dataId);
            });

            jQuery('#sortable0').sortable({
                connectWith: '.connectedSortable',
            });

            if (sortableIDs != '') {
                var joinSortableIDs = sortableIDs.join(', ');

                jQuery(joinSortableIDs).sortable({
                    connectWith: '.connectedSortable',
                    update: function(event, ui) {
                        if (this === ui.item.parent()[0]) {
                            jQuery('.pro-loader').show();

                            var tableId = ui.item.parent().attr("data-table");
                            var time = ui.item.parent().attr("data-time");
                            var post_id = ui.item.attr("data-res");
                            updateReservationTableTime(tableId, time, post_id);

                            jQuery('.pro-loader').hide();
                        }
                    }
                }).disableSelection();
            }

            function updateReservationTableTime(tableId, time, post_id) {
                jQuery('.pro-loader').show();

                var params = {
                    'post_id': post_id,
                    'tables_id': tableId,
                    'time': time
                };

                console.log(params)

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_reservation_update_table_time',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();

                    if (data.data.status == 'success') {
                        location.reload();
                    } else {
                        alert(data.data.message);
                    }
                });
            }

        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_reservation_script_to_footer', 999);
?>