<?php
if (!defined('ABSPATH')) exit;


// EXPORT ANALYTICS
if (isset($_GET['exportAnalytics']) && $_GET['exportAnalytics']) {
    exportAnalytics();
}

function exportAnalytics()
{
    require_once dirname(__FILE__) . '/SimpleXLSX.php';

    $helper = new ReservationClass();
    $reservations = $helper->reservationsAnalytics();

    $array[] = [
        'ID', 'Naziv', 'Gost Ime', 'Gost prezime', 'Gost email', 'Gost telefon', 'Država',
        'Datum rezervacije', 'Vrijeme rezervacije', 'Datum dolaska/odlaska', 'Vrijeme zadržavanja', 'Status',
        'Prostorija', 'Stol', 'Broj ljudi', 'Napomena', 'Interna napomena', 'Ekskluzivna rezervacija'
    ];
    if ($reservations) {
        foreach ($reservations as $res) {

            if($res['time_from'] != '00:00' && $res['time_to'] != '00:00'){
                $from_time = strtotime($res['time_from']);
                $to_time = strtotime($res['time_to']);
                $timeDifferenceInSeconds = $to_time - $from_time;
            } else {
                $from_time = strtotime($res['time_reservation_from']);
                $to_time = strtotime($res['time_reservation_to']);
                $timeDifferenceInSeconds = $to_time - $from_time;
            }

            $array[] = [
                $res['id'],
                $res['title'],
                $res['user']['name'],
                $res['user']['lastname'],
                $res['user']['email'],
                $res['user']['telephone'],
                $res['country'],
                $res['date_reservation'],
                $res['time_reservation_from'] . ' - ' . $res['time_reservation_to'],
                $res['time_from'] . ' - ' . $res['time_to'],
                floor($timeDifferenceInSeconds / 3600) . ':' . floor(($timeDifferenceInSeconds % 3600) / 60),
                $res['status_reservation'],
                $res['tables']['room']['title'],
                $res['tables']['title'],
                $res['number_people'],
                $res['note'],
                $res['intern_note'],
                $res['exclusive_option'] != false ? 'Uključena' : 'Nije uključena',
            ];
        }
    }

    $xlsx = new SimpleXLSXGen();
    $xlsx->addSheet($array);
    $xlsx->downloadAs('analytics-' . date('d-m-Y', time()) . '.xlsx');
    exit();
}
