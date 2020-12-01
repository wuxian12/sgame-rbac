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
        if(empty($config)){
            $config = FunctionUtil::getDefualtConfig();
        }
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
    	return FunctionUtil::getTree($this->permissionAll([['is_web', '=', 1]],$adminId));
    }
    //获取所有权限
    public function permissionList(array $data = []) :array
    {
    	return FunctionUtil::getTree($this->superPermission($data));
    }

    //添加权限
    public function addPermission(array $data) :int
    {
    	return Permission::getInstance($this->getConfig())->addPermission($data);
    }
    //编辑权限
    public function editPermission(int $permissionId,array $data) :int
    {
    	return Permission::getInstance($this->getConfig())->editPermission($permissionId,$data);
    }
    //删除权限
    public function delPermission(array $permissionIds) :int
    {
    	return Permission::getInstance($this->getConfig())->delPermission($permissionIds);
    }
    //获取权限
    public function getPermissionInfo(int $permissionId):array
    {
    	$where[] = ['id', '=', $permissionId];
    	return Permission::getInstance($this->getConfig())->getPermissionInfo($where);
    }

    //获取所有角色
    public function roleAll():array
    {
    	return Role::getInstance($this->getConfig())->roleAll();
    }
    //角色列表
    public function roleList(int $pageSize, $where = []):array
    {
    	return Role::getInstance($this->getConfig())->roleList($pageSize, $where);
    }
    //添加角色
    public function addRole(array $data) :int
    {
    	return Role::getInstance($this->getConfig())->addRole($data);
    }
    //编辑角色
    public function editRole(int $roleId,array $data) :int
    {
    	return Role::getInstance($this->getConfig())->editRole($roleId,$data);
    }
    //删除角色
    public function delRole(array $roleIds):int
    {
    	return Role::getInstance($this->getConfig())->delRole($roleIds);
    }
    //获取角色
    public function getRoleInfo(int $roleId):array
    {
    	$where[] = ['id', '=', $roleId];
    	return Role::getInstance($this->getConfig())->getRoleInfo($where);
    }

    //用户列表
    public function adminList(int $pageSize, $where = []):array
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            return Admin::getInstance($this->getConfig())->getAdminListFour($pageSize,$where);
        }else{
            return Admin::getInstance($this->getConfig())->getAdminList($pageSize,$where);
        }
    	
    }
    //添加用户
    public function addAdmin(array $data):int
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            return Admin::getInstance($this->getConfig())->addAdminFour($data);
        }else{
            return Admin::getInstance($this->getConfig())->addAdmin($data);
        }
    	
    }
    //编辑用户
    public function editAdmin(int $adminId, array $data):int
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            return Admin::getInstance($this->getConfig())->editAdminFour($adminId,$data);
        }else{
            return Admin::getInstance($this->getConfig())->editAdmin($adminId,$data);
        }
    	
    }
    //删除用户
    public function delAdmin(array $adminIds):int
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            return Admin::getInstance($this->getConfig())->delAdminFour($adminIds);
        }else{
            return Admin::getInstance($this->getConfig())->delAdmin($adminIds);
        }
    	
    }
    //获取用户
    public function getAdminInfo(int $adminId):array
    {
        $config = $this->getConfig();
        $where[] = ['id', '=', $adminId];
        if($config['table_num'] == 4){
            return Admin::getInstance($this->getConfig())->getAdminInfoFour($where);
        }else{
            return Admin::getInstance($this->getConfig())->getAdminInfo($where);
        }
    	
    	
    }
	
    /**
     * 获取某个用户的权限列表
     * @param int $admin_id 用户id
     * @return array
     */
    public function permissionAll(array $where = [], int $admin_id) : array
    {
        //取出超级管理员
        $super = $this->getSuperId();
        if (in_array($admin_id, $super) || empty($admin_id)) { //超级管理员的权限
            $permission_arr = $this->superPermission($where);
        } else {
            $permission_arr = $this->permissionListByUserid($admin_id,$where);
        }
        return $permission_arr;
    }

    //超级管理员权限
    public function superPermission(array $where = [])
    {
        return Permission::getInstance($this->getConfig())->getPermissionList($where);
    }

    /**
     * 通过某个用户获取相应的权限
     * @param int $admin_id 用户id
     * @return array
     */
    public function permissionListByUserid(int $admin_id, array $where = []) : array
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            $role_id = Admin::getInstance($this->getConfig())->getRolleId($admin_id);
            $role_arr = empty($role_id) ? [] : [$role_id];
        }else{
            $role_arr = RoleAdmin::getInstance($this->getConfig())->roleIdByUserid($admin_id);
            if (empty($role_arr)) {
                return [];
            }
        }
        $permission_ids = RolePermission::getInstance($this->getConfig())->permissionIdByRoleids($role_arr);
        if (! empty($permission_ids)) {
            return Permission::getInstance($this->getConfig())->getPermissionList($where,$permission_ids);
        }
        return [];
    }


    //通过某角色获取相应的权限id
    public function getPermissionIdsByRoleId(int $role_id) : array
    {
        
        return RolePermission::getInstance($this->getConfig())->permissionIdByRoleids([$role_id]);
        
    }

    //新增角色权限
    public function addPermissionIdsRoleId(int $role_id, string $permissionIds) : int
    {
        return RolePermission::getInstance($this->getConfig())->addRolepermission($role_id,$permissionIds);
    }

    //通过某角色id获取相应用户id
    public function getAdminIdsByRoleId(int $role_id) : array
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            return Admin::getInstance($this->getConfig())->getAdminIdByRoleId($role_id);
        }else{
            return RoleAdmin::getInstance($this->getConfig())->adminIdsByRoleId($role_id);
        }
    	
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public function getRoleIdByUserid(int $admin_id) : array
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            return [Admin::getInstance($this->getConfig())->getRolleId($admin_id)];
        }else{
            return RoleAdmin::getInstance($this->getConfig())->roleIdByUserid($admin_id);
        }
        
    }

    
    /**
     * 通过某个用户获取相应的角色
     * @param int $manage_id 用户id
     * @return array
     */
    public function roleListByUserid(int $admin_id) : array
    {
        $config = $this->getConfig();
        if($config['table_num'] == 4){
            $role_id = Admin::getInstance($this->getConfig())->getRolleId($admin_id);
            $role_ids = empty($role_id) ? [] : [$role_id];
        }else{
            $role_ids = RoleAdmin::getInstance($this->getConfig())->roleIdByUserid($admin_id);
        }
        $data = [];
        //通过权限id找到相关的权限
        if (! empty($role_ids)) {
            $data = Role::getInstance($this->getConfig())->roleAll($role_ids);
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
        $permission_arr = $this->permissionAll([],$admin_id);
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
