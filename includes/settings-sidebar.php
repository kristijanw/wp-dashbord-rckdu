<?php

class Prospekt_Sidebar {

    public function create_sidebar_menu($menu_location = '') {
        $current_user = wp_get_current_user();
        $check_menu = $this->switch_menu_by_role($current_user->roles[0]);
        $array_menu = wp_get_nav_menu_items($check_menu);

        // echo '<pre>';
        // var_dump($array_menu); 
        // echo '</pre>';

        // $menuhtml = '<ul >';
        //     foreach ($array_menu as $item) {
        //         $menuhtml .= '<li><a href="'.$item->url.'">'.$item->title.'</a></li>';
        //     }
        // $menuhtml .= '</ul>';
        
        // echo $menuhtml;
        wp_nav_menu([
            'menu' => $check_menu,
        ]);
    }

    public function switch_menu_by_role($role)
    {
        switch ($role) {
            case 'administrator':
                $menuid = 2;
                break;
            case 'voditelj':
                $menuid = 2;
                break;
            case 'konobar':
                $menuid = 2;
                break;
            default:
                $menuid = 2;
                break;
        }

        return $menuid;
    }

}



?>