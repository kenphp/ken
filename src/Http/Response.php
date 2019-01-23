<?php

namespace Ken\Http;

/**
 * @author Juliardi [ardi93@gmail.com]
 */
class Response extends \Nyholm\Psr7\Response {

    /**
     * Return redirect response
     * @param  string $url Destination URL
     * @return \Psr\Http\Message\{ResponseInterface
     */
    public function redirect($url) {
        return $this->withHeader('Location', $url);
    }

    /**
     * Return JSON response
     * @param  array  $array
     * @param  int $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function withJson($array, $status = 200) {
        $this->getBody()->write(json_encode($array));
        return $this
            ->withStatus($status)
            ->withHeader('Content-type', 'application/json');
    }
}
