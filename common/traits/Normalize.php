<?php
namespace common\traits;

trait Normalize {
    public function norm($number)
    {
        return preg_replace("/[^а-яёa-z0-9]/iu", '', $number);
    }
}