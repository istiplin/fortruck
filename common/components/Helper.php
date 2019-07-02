<?php

namespace common\components;

class Helper{
    public static function normNumber($number)
    {
        return preg_replace("/[^а-яёa-z0-9]/iu", '', $number);
    }
}
?>
