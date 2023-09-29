<?php

class RoomsClass extends Helpers {

    public function loadAllRooms()
    {
        $args = [
            'post_type' => 'pro_rooms',
            'numberposts' => -1,
        ];
        $posts = get_posts($args);
        
        $data = [];
        $i = 0;
        foreach ($posts as $post) {
            $data[$i]['id'] = $post->ID;
            $data[$i]['title'] = get_the_title($post->ID);
            $i++;
        }

        return $data;
    }

}