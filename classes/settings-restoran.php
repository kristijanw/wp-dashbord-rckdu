<?php

class SettingsRestoranClass extends Helpers
{

    public function getByMonth($month)
    {
        $optionMonth = json_decode(get_option('pro_month_option_' . $month . '', ''), true);

        return $optionMonth;
    }
}
