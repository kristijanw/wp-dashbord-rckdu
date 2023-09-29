<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php include CHILD_TEMPLATE_ROOT . 'components/eu-header.php'; ?>

<div class="container p-5 settings">

    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center justify-content-between">
                        <div class="month">Siječanj</div>
                        <div class="month">Veljača</div>
                        <div class="month">Ožujak</div>
                        <div class="month">Travanj</div>
                        <div class="month">Svibanj</div>
                        <div class="month">Lipanj</div>
                        <div class="month">Srpanj</div>
                        <div class="month">Kolovoz</div>
                        <div class="month">Rujan</div>
                        <div class="month">Listopad</div>
                        <div class="month">Studeni</div>
                        <div class="month">Prosinac</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 p-5 d-none options">
            <h3 class="selected-month"></h3>

            <div class="toggle-btn">
                <input type="checkbox" class="cb-value" />
                <span class="round-btn"></span>
                <span class="txt">Neobavezni način</span>
            </div>

            <div class="msgs mt-5 alert alert-success d-none" role="alert"></div>
            <div class="msge mt-5 alert alert-danger d-none" role="alert"></div>

            <div class="termini mt-5 d-none">
                <div class="card">
                    <div class="card-body">
                        <h2>Termini rezervacija</h2>

                        <div class="listtermini mt-5">
                            <div class="itemtermin mt-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-5 w-75">
                                    <input type="time" name="start" id="time_start">
                                    <span>-</span>
                                    <input type="time" name="end" id="time_end">
                                </div>
                                <div class="d-flex">
                                    <img id="deleteTermin" src="/wp-content/uploads/2023/09/delete-icon.svg" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex align-items-center justify-content-between">
                            <button type="button" class="addTermin btn btn-outline-danger btn-lg d-flex align-items-center">
                                <img src="/wp-content/uploads/2023/09/plus-icon.svg" alt="add">
                                Dodaj
                            </button>

                            <button type="button" class="saveTermin btn btn-outline-success btn-lg d-flex align-items-center">
                                Spremi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php function add_pro_settings_script_to_footer()
{ ?>
    <script>
        jQuery(document).ready(function() {

            // Selected month
            jQuery('.month').click(function(e) {
                e.preventDefault();
                jQuery('.month').removeClass('selected');
                jQuery(this).addClass('selected');
                jQuery('.selected-month').html(jQuery(this).html());

                // Get data for selected month
                getData(jQuery(this).html());
            });

            // Switch active, disable
            jQuery('.cb-value').click(function(e) {
                var mainParent = jQuery(this).parent('.toggle-btn');
                if (jQuery(mainParent).find('input.cb-value').is(':checked')) {
                    jQuery(mainParent).addClass('active');
                    jQuery(mainParent).find('.txt').html('Obavezni način');
                    jQuery(mainParent).parent().find('.termini').attr('style', 'display: block !important');

                    // Active
                    jQuery('.pro-loader').show();
                    updateActiveAndDisable(true, jQuery('.selected-month').html());
                } else {
                    jQuery(mainParent).removeClass('active');
                    jQuery(mainParent).find('.txt').html('Neobavezni način');
                    jQuery(mainParent).parent().find('.termini').attr('style', 'display: none !important');

                    // Disable
                    jQuery('.pro-loader').show();
                    updateActiveAndDisable(false, jQuery('.selected-month').html());
                }
            });

            function updateActiveAndDisable(status, month) {
                const params = {
                    month: findNumberOfMonth(month),
                    active: status,
                }

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_update_settings_reservation',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();

                    const mactive = data.data.data.active;
                    const mmonth = data.data.data.month;
                    const mtimes = data.data.data.times;

                    if (mactive) {
                        jQuery('.toggle-btn').addClass('active');
                        jQuery('.toggle-btn').find('.txt').html('Obavezni način');
                        jQuery('.termini').attr('style', 'display: block !important');

                        jQuery('.listtermini').empty();
                        // List termini from api
                        var listtermini = document.querySelector('.listtermini');

                        if (mtimes != undefined) {
                            if (mtimes.length > 0) {
                                mtimes.forEach(function(currentValue, index, arr) {
                                    var itemtermin = document.createElement('div');
                                    itemtermin.className = 'itemtermin mt-3 d-flex align-items-center justify-content-between';

                                    var innerContainer = document.createElement('div');
                                    innerContainer.className = 'd-flex align-items-center gap-5 w-75';

                                    var startTimeInput = document.createElement('input');
                                    startTimeInput.type = 'time';
                                    startTimeInput.name = 'start';
                                    startTimeInput.id = 'time_start';
                                    startTimeInput.value = currentValue.start;

                                    var dashSpan = document.createElement('span');
                                    dashSpan.textContent = '-';

                                    var endTimeInput = document.createElement('input');
                                    endTimeInput.type = 'time';
                                    endTimeInput.name = 'end';
                                    endTimeInput.id = 'time_end';
                                    endTimeInput.value = currentValue.end;

                                    var deleteIconDiv = document.createElement('div');
                                    var deleteIcon = document.createElement('img');
                                    deleteIcon.id = 'deleteTermin';
                                    deleteIcon.src = '/wp-content/uploads/2023/09/delete-icon.svg';
                                    deleteIcon.alt = '';

                                    innerContainer.appendChild(startTimeInput);
                                    innerContainer.appendChild(dashSpan);
                                    innerContainer.appendChild(endTimeInput);
                                    deleteIconDiv.appendChild(deleteIcon);

                                    itemtermin.appendChild(innerContainer);
                                    itemtermin.appendChild(deleteIconDiv);

                                    listtermini.appendChild(itemtermin);
                                });
                            } else {
                                var itemtermin = document.createElement('div');
                                itemtermin.className = 'itemtermin mt-3 d-flex align-items-center justify-content-between';

                                var innerContainer = document.createElement('div');
                                innerContainer.className = 'd-flex align-items-center gap-5 w-75';

                                var startTimeInput = document.createElement('input');
                                startTimeInput.type = 'time';
                                startTimeInput.name = 'start';
                                startTimeInput.id = 'time_start';

                                var dashSpan = document.createElement('span');
                                dashSpan.textContent = '-';

                                var endTimeInput = document.createElement('input');
                                endTimeInput.type = 'time';
                                endTimeInput.name = 'end';
                                endTimeInput.id = 'time_end';

                                var deleteIconDiv = document.createElement('div');
                                var deleteIcon = document.createElement('img');
                                deleteIcon.id = 'deleteTermin';
                                deleteIcon.src = '/wp-content/uploads/2023/09/delete-icon.svg';
                                deleteIcon.alt = '';

                                innerContainer.appendChild(startTimeInput);
                                innerContainer.appendChild(dashSpan);
                                innerContainer.appendChild(endTimeInput);
                                deleteIconDiv.appendChild(deleteIcon);

                                itemtermin.appendChild(innerContainer);
                                itemtermin.appendChild(deleteIconDiv);

                                listtermini.appendChild(itemtermin);
                            }
                        } else {
                            var itemtermin = document.createElement('div');
                            itemtermin.className = 'itemtermin mt-3 d-flex align-items-center justify-content-between';

                            var innerContainer = document.createElement('div');
                            innerContainer.className = 'd-flex align-items-center gap-5 w-75';

                            var startTimeInput = document.createElement('input');
                            startTimeInput.type = 'time';
                            startTimeInput.name = 'start';
                            startTimeInput.id = 'time_start';

                            var dashSpan = document.createElement('span');
                            dashSpan.textContent = '-';

                            var endTimeInput = document.createElement('input');
                            endTimeInput.type = 'time';
                            endTimeInput.name = 'end';
                            endTimeInput.id = 'time_end';

                            var deleteIconDiv = document.createElement('div');
                            var deleteIcon = document.createElement('img');
                            deleteIcon.id = 'deleteTermin';
                            deleteIcon.src = '/wp-content/uploads/2023/09/delete-icon.svg';
                            deleteIcon.alt = '';

                            innerContainer.appendChild(startTimeInput);
                            innerContainer.appendChild(dashSpan);
                            innerContainer.appendChild(endTimeInput);
                            deleteIconDiv.appendChild(deleteIcon);

                            itemtermin.appendChild(innerContainer);
                            itemtermin.appendChild(deleteIconDiv);

                            listtermini.appendChild(itemtermin);
                        }

                    } else {
                        jQuery('.toggle-btn').removeClass('active');
                        jQuery('.toggle-btn').find('.txt').html('Neobavezni način');
                        jQuery('.termini').attr('style', 'display: none !important');
                    }
                });
            }

            // Add new time
            jQuery('.addTermin').click(function(e) {
                e.preventDefault();
                var item = jQuery('.itemtermin').first().clone();
                item.find('#time_start').val('');
                item.find('#time_end').val('');
                jQuery('.listtermini').append(item);
            });

            // Delete time
            jQuery('.listtermini').on('click', '.itemtermin #deleteTermin', function(e) {
                e.preventDefault();
                jQuery('#deleteOption').modal('show');
                jQuery('#deleteOption').css('z-index', 99999);
                const el = jQuery(this).parent().parent();

                jQuery('#deleteOption').on('click', '.delete-option', function(e) {
                    el.remove();
                    jQuery('#deleteOption').modal('hide');
                    jQuery('#deleteOption').css('z-index', -1);
                });
            });

            // Save time for selected month
            jQuery('.saveTermin').click(function(e) {
                e.preventDefault();
                jQuery('.pro-loader').show();

                const month = jQuery('.selected-month').html();
                const active = jQuery('input.cb-value').is(':checked');
                var times = {};
                jQuery('.itemtermin').each(function(i, obj) {
                    times[i] = {
                        start: jQuery(this).find('#time_start').val(),
                        end: jQuery(this).find('#time_end').val(),
                    };
                });

                const params = {
                    month: findNumberOfMonth(month),
                    active: active,
                    times: times
                }

                jQuery.ajax({
                    type: "POST",
                    dataType: 'JSON',
                    url: prospektAjax.ajaxurl,
                    data: {
                        'action': 'pro_save_settings_reservation',
                        data: params,
                        'security': prospektAjax.ajaxnonce
                    },
                }).done(function(data) {
                    jQuery('.pro-loader').hide();

                    if (data.data.status == 'success') {
                        jQuery('.msgs').html(data.data.message);
                        jQuery('.msgs').attr('style', 'display: block !important;');
                        setTimeout(function() {
                            jQuery('.msgs').attr('style', 'display: none !important;');
                        }, 4000);
                    } else {
                        jQuery('.msge').html(data.data.message);
                        jQuery('.msge').attr('style', 'display: block !important;');
                        setTimeout(function() {
                            jQuery('.msge').attr('style', 'display: none !important;');
                        }, 4000);
                    }
                });
            });

            function findNumberOfMonth(nameMonth) {
                const months = [
                    "Siječanj",
                    "Veljača",
                    "Ožujak",
                    "Travanj",
                    "Svibanj",
                    "Lipanj",
                    "Srpanj",
                    "Kolovoz",
                    "Rujan",
                    "Listopad",
                    "Studeni",
                    "Prosinac"
                ];
                const index = months.indexOf(nameMonth);
                if (index !== -1) {
                    return index + 1;
                } else {
                    return -1;
                }
            }

            function getData(month) {
                jQuery('.pro-loader').show();
                var month = findNumberOfMonth(month);

                $.get("/wp-json/prospekt-endpoint/options/" + month, function(data, status) {
                    jQuery('.pro-loader').hide();
                    jQuery('.options').attr('style', 'display: block !important');

                    if (data.data.status == 'success') {
                        if (data.data.data != null) {
                            const mactive = data.data.data.active;
                            const mmonth = data.data.data.month;
                            const mtimes = data.data.data.times;

                            if (mactive) {
                                jQuery('.toggle-btn').addClass('active');
                                jQuery('.toggle-btn').find('.txt').html('Obavezni način');
                                jQuery('.termini').attr('style', 'display: block !important');

                                jQuery('.listtermini').empty();
                                // List termini from api
                                var listtermini = document.querySelector('.listtermini');

                                if (mtimes != undefined) {
                                    if (mtimes.length > 0) {
                                        mtimes.forEach(function(currentValue, index, arr) {
                                            var itemtermin = document.createElement('div');
                                            itemtermin.className = 'itemtermin mt-3 d-flex align-items-center justify-content-between';

                                            var innerContainer = document.createElement('div');
                                            innerContainer.className = 'd-flex align-items-center gap-5 w-75';

                                            var startTimeInput = document.createElement('input');
                                            startTimeInput.type = 'time';
                                            startTimeInput.name = 'start';
                                            startTimeInput.id = 'time_start';
                                            startTimeInput.value = currentValue.start;

                                            var dashSpan = document.createElement('span');
                                            dashSpan.textContent = '-';

                                            var endTimeInput = document.createElement('input');
                                            endTimeInput.type = 'time';
                                            endTimeInput.name = 'end';
                                            endTimeInput.id = 'time_end';
                                            endTimeInput.value = currentValue.end;

                                            var deleteIconDiv = document.createElement('div');
                                            var deleteIcon = document.createElement('img');
                                            deleteIcon.id = 'deleteTermin';
                                            deleteIcon.src = '/wp-content/uploads/2023/09/delete-icon.svg';
                                            deleteIcon.alt = '';

                                            innerContainer.appendChild(startTimeInput);
                                            innerContainer.appendChild(dashSpan);
                                            innerContainer.appendChild(endTimeInput);
                                            deleteIconDiv.appendChild(deleteIcon);

                                            itemtermin.appendChild(innerContainer);
                                            itemtermin.appendChild(deleteIconDiv);

                                            listtermini.appendChild(itemtermin);
                                        });
                                    } else {
                                        var itemtermin = document.createElement('div');
                                        itemtermin.className = 'itemtermin mt-3 d-flex align-items-center justify-content-between';

                                        var innerContainer = document.createElement('div');
                                        innerContainer.className = 'd-flex align-items-center gap-5 w-75';

                                        var startTimeInput = document.createElement('input');
                                        startTimeInput.type = 'time';
                                        startTimeInput.name = 'start';
                                        startTimeInput.id = 'time_start';

                                        var dashSpan = document.createElement('span');
                                        dashSpan.textContent = '-';

                                        var endTimeInput = document.createElement('input');
                                        endTimeInput.type = 'time';
                                        endTimeInput.name = 'end';
                                        endTimeInput.id = 'time_end';

                                        var deleteIconDiv = document.createElement('div');
                                        var deleteIcon = document.createElement('img');
                                        deleteIcon.id = 'deleteTermin';
                                        deleteIcon.src = '/wp-content/uploads/2023/09/delete-icon.svg';
                                        deleteIcon.alt = '';

                                        innerContainer.appendChild(startTimeInput);
                                        innerContainer.appendChild(dashSpan);
                                        innerContainer.appendChild(endTimeInput);
                                        deleteIconDiv.appendChild(deleteIcon);

                                        itemtermin.appendChild(innerContainer);
                                        itemtermin.appendChild(deleteIconDiv);

                                        listtermini.appendChild(itemtermin);
                                    }
                                } else {
                                    var itemtermin = document.createElement('div');
                                    itemtermin.className = 'itemtermin mt-3 d-flex align-items-center justify-content-between';

                                    var innerContainer = document.createElement('div');
                                    innerContainer.className = 'd-flex align-items-center gap-5 w-75';

                                    var startTimeInput = document.createElement('input');
                                    startTimeInput.type = 'time';
                                    startTimeInput.name = 'start';
                                    startTimeInput.id = 'time_start';

                                    var dashSpan = document.createElement('span');
                                    dashSpan.textContent = '-';

                                    var endTimeInput = document.createElement('input');
                                    endTimeInput.type = 'time';
                                    endTimeInput.name = 'end';
                                    endTimeInput.id = 'time_end';

                                    var deleteIconDiv = document.createElement('div');
                                    var deleteIcon = document.createElement('img');
                                    deleteIcon.id = 'deleteTermin';
                                    deleteIcon.src = '/wp-content/uploads/2023/09/delete-icon.svg';
                                    deleteIcon.alt = '';

                                    innerContainer.appendChild(startTimeInput);
                                    innerContainer.appendChild(dashSpan);
                                    innerContainer.appendChild(endTimeInput);
                                    deleteIconDiv.appendChild(deleteIcon);

                                    itemtermin.appendChild(innerContainer);
                                    itemtermin.appendChild(deleteIconDiv);

                                    listtermini.appendChild(itemtermin);
                                }

                            } else {
                                jQuery('.toggle-btn').removeClass('active');
                                jQuery('.toggle-btn').find('.txt').html('Neobavezni način');
                                jQuery('.termini').attr('style', 'display: none !important');
                            }
                        } else {
                            jQuery('.toggle-btn').removeClass('active');
                            jQuery('.toggle-btn').find('.txt').html('Neobavezni način');
                            jQuery('.termini').attr('style', 'display: none !important');
                        }
                    } else {
                        jQuery('.toggle-btn').removeClass('active');
                        jQuery('.toggle-btn').find('.txt').html('Neobavezni način');
                        jQuery('.termini').attr('style', 'display: none !important');
                    }
                });
            }

        });
    </script>
