<?php

declare (strict_types = 1);

namespace Wuxian\Rbac;

class Rbac implements RbacInterface
{
	//use RoleTrait;
	
	//rbac配置参数
	protected $config = [];

	public function __construct($config = [])
    {
        $this->setConfig($config);
    }

	//获取rbac配置
	public function getConfig() : array
	{
		return $this->config;
	}

	//设置rbac配置
	public function setConfig($config) :void
	{
		if(!is_array($config)){
			throw new \InvalidArgumentException('config must be array but you not afferent'.$config);
		}
		$this->config = $config;
	}

	//获取超级权限用户id
	public function getSuperId() : array
	{
		return $this->getConfig()['super'] ?? [];
	}

	//获取用户权限【左侧边栏】
    public function menu(int $adminId) :array
    {
    	return FunctionUtil::getTree($this->permissionAll());
    }
    //获取所有权限
    public function permissionList() :array
    {
    	return FunctionUtil::getTree(static::superPermission());
    }
    //添加权限
    public function addPermission(array $data) :int
    {
    	return Permission::addPermission($data);
    }
    //编辑权限
    public function editPermission(int $permissionId,array $data) :int
    {
    	$where[] = ['id', '=', $permissionId];
    	return Permission::editPermission($where,$data);
    }
    //删除权限
    public function delPermission(array $permissionIds) :int
    {
    	return Permission::delPermission($permissionIds);
    }
    //获取权限
    public function getPermissionInfo(int $permissionId):array
    {
    	$where[] = ['id', '=', $permissionId];
    	return Permission::getPermissionInfo($where);
    }

    //用户获取角色
    public function getRoleIdByAdminId(int $adminId):array;

    //角色获取权限id
    public function getPermissionIdsByRoleId(int $roleId):arrray
    {
        return RolePermission::permissionIdByRoleids([$roleId]);
    }

    //获取所有角色
    public function roleAll():array
    {
    	return Role::roleAll();
    }
    //角色列表
    public function roleList(int $pageSize, $where = []):array
    {
    	return Role::roleAll($pageSize, $where);
    }
    //添加角色
    public function addRole(array $data) :int
    {
    	return Role::addRole($data);
    }
    //编辑角色
    public function editRole(int $roleId,array $data) :int
    {
    	$where[] = ['id', '=', $roleId];
    	return Role::editRole($where,$data);
    }
    //删除角色
    public function delRole(array $roleIds):int
    {
    	return Role::delRole($roleIds);
    }
    //获取角色
    public function getRoleInfo(int $roleId);:array
    {
    	$where[] = ['id', '=', $roleId];
    	return Role::getRoleInfo($where);
    }

    //用户列表
    public function adminList(int $pageSize, $where = []):array
    {
    	return Admin::getAdminList($pageSize,$where);
    }
    //添加用户
    public function addAdmin(array $data):int
    {
    	return Admin::addAdmin($data);
    }
    //编辑用户
    public function editAdmin(int $adminId, array $data):int
    {
    	$where[] = ['id', '=', $adminId];
    	return Admin::editAdmin($where,$data);
    }
    //删除用户
    public function delAdmin(array $adminIds):int
    {
    	return Admin::delAdmin($adminIds);
    }
    //获取用户
    public function getAdminInfo(int $adminId):array
    {
    	$where[] = ['id', '=', $adminId];
    	return Admin::getAdminInfo($where);
    }
	
    /**
     * 获取某个用户的权限列表
     * @param int $admin_id 用户id
     * @return array
     */
    public function permissionAll(int $admin_id) : array
    {
        //取出超级管理员
        $super = $this->getSuperId();
        if (in_array($admin_id, $super)) { //超级管理员的权限
            $permission_arr = static::superPermission();
        } else {
            $permission_arr = static::permissionListByUserid($admin_id);
        }
        return $permission_arr;
    }

    //超级管理员权限
    public static function superPermission()
    {
        return Permission::getPermissionList();
    }

    /**
     * 通过某个用户获取相应的权限
     * @param int $admin_id 用户id
     * @return array
     */
    public static function permissionListByUserid(int $admin_id) : array
    {
        $role_arr = RoleAdmin::rolesIdByUserid($admin_id);
        if (empty($role_arr)) {
            return [];
        }
        $permission_ids = RolePermission::permissionIdByRoleids($role_arr);
        if (! empty($permission_ids)) {
            return Permission::getPermissionList($permission_ids);
        }
        return [];
    }

    //所有角色列表
    public function getRoleAll() : array
    {
        return Role::roleAll();
    }

    //通过某角色获取相应的权限
    public function permissionListByRole(int $role_id) : array
    {
        $permission_ids = RolePermission::permissionIdByRoleids([$role_id]);
        //通过权限id找到相关的权限
        if (! empty($permission_ids)) {
            return Permission::getPermissionList($permission_ids);
        }
        return [];
    }

    //通过某角色id获取相应用户id
    public function getAdminIdsByRoleId(int $role_id) : array
    {
    	return RoleAdmin::adminIdsByRoleId($role_id);
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public function getRoleIdByUserid(int $admin_id) : array
    {
        return RoleAdmin::roleIdByUserid($admin_id);
    }

    
    /**
     * 通过某个用户获取相应的角色
     * @param int $manage_id 用户id
     * @return array
     */
    public function roleListByUserid(int $admin_id) : array
    {
        //通过角色找到权限id
        $role_ids = RoleAdmin::roleIdByUserid($admin_id);
        $data = [];
        //通过权限id找到相关的权限
        if (! empty($role_ids)) {
            $data = Role::roleAll($role_ids);
        } else {
            if (in_array($admin_id, $this->getSuperId())) {
                $data[0]['name'] = '超级管理员';
            }
        }
        return $data;
    }

    /**
     * 判断当前用户是否拥有当前接口访问权限.
     * @param $arr
     * @return bool
     */
    public function permissionIsOk(int $admin_id, string $identity) : bool
    {
        //通过用户找到权限
        $permission_arr = static::permissionAll($admin_id);
        if (empty($permission_arr)) {
            return false;
        }
        $identitys = array_column($permission_arr, 'identity');
        $flag = 1; //判断是否有权限  1没有
        foreach ($identitys as $v) {
        	if(in_array($identity, \explode(',', $v))){
        		$flag = 2;
        		break;
        	}
        }
        if ($flag == 1) {
            return false;
        }else{
        	return true;
        }
        
    }
}
