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
            $baseUrl = app()->request->baseUrl;

            $url = ltrim('/', $url);

            return sprintf('%s/%s', $baseUrl, $url);
        } else {
            return $url;
        }
    }
}
