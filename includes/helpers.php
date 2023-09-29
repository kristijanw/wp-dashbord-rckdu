<?php

class Helpers {

    public function get_role_name($role)
    {
        $roleName = '';
        switch ($role) {
            case 'administrator':
                $roleName = 'Administrator';
                break;
            case 'um_voditelj-smjene':
                $roleName = 'Voditelj smjene';
                break;
            case 'um_konobar':
                $roleName = 'Konobar';
                break;
            default:
                $roleName = '';
                break;
        }
        return $roleName;
    }

    public function role_color($role)
    {
        $color = '';
        switch ($role) {
            case 'administrator':
                $color = 'danger';
                break;
            case 'um_voditelj-smjene':
                $color = 'warning';
                break;
            case 'um_konobar':
                $color = 'success';
                break;
            default:
                $color = '';
                break;
        }
        return $color;
    }

}