<?php }
add_action('wp_footer', 'add_pro_settings_script_to_footer', 999);
?>

<!-- DELETE OPTION MODAL -->
<div class="modal fade modal-dialog modal-dialog-centered" id="deleteOption" tabindex="-1" aria-labelledby="deleteOptionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container delete-field-option">
                    <h2 class="text-center">Želite li izbrisati termin rezervacije?</h2>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-primary delete-option">Izbriši</button>
            </div>
        </div>
    </div>
</div>
<!-- DELETE OPTION MODAL -->

<style>
    .msgs,
    .msge {
        transition: all 0.5s;
        font-size: 16px;
    }

    #deleteTermin {
        cursor: pointer;
    }

    .toggle-btn {
        width: 55px;
        height: 12px;
        margin-top: 3rem;
        border-radius: 50px;
        display: inline-block;
        position: relative;
        background: #3131316e;
        cursor: pointer;
        -webkit-transition: background-color 0.4s ease-in-out;
        -moz-transition: background-color 0.4s ease-in-out;
        -o-transition: background-color 0.4s ease-in-out;
        transition: background-color 0.4s ease-in-out;
        cursor: pointer;
    }

    .toggle-btn .txt {
        position: absolute;
        top: -5px;
        left: 7rem;
        width: 15rem;
        font-size: 15px;
    }

    .toggle-btn.active {
        background: rgba(61, 167, 35, 0.30);
    }

    .toggle-btn.active .round-btn {
        left: 30px;
        background-color: #3DA723;
    }

    .toggle-btn .round-btn {
        width: 25px;
        height: 25px;
        background-color: #fff;
        border-radius: 50%;
        display: inline-block;
        position: absolute;
        left: 0px;
        top: 50%;
        margin-top: -13px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        filter: drop-shadow(0px 1px 3px rgba(0, 0, 0, 0.12)) drop-shadow(0px 1px 1px rgba(0, 0, 0, 0.14)) drop-shadow(0px 2px 1px rgba(0, 0, 0, 0.20));
    }

    .toggle-btn .cb-value {
        position: absolute;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 9;
        cursor: pointer;
    }
</style>