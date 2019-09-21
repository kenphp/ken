<?php

namespace Ken;

use Closure;

use Ken\Container\Container;
use Ken\Exception\HttpException;
use Ken\Http\MiddlewareFactory;
use Ken\Http\ServerRequestHandler;

use Ken\Log\Logger;
use Ken\Router\Router;
use Ken\Utils\ArrayDot;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;

use Psr\Http\Message\ResponseInterface;

/**
 * KenPHP web application class
 * @property \Ken\Container\Container                   $container
 * @property \Ken\Utils\ArrayDot                        $configuration
 * @property \Psr\Log\LoggerInterface                   $logger
 * @property \Psr\Http\Message\ServerRequestInterface   $request
 * @property \Psr\Http\Message\ResponseInterface        $response
 * @property \Ken\Router\Router                         $router
 * @property \Ken\View\BaseEngine                       $view
 */
class Application {

    /**
     * @var \Ken\Container\Container
     */
    protected $container;

    /**
     * @var \Ken\Utils\ArrayDot
     */
    protected $configuration;

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @param array $configuration
     */
    public function __construct($configuration = []) {
        $this->container = new Container();
        $this->container->set('configuration', new ArrayDot($configuration));

        $this->init();
        self::$instance = $this;
    }

    /**
     * Initializes application's components
     */
    protected function init() {
        $this->container->set('logger', function($c) {
            $configuration = $c->get('configuration');

            $logger = new Logger($configuration['logger']);

            return $logger;
        });

        $this->container->set(MiddlewareFactory::class, function($c) {
            return new MiddlewareFactory();
        });

        $this->container->set('request', function($c) {
            $psr17Factory = new Psr17Factory();

            $creator = new ServerRequestCreator(
                $psr17Factory, // ServerRequestFactory
                $psr17Factory, // UriFactory
                $psr17Factory, // UploadedFileFactory
                $psr17Factory  // StreamFactory
            );

            return $creator->fromGlobals();
        });

        $this->container->set('router', function($c) {
            return new Router();
        });

        $this->container->set('response', function($c) {
            return new Response();
        });

        $this->registerErrorHandler();
    }

    /**
     * Registers error handler for this application
     */
    public function registerErrorHandler()
    {
        $whoops = new \Whoops\Run();
        $configuration = $this->getConfiguration();

        if (isset($configuration['debug']) && $configuration['debug']) {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        } else {
            $whoops->pushHandler(new \Whoops\Handler\CallbackHandler(function ($exception) {
                $this->logger->error($exception->getMessage(), compact('exception'));

                $request = $this->container->get('request');
                $response = $this->container->get('response');
                $errorInfo = ['message' => $exception->getMessage()];

                if (is_a($exception, HttpException::class)) {
                    $errorInfo['code'] = $exception->getCode();
                } else {
                    $errorInfo['code'] = 500;
                }

                $headerAccept = $request->getHeader('Accept');
                if (in_array('application/json', $headerAccept)) {
                    $response = $response->withStatus($errorInfo['code']);
                    $response->getBody()->write(json_encode($errorInfo));

                    $response = $response->withHeader('Content-Type', 'application/json');
                } else {
                    $view = $this->container->get('view');
                    $response = $view->render($response, 'error', $errorInfo);
                }

                $this->emitResponse($response);

                return \Whoops\Handler\Handler::DONE;
            }));
        }

        $whoops->register();
    }

    /**
     * @param  string $property Property name
     * @return mixed
     */
    public function __get($property) {
        $methodName = 'get' . ucfirst(strtolower($property));

        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName]);
        }

        if ($this->container->has($property)) {
            return $this->container->get($property);
        }

        return null;
    }

    /**
     * @return \Ken\Container\Container
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * @return static
     */
    public static function getInstance() {
        return self::$instance;
    }

    /**
     * @return \Ken\Utils\ArrayDot
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            $this->configuration = $this->container->get('configuration');
        }

        return $this->configuration;
    }

    /**
     * Runs application to handle request.
     */
    public function run()
    {
        $request = $this->request;
        $response = $this->response;
        $config = $this->configuration;

        $httpMethod = $request->getMethod();
        $pathInfo = $request->getUri()->getPath();
        if (empty($pathInfo)) {
            $pathInfo = '/';
        }

        $routeObject = $this->router->resolve($pathInfo, $httpMethod);
        if($routeObject) {
            $middlewareList = [];
            if (isset($routeObject['middleware'])) {
                $middlewareConfig = $config->get('middlewares');
                $middlewareCount = count($routeObject['middleware']);
                $middlewareNext = null;
                $middlewareFactory = $this->container->get(MiddlewareFactory::class);

                for ($i=$middlewareCount-1; $i >= 0; $i--) {
                    $middlewareName = $routeObject['middleware'][$i];
                    $middlewareClass = $middlewareConfig[$middlewareName];
                    $middlewareList[$i] = $middlewareFactory->createObject($middlewareClass, [
                        'response' => $response,
                        'next' => $middlewareNext,
                    ]);
                    $middlewareNext = $middlewareList[$i];
                }
            }

            $baseNamespace = $config->get('controllersNamespace');
            $handler = $this->convertCallbackToClosure($routeObject['handler'], $baseNamespace);
            $params = isset($routeObject['params']) ? $routeObject['params'] : [];
            $requestHandler = new ServerRequestHandler($response, $handler, $routeObject['params']);

            if (isset($middlewareList[0])) {
                $response = $middlewareList[0]->process($request, $requestHandler);
            } else {
                $response = $requestHandler->handle($request);
            }

        } else {
            throw new HttpException(404, "Route '{$pathInfo}' not found");
        }

        $this->emitResponse($response);
    }

    /**
     * Emits response to client
     * @param  ResponseInterface $response
     */
    protected function emitResponse(ResponseInterface $response) {
        $this->logger->flush();
        (new \Zend\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
    }

    /**
     * Converts callback to Closure.
     *
     * @param string|Closure $callback
     * @param string         $namespace
     *
     * @return Closure
     */
    protected function convertCallbackToClosure($callback, $namespace)
    {
        if ($callback instanceof Closure || is_callable($callback)) {
            return $callback;
        } elseif (is_string($callback)) {
            $namespace = rtrim($namespace, '\\').'\\';
            $arrCallback = explode('::', $callback);
            $isStaticCall = count($arrCallback) == 2;
            if ($isStaticCall) {
                $className = $namespace.$arrCallback[0];
                return [$className, $arrCallback[1]];
            } else {
                $arrCallback = explode(':', $callback);
                $className = $namespace.$arrCallback[0];
                // Must be replaced with a safer way to instantiate an object
                $obj = $this->container->get($className);
                return [$obj, $arrCallback[1]];
            }
        }
    }
}
