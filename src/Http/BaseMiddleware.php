<?php

namespace Ken\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Participant in processing a server request and response.
 *
 * An HTTP middleware component participates in processing an HTTP message:
 * by acting on the request, generating the response, or forwarding the
 * request to a subsequent middleware and possibly acting on its response.
 */
abstract class BaseMiddleware implements MiddlewareInterface {
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var \Psr\Http\Server\MiddlewareInterface
     */
    protected $_next;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Server\MiddlewareInterface|null $next
     */
    final public function __construct(ResponseInterface $response, MiddlewareInterface $next = null) {
        $this->response = $response;
        $this->_next = $next;
    }

    /**
     * Pass request to the next middleware. If there are no next middleware,
     * it will pass the request to the handler
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    final protected function next(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        if ($this->_next) {
            return $this->_next->process($request, $handler);
        }
        return $handler->handle($request);
    }

    /**
     * @inheritDoc
     */
    abstract public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
