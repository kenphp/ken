<?php

namespace Ken\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Handles a server request and produces a response.
 *
 * An HTTP request handler process an HTTP request in order to produce an
 * HTTP response.
 */
class ServerRequestHandler implements RequestHandlerInterface {

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var callable
     */
    protected $handler;

    /**
     * @var array
     */
    protected $handlerParams;

    /**
     * @param ResponseInterface $response
     * @param callable $handler
     * @param array $params
     */
    public function __construct(ResponseInterface $response, callable $handler, $params = []) {
        $this->response = $response;
        $this->handler = $handler;
        $this->handlerParams = $params;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface {
        return call_user_func($this->handler, $request, $this->response, $this->handlerParams);
    }
}
