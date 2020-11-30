<?php
namespace Wuxian\Rbac;

class Role extends CommonAbstract
{

    /**
     * 模型名字
     * @var array
     */
    public $modelName = 'role_model';

    /**
     * 模型驱动
     * @var array
     */
    public $modelDriver = 'RoleDriver';

    /**
     * 连接模型
     * @access public
     * @param  array         $options  配置数组
     * @return Driver
     */
    public function connect(array $options = [])
    {
        if(!empty($options[$this->modelName])){
            $name = md5(serialize($options));  
            $className = $options[$this->modelName];
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

}
