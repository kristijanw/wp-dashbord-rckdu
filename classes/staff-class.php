<?php

class StaffClass extends Helpers {

    public function loadAllStaff()
    {
        $roles = isset($_GET['roles']) ? $_GET['roles'] : null;

        $args = [
            'numberposts' => -1,
        ];

        if(!empty($roles)) {
            $args['role__in'] = [$roles];
        }

        $users = get_users($args);

        $data = [];
        $i = 0;
        foreach ($users as $user) {
            $data[$i]['id'] = $user->ID;
            $data[$i]['first_name'] = $user->first_name;
            $data[$i]['last_name'] = $user->last_name;
            $data[$i]['email'] = $user->user_email;
            $data[$i]['roles'] = $this->get_role_name($user->roles[0]);
            $data[$i]['role_for_drop'] = $user->roles[0];
            $data[$i]['color'] = $this->role_color($user->roles[0]);
            $i++;
        }

        return $data;
    }

    public function loadKonobari() {
        $args = [
            'numberposts' => -1,
            'role__in' => ['um_konobar']
        ];
        $users = get_users($args);

        $data = [];
        $i = 0;
        foreach ($users as $user) {
            $data[$i]['id'] = $user->ID;
            $data[$i]['first_name'] = $user->first_name;
            $data[$i]['last_name'] = $user->last_name;
            $data[$i]['email'] = $user->user_email;
            $data[$i]['roles'] = $this->get_role_name($user->roles[0]);
            $data[$i]['role_for_drop'] = $user->roles[0];
            $data[$i]['color'] = $this->role_color($user->roles[0]);
            $i++;
        }

        return $data;
    }
    
}