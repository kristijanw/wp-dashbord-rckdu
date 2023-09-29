<?php

class ResponsibilitiesClass extends Helpers
{

    public function loadAll()
    {
        if (!empty($_GET['date']) || !empty($_GET['user'])) {
            $meta_query = ['relation' => 'AND'];
        }

        if (!empty($_GET['date'])) {
            $meta_query[] = [
                'key' => 'date',
                'value' => date('Ymd', strtotime($_GET['date'])),
                'compare' => 'DATETIME',
            ];
        }
        if (!empty($_GET['user'])) {
            $meta_query[] = [
                'key' => 'user_id',
                'value' => sanitize_text_field($_GET['user']),
                'compare' => 'LIKE',
            ];
        }

        $args = [
            'post_type' => 'pro_responsibilities',
            'numberposts' => -1,
        ];

        if (isset($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        $posts = get_posts($args);

        $data = [];
        $i = 0;
        foreach ($posts as $post) {
            $user_id = get_post_meta($post->ID, 'user_id', true);
            $user = get_userdata($user_id);
            $room_id = get_post_meta($post->ID, 'rooms_id', true);
            $table_id = get_post_meta($post->ID, 'tables_id', true);

            $data[$i]['id'] = $post->ID;
            $data[$i]['title'] = get_the_title($post->ID);
            $data[$i]['date'] = date('Y-m-d', strtotime(get_post_meta($post->ID, 'date', true)));
            $data[$i]['list_responsibilities'] = apply_filters('acf_the_content', get_post_meta($post->ID, 'list_responsibilities', true));
            $data[$i]['user'] = [
                'id' => $user->ID,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ];
            $data[$i]['room'] = [
                'id' => $room_id,
                'title' => get_the_title($room_id),
            ];
            $data[$i]['table'] = [
                'id' => $table_id,
                'title' => get_the_title($table_id),
            ];
            $i++;
        }

        return $data;
    }

    public function findById($id)
    {
        $post = get_post($id);

        $user_id = get_post_meta($post->ID, 'user_id', true);
        $user = get_userdata($user_id);
        $room_id = get_post_meta($post->ID, 'rooms_id', true);
        $table_id = get_post_meta($post->ID, 'tables_id', true);

        $data['id'] = $post->ID;
        $data['title'] = get_the_title($post->ID);
        $data['date'] = date('Y-m-d', strtotime(get_post_meta($post->ID, 'date', true)));
        $data['list_responsibilities'] = apply_filters('acf_the_content', get_post_meta($post->ID, 'list_responsibilities', true));
        $data['user'] = [
            'id' => $user->ID,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ];
        $data['room'] = [
            'id' => $room_id,
            'title' => get_the_title($room_id),
        ];
        $data['table'] = [
            'id' => $table_id,
            'title' => get_the_title($table_id),
        ];

        return $data;
    }
}
