<?php

class TablesClass extends Helpers {

    public function loadTables()
    {
        if(!empty($_GET['room'])) {
            $meta_query = ['relation' => 'OR'];
            $meta_query[] = [
                'key' => 'room_id',
                'value' => sanitize_text_field($_GET['room']),
                'compare' => 'LIKE',
            ];
        }

        $args = [
            'post_type' => 'pro_tables',
            'numberposts' => -1,
        ];

        if(isset($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        $posts = get_posts($args);
        
        $data = [];
        $i = 0;
        foreach ($posts as $post) {
            $room_id = get_post_meta($post->ID, 'room_id', true);

            $data[$i]['id'] = $post->ID;
            $data[$i]['title'] = get_the_title($post->ID);
            $data[$i]['room_id'] = $room_id;
            $data[$i]['room_title'] = get_the_title($room_id);
            $data[$i]['total_places'] = get_post_meta($post->ID, 'total_places', true);
            $data[$i]['availability'] = get_post_meta($post->ID, 'availability', true) == true ? 'Dostupan online' : 'Nije dotupan online';
            $data[$i]['availability_status'] = get_post_meta($post->ID, 'availability', true);
            $data[$i]['availability_color'] = get_post_meta($post->ID, 'availability', true) == true ? 'success' : 'danger';
            $i++;
        }

        return $data;
    }

}