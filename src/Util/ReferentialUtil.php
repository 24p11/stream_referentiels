<?php


namespace App\Util;


class ReferentialUtil
{
    public static function date($date = null)
    {
        return \DateTime::createFromFormat('Y-m-d', $date ?? new \DateTime());
    }
}