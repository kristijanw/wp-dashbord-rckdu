<?php

class ReservationClass extends Helpers
{

    public function loadAllReservations()
    {
        if (!empty($_GET['start_date']) || !empty($_GET['end_date'])) {
            $meta_query = ['relation' => 'AND'];
        }

        if (!empty($_GET['start_date'])) {
            $meta_query[] = [
                'key' => 'date_reservation',
                'value' => date('Ymd', strtotime($_GET['start_date'])),
                'compare' => '>=',
                'type' => 'DATETIME',
            ];
        }
        if (!empty($_GET['end_date'])) {
            $meta_query[] = [
                'key' => 'date_reservation',
                'value' => date('Ymd', strtotime($_GET['end_date'])),
                'compare' => '<=',
                'type' => 'DATETIME',
            ];
        }

        $args = [
            'post_type' => 'pro_reservations',
            'numberposts' => -1,
        ];

        if (isset($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        $posts = get_posts($args);

        $data = [];
        $i = 0;
        foreach ($posts as $post) {

            $tables_id = get_post_meta($post->ID, 'tables_id', true);
            $room_id = get_post_meta($tables_id, 'room_id', true);

            if (!empty($_GET['statsreservation'])) {
                $statsr = intval($_GET['statsreservation']);
                if ($statsr == 91 && empty($tables_id)) continue;
                if ($statsr == 99 && !empty($tables_id)) continue;
            }

            $data[$i]['id'] = $post->ID;
            $data[$i]['title'] = get_the_title($post->ID);
            $data[$i]['tables'] = [
                'id' => $tables_id,
                'title' => get_the_title($tables_id),
                'total_places' => get_post_meta($tables_id, 'total_places', true),
                'availability' => get_post_meta($tables_id, 'availability', true),
                'room' => [
                    'id' => get_post_meta($room_id, 'room_id', true),
                    'title' => get_the_title($room_id),
                ],
            ];
            $data[$i]['user'] = [
                'name' => get_post_meta($post->ID, 'user_name', true),
                'lastname' => get_post_meta($post->ID, 'user_lastname', true),
                'email' => get_post_meta($post->ID, 'user_email', true),
                'telephone' => get_post_meta($post->ID, 'telephone', true),
            ];
            $data[$i]['number_people'] = get_post_meta($post->ID, 'number_people', true);
            $data[$i]['note'] = get_post_meta($post->ID, 'note', true);
            $data[$i]['intern_note'] = get_post_meta($post->ID, 'intern_note', true);
            $data[$i]['date_reservation'] = date('d.m.Y', strtotime(get_post_meta($post->ID, 'date_reservation', true)));
            $data[$i]['time_reservation_from'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_from', true)));
            $data[$i]['time_reservation_to'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_to', true)));
            $data[$i]['exclusive_option'] = get_post_meta($post->ID, 'exclusive_option', true);
            $data[$i]['status_reservation'] = get_post_meta($post->ID, 'status_reservation', true);
            $i++;
        }

        return $data;
    }

    public function findById($id)
    {
        $post = get_post($id);

        $tables_id = get_post_meta($post->ID, 'tables_id', true);
        $room_id = get_post_meta($tables_id, 'room_id', true);

        $data['id'] = $post->ID;
        $data['title'] = get_the_title($post->ID);
        $data['tables'] = [
            'id' => $tables_id,
            'title' => get_the_title($tables_id),
            'room' => [
                'id' => $room_id,
                'title' => get_the_title($room_id),
                'total_places' => get_post_meta($room_id, 'total_places', true),
                'availability' => get_post_meta($room_id, 'availability', true),
            ],
        ];
        $data['user'] = [
            'name' => get_post_meta($post->ID, 'user_name', true),
            'lastname' => get_post_meta($post->ID, 'user_lastname', true),
            'email' => get_post_meta($post->ID, 'user_email', true),
            'telephone' => get_post_meta($post->ID, 'telephone', true),
        ];
        $data['number_people'] = get_post_meta($post->ID, 'number_people', true);
        $data['note'] = get_post_meta($post->ID, 'note', true);
        $data['intern_note'] = get_post_meta($post->ID, 'intern_note', true);
        $data['date_reservation'] = date('d.m.Y', strtotime(get_post_meta($post->ID, 'date_reservation', true)));
        $data['time_reservation_from'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_from', true)));
        $data['time_reservation_to'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_to', true)));
        $data['exclusive_option'] = get_post_meta($post->ID, 'exclusive_option', true);
        $data['status_reservation'] = get_post_meta($post->ID, 'status_reservation', true);

        return $data;
    }

    public function reservationsAnalytics()
    {
        if (!empty($_GET['date'])) {
            $meta_query = ['relation' => 'AND'];
        }

        if (!empty($_GET['date'])) {
            $meta_query[] = [
                'key' => 'date_reservation',
                'value' => date('Ymd', strtotime($_GET['date'])),
                'compare' => 'DATETIME',
            ];
        }

        $args = [
            'post_type' => 'pro_reservations',
            'numberposts' => -1,
        ];

        if (isset($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        $posts = get_posts($args);

        $data = [];
        $i = 0;
        foreach ($posts as $post) {
            $tables_id = get_post_meta($post->ID, 'tables_id', true);
            $room_id = get_post_meta($tables_id, 'room_id', true);

            $data[$i]['id'] = $post->ID;
            $data[$i]['title'] = get_the_title($post->ID);
            $data[$i]['tables'] = [
                'id' => $tables_id,
                'title' => get_the_title($tables_id),
                'total_places' => get_post_meta($tables_id, 'total_places', true),
                'availability' => get_post_meta($tables_id, 'availability', true),
                'room' => [
                    'id' => get_post_meta($room_id, 'room_id', true),
                    'title' => get_the_title($room_id),
                ],
            ];
            $data[$i]['user'] = [
                'name' => get_post_meta($post->ID, 'user_name', true),
                'lastname' => get_post_meta($post->ID, 'user_lastname', true),
                'email' => get_post_meta($post->ID, 'user_email', true),
                'telephone' => get_post_meta($post->ID, 'telephone', true),
            ];
            $data[$i]['country'] = get_post_meta($post->ID, 'country', true);
            $data[$i]['number_people'] = get_post_meta($post->ID, 'number_people', true);
            $data[$i]['note'] = get_post_meta($post->ID, 'note', true);
            $data[$i]['intern_note'] = get_post_meta($post->ID, 'intern_note', true);
            $data[$i]['date_reservation'] = date('d.m.Y', strtotime(get_post_meta($post->ID, 'date_reservation', true)));
            $data[$i]['time_reservation_from'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_from', true)));
            $data[$i]['time_reservation_to'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_to', true)));
            $data[$i]['time_from'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_from', true)));
            $data[$i]['time_to'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_to', true)));
            $data[$i]['exclusive_option'] = get_post_meta($post->ID, 'exclusive_option', true);
            $data[$i]['status_reservation'] = get_post_meta($post->ID, 'status_reservation', true);
            $i++;
        }

        return $data;
    }

    public function reservationByTableId($table_id)
    {
        $args = [
            'post_type' => 'pro_reservations',
            'numberposts' => -1,
            'meta_query' => [
                ['relation' => 'OR'],
                [
                    'key' => 'tables_id',
                    'value' => $table_id,
                    'compare' => 'LIKE',
                ]
            ]
        ];

        $posts = get_posts($args);

        $data = [];
        $i = 0;
        foreach ($posts as $post) {
            $tables_id = get_post_meta($post->ID, 'tables_id', true);
            $room_id = get_post_meta($tables_id, 'room_id', true);

            $data[$i]['id'] = $post->ID;
            $data[$i]['title'] = get_the_title($post->ID);
            $data[$i]['tables'] = [
                'id' => $tables_id,
                'title' => get_the_title($tables_id),
                'total_places' => get_post_meta($tables_id, 'total_places', true),
                'availability' => get_post_meta($tables_id, 'availability', true),
                'room' => [
                    'id' => get_post_meta($room_id, 'room_id', true),
                    'title' => get_the_title($room_id),
                ],
            ];
            $data[$i]['user'] = [
                'name' => get_post_meta($post->ID, 'user_name', true),
                'lastname' => get_post_meta($post->ID, 'user_lastname', true),
                'email' => get_post_meta($post->ID, 'user_email', true),
                'telephone' => get_post_meta($post->ID, 'telephone', true),
            ];
            $data[$i]['country'] = get_post_meta($post->ID, 'country', true);
            $data[$i]['number_people'] = get_post_meta($post->ID, 'number_people', true);
            $data[$i]['note'] = get_post_meta($post->ID, 'note', true);
            $data[$i]['intern_note'] = get_post_meta($post->ID, 'intern_note', true);
            $data[$i]['date_reservation'] = date('d.m.Y', strtotime(get_post_meta($post->ID, 'date_reservation', true)));
            $data[$i]['time_reservation_from'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_from', true)));
            $data[$i]['time_reservation_to'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_reservation_to', true)));
            $data[$i]['time_from'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_from', true)));
            $data[$i]['time_to'] = date('H:i', strtotime(get_post_meta($post->ID, 'time_to', true)));
            $data[$i]['exclusive_option'] = get_post_meta($post->ID, 'exclusive_option', true);
            $data[$i]['status_reservation'] = get_post_meta($post->ID, 'status_reservation', true);
            $i++;
        }

        return $data;
    }
}
