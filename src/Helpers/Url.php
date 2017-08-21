<?php

namespace Ken\Helpers;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Url
{
    /**
     * Creates relative url.
     *
     * @param string $url
     * @param array  $params Assosiative array in format [key => value ] for GET parameters
     *
     * @return string
     */
    public static function create($url, array $params = array())
    {
        $url = self::addParams($url, $params);

        return $url;
    }

    /**
     * Creates absolute url.
     *
     * @param string $url
     * @param array  $params Assosiative array in format [key => value ] for GET parameters
     *
     * @return string
     */
    public static function createAbsolute($url, array $params = array())
    {
        $url = self::addParams($url, $params);
        $url = ltrim($url, '/');
        $baseUrl = app()->request->getBaseUrl();

        return $baseUrl.'/'.$url;
    }

    /**
     * Appends GET parameters to url.
     *
     * @param string $url
     * @param array  $params Assosiative array in format [key => value ] for GET parameters
     *
     * @return string
     */
    private static function addParams($url, array $params = array())
    {
        $cParams = count($params);

        if ($cParams > 0) {
            $url .= '?';
            $idx = 1;
            foreach ($params as $key => $value) {
                $url .= $key.'='.$value;
                if ($idx < $cParams) {
                    $url .= '&';
                }
                ++$idx;
            }
        }

        return $url;
    }
}
