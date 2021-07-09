<?php
define('FRAME_BASE_PATH', __DIR__);
define('FRAME_START_TIME', microtime(true));
define('FRAME_START_MEMORY', memory_get_usage());

class App implements Psr\Container\ContainerInterface
{

    public $binding = [];   //绑定关系
    private static $instance; //这个类的实例
    protected $instances = []; //所有实例的存放

    private function __construct()
    {
        self::$instance = $this;    //APP类的实例
        $this->register();  //注册绑定
        $this->boot();      //注册之后  启动
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     * @throws \Psr\Container\ContainerExceptionInterface Error while retrieving the entry.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface  No entry was found for **this** identifier.
     */
    public function get(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        $instance = $this->binding[$abstract]['concrete']($this);
        if ($this->binding[$abstract]['is_singleton']) {
            $this->instances[$abstract] = $instance;
        }
        return $instance;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        // TODO: Implement has() method.
    }

    public static function getContainer()
    {
        return self::$instance ?? self::$instance = new self();
    }

    /**
     * @param $abstract
     * @param $concrete
     * @param bool $is_singleton
     */
    public function bind($abstract, $concrete, $is_singleton = false)
    {
        if (!$concrete instanceof \Closure) {
            $concrete = function ($app) use ($concrete) {
                return $app->build($concrete);
            };
        }
        $this->binding[$abstract] = compact('concrete', 'is_singleton');
    }

    protected function getDependencies($paramters)
    {
        $dependencies = []; //当前类所有依赖
        foreach ($paramters as $paramter) {
            if ($paramter->getClass()) {
                $dependencies[] = $this->get($paramter->getClass()->name);
            }
            return $dependencies;
        }
    }

    //解析依赖
    public function build($concrete)
    {
        $reflector = new ReflectionClass($concrete); //反射
        $construtor = $reflector->getConstructor(); //获取构造函数
        if (is_null($construtor)) {
            return $reflector->newInstance();
        }
        $dependencies = $construtor->getParameters();   //获取构造函数的参数
        $instances = $this->getDependencies($dependencies);     //当前类的所有实例化依赖
        return $reflector->newInstanceArgs($instances); //跟new类($instances)一样
    }


    protected function register()
    {
        $registers = [      //待绑定服务
            'response' => \core\Response::class,
            'router' => \core\RouteCollection::class,
            'pipeline' => \core\PipeLine::class,
            'config' => \core\Config::class,
            'db' => \core\Database::class,
        ];
        foreach ($registers as $name => $concrete) {
            $this->bind($name, $concrete, true);
        }
    }

    protected function boot()
    {
        app('config')->init();

        app('router')->group(['namespace' => 'App\\controller'], function ($router) {
            require_once FRAME_BASE_PATH . '/routes/web.php';
        });

        app('router')->group(['namespace' => 'App\\controller', 'prefix' => 'api'], function ($router) {
            require_once FRAME_BASE_PATH . '/routes/api.php';
        });

//        dd(app('router')->getRoutes());
    }
}