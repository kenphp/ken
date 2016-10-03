<?php

namespace Ken;

use Ken\Exception\RouteNotFoundException;
use Ken\Exception\InvalidConfigurationException;
use Ken\Http\Input;
use Ken\Http\Request;
use Ken\Log\FileLogger;
use Ken\Routing\Router;
use Ken\View\ViewFactory;

/**
 * @property Ken\Log\BaseLogger $logger     Log handler used by the application
 * @property Ken\Routing\Router  $router     Route handler used by the application
 * @property Ken\Http\Request   $request    Request handler used by the application
 * @property Ken\Http\Input     $input      Input handler used by the application
 * @property Ken\View           $view       View handler used by the application
 * @property string             $basePath   Base path of the application
 * @property Ken\Ken            $instance   Instance of application
 */
class Application
{
    /**
     * @var Ken\Log\BaseLogger Log handler used by the application
     */
    protected $logger;

    /**
     * @var Ken\Routing\Router Route handler used by the application
     */
    protected $router;

    /**
     * @var Ken\Http\Request Request handler used by the application
     */
    protected $request;

    /**
     * @var Ken\Http\Input Input handler used by the application
     */
    protected $input;

    /**
     * @var Ken\View\BaseView View handler used by the application
     */
    protected $view;

    /**
     * @var string Base path of the application
     */
    protected $basePath;

    /**
     * @var string Application name
     */
    protected $name;

    /**
     * @var Ken\Ken Instance of application
     */
    protected static $instance;

    /**
     * @var string Time zone used in the application
     */
    protected $timeZone;

    public function __construct($config)
    {
        self::$instance = $this;
        $this->initProperty($config);
    }

    private function initProperty($config)
    {
        try {
            $this->setBasePath($config);

            if (!isset($config['log'])) {
                $this->initLogger();
            } else {
                $this->initLogger($config['log']);
            }

            $this->setTimeZone($config);
            $this->initRouter();
            $this->initRequest();
            $this->initInput();
            $this->initView($config);
            $this->name = isset($config['name']) ? $config['name'] : 'Ken Application';
        } catch (InvalidConfigurationException $exc) {
            $this->logger->error($exc->getMessage(), ['exception' => $exc]);
        }

        $this->setRoute($config);
    }

    /**
     * Get instance of application.
     *
     * @return Ken\Application
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    private function setBasePath($config)
    {
        if (!isset($config['basePath'])) {
            throw new InvalidConfigurationException("Parameter 'basePath' not found in configuration");
        }

        $this->basePath = $config['basePath'];
    }

    private function initLogger($loggerConfig = null)
    {
        if ($loggerConfig == null) {
            $this->logger = new FileLogger([
                'filepath' => $this->basePath.DIRECTORY_SEPARATOR.'kenphp.log',
                'enabledLevels' => ['error', 'warning'],
            ]);
        } else {
            if (!isset($loggerConfig['handler'])) {
                throw new InvalidConfigurationException("Parameter 'handler' not found in 'log' configuration");
            }

            $handler = $loggerConfig['handler'];

            if (!isset($loggerConfig['config'])) {
                throw new InvalidConfigurationException("Parameter 'config' not found in 'log' configuration");
            }

            $this->logger = new $handler($loggerConfig['config']);
        }
    }

    private function initRouter()
    {
        $this->router = new Router();
    }

    private function initRequest()
    {
        $this->request = new Request();
    }

    private function initInput()
    {
        $this->input = new Input($this->request);
    }

    private function initView($config)
    {
        if (!isset($config['view'])) {
            throw new InvalidConfigurationException("Parameter 'view' not found in configuration");
        }

        $this->view = ViewFactory::createViewEngine($config['view']);
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

    protected function setRoute($config)
    {
        if (!isset($config['routeFile'])) {
            throw new InvalidConfigurationException("Parameter 'routeFile' not found in configuration");
        }
        $router = $this->router;
        require_once $config['routeFile'];
    }

    public function run()
    {
        // try {
            $this->router->handleRequest($this->request);
        // } catch (RouteNotFoundException $exc) {
        //     $this->logger->error($exc->getMessage(), ['exception' => $exc]);
        //     require_once $this->basePath.DIRECTORY_SEPARATOR.'views/404.php';
        // }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            $methodName = 'get'.ucfirst($name);
            if (method_exists($this, $methodName)) {
                return $this->$methodName();
            }
        }
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getName()
    {
        return $this->name;
    }
}
