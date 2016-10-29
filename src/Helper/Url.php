<?php

namespace Ken\Helper;

/**
 *
 */
class Url
{
    public static function to($url, $absolute = false)
    {
        if ($absolute) {
            $baseUrl = app()->request->getBaseUrl();

            $url = ltrim($url, '/');

            return $baseUrl.'/'.$url;
        } else {
            return $url;
        }
    }
}
