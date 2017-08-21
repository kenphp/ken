<?php

namespace Ken;

use Exception;
use Ken\Exception\InvalidConfigurationException;
use Ken\Utils\Config;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Application
{
    private $basePath;

    private $baseUrl;

    private $name;

    private $timeZone;

    /**
     * @var \Ken\Utils\Config
     */
    private $config;

    /**
     * @var array
     */
    private $components;

    /**
     * @var \Ken\Application
     */
    private static $instance;

    public function __construct(array $config)
    {
        $this->components = array();
        $config = $this->getCoreComponentsConfig($config);
        $this->config = new Config($config);
        $this->init();
        self::$instance = $this;
    }

    /**
     * Retrieves core components configuration. Used internally to initialize core components.
     *
     * @param array $config
     *
     * @return array Configuration array containing core components configurations.
     */
    private function getCoreComponentsConfig($config)
    {
        $coreComponents = $this->coreComponents();
        $components = $config['components'];

        foreach ($coreComponents as $key => $value) {
            if (array_key_exists($key, $components)) {
                $config['components'][$key] = array_merge($config['components'][$key], $value);
            } else {
                $config['components'][$key] = $value;
            }
        }

        return $config;
    }

    protected function init()
    {
        try {
            $this->buildComponents();
            $this->applyConfig($this->config->all());
        } catch (Exception $e) {
            if (isset($this->logger)) {
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
            } else {
                error_log($e->getMessage());
                error_log($e->getTraceAsString());
            }
        }
    }

    /**
     * Builds components that has been configured in the configuration.
     *
     * @throws \Ken\Exception\InvalidConfigurationException
     */
    private function buildComponents()
    {
        $componentsConfig = $this->config->get('components');

        foreach ($componentsConfig as $key => $value) {
            if (isset($value['class'])) {
                $className = $value['class'];
                $component = $className::build($value);
                $this->registerComponent($key, $component);
            } else {
                throw new InvalidConfigurationException("Parameter 'class' is required in components configuration.");
            }
        }
    }

    /**
     * Registers a component if there are no other components
     * with the same name already registered.
     *
     * @param string $name       Name of the components
     * @param object $components An object to be registered
     *
     * @return bool True, if success or <br>
     *              False, if $component is not an object
     *              or if there are other component with the same name already registered
     */
    public function registerComponent($name, $component)
    {
        if (is_object($component)) {
            if (!isset($this->components[$name])) {
                $this->components[$name] = $component;

                return true;
            }
        }

        return false;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } elseif (isset($this->components[$name])) {
            return $this->components[$name];
        } else {
            return;
        }
    }

    public function __isset($name)
    {
        return property_exists($this, $name) || isset($this->components[$name]);
    }

    /**
     * Runs application to handle request.
     */
    public function run()
    {
        $this->router->handleRequest($this->request);
        $this->logger->flush();
    }

    /**
     * Registers application routes.
     *
     * @param string $routeFile Route file path
     */
    public function registerRoutes($routeFile)
    {
        $this->router->setRouteFile($routeFile);
        $this->router->registerRoutes();
    }

    /**
     * Applies configuration.
     */
    private function applyConfig($config)
    {
        try {
            $this->setBasePath($config);
            $this->setBaseUrl($this->request);
            $this->setName($config);
            $this->setTimeZone($config);
        } catch (InvalidConfigurationException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
    }

    private function setBasePath($config)
    {
        if (!isset($config['basePath'])) {
            throw new InvalidConfigurationException("Configuration 'basePath' is required");
        }

        $this->basePath = $config['basePath'];
    }

    private function setBaseUrl($request)
    {
        $this->baseUrl = $request->baseUrl;
    }

    private function setName($config)
    {
        if (!isset($config['name'])) {
            throw new InvalidConfigurationException("Configuration 'name' is required");
        }

        $this->name = $config['name'];
    }

    private function setTimeZone($config)
    {
        if (!isset($config['timeZone'])) {
            $this->timeZone = 'UTC';
        } else {
            $this->timeZone = $config['timeZone'];
        }
        date_default_timezone_set($this->timeZone);
    }

    /**
     * Returns core components configurations.
     */
    private function coreComponents()
    {
        return array(
            'logger' => ['class' => 'Ken\Log\Logger'],
            'request' => [
                'class' => 'Ken\Http\ServerRequest',
                'server' => $_SERVER,
                'get' => $_GET,
                'post' => $_POST,
                'files' => $_FILES,
            ],
            'router' => ['class' => 'Ken\Routing\Router'],
            'view' => ['class' => 'Ken\View\View'],
        );
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}
