<?php

namespace Ken;

use Closure;

use Ken\Container\Container;
use Ken\Exception\HttpException;
use Ken\Log\Logger;
use Ken\Router\Router;
use Ken\Utils\ArrayDot;
use Ken\View\Engine\Plates;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;

/**
 * KenPHP web application class
 * @property \Ken\Container\Container                   $container
 * @property \Ken\Utils\Configuration                   $configuration
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
     * @var \Ken\Utils\Arr
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
        $this->container = new Container(['configuration' => $configuration]);
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

        $this->container->set('view', function($c) {
            $configuration = $c->get('configuration')['view'];
            $viewFunctions = isset($configuration['viewFunctions']) ? $configuration['viewFunctions'] : [];
            return new Plates($configuration['viewPath'], $viewFunctions);
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
                $this->logger->flush();

                $response = $this->container->get('response');
                $view = $this->container->get('view');

                if (is_a($exception, HttpException::class)) {
                    return $view->render($response, 'error', [
                        'code' => $exception->getCode(),
                        'message' => $exception->getMessage(),
                    ]);
                } else {
                    return $view->render($response, 'error', [
                        'code' => 500,
                        'message' => $exception->getMessage(),
                    ]);
                }
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
            $config = $this->container->get('configuration');
            $this->configuration = new ArrayDot($config);
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

        $httpMethod = $request->getMethod();
        $pathInfo = $request->getUri()->getPath();
        if (empty($pathInfo)) {
            $pathInfo = '/';
        }

        $routeObject = $this->router->resolve($pathInfo, $httpMethod);
        if($routeObject) {
            foreach ($routeObject['before'] as $before) {
                call_user_func($before, $request);
            }

            $baseNamespace = $this->configuration->get('controllersNamespace');
            $handler = $this->convertCallbackToClosure($routeObject['handler'], $baseNamespace);
            $response = call_user_func_array($handler, [$request, $response, $routeObject['params']]);

            foreach ($routeObject['after'] as $after) {
                call_user_func($after, $response);
            }
        }

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
