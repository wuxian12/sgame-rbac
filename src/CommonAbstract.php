<?php
namespace Wuxian\Rbac;

abstract class CommonAbstract
{
    /**
     * 模型驱动
     * @var array
     */
    public $modelDriver = '';

    /**
     * 模型名字
     * @var array
     */
    public $modelName = '';
    /**
     * 模型实例
     * @var array
     */
    protected $instance = [];

    /**
     * 模型配置
     * @var array
     */
    protected $config = [];

    /**
     * 操作句柄
     * @var object
     */
    protected $handler;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->init($config);
    }

    public static function getInstance(array $config = [])
    {
        return new static($config);
    }

    /**
     * 连接模型
     * @access public
     * @param  array         $options  配置数组
     * @return Driver
     */
    public function connect(array $options = [])
    {
        if(!empty($options['modelDriver'])){
            $name = md5(serialize($options));  
            $className = $options['modelDriver'];
            $this->instance[$name] = new $className();
            
        }else{
            $name = md5(serialize($options).$this->modelDriver);
            if (!isset($this->instance[$name])) {
                $type = !empty($options['type']) ? $options['type'] : 'hyperf';
                $className = '\\Wuxian\\Rbac\\'.ucfirst($type).'\\'.$this->modelDriver;
                $this->instance[$name] = $className;
            }
        }
        
        return $this->instance[$name];
    }

    /**
     * 自动初始化模型
     * @access public
     * @param  array         $options  配置数组
     * @return Driver
     */
    public function init(array $options = [])
    {
        if (is_null($this->handler)) {

            $this->handler = $this->connect($options);
        }
        return $this->handler;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->init(), $method], $args);
    }

    

}
