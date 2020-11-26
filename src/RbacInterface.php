<?php
interface RbacInterface
{
	//获取用户权限【左侧边栏】
    public function menu(int $adminId) :array;
	//获取所有权限
    public function permissionList() :array;
    //添加权限
    public function addPermission(array $data) :int;
    //编辑权限
    public function editPermission(int $permissionId,array $data) :int;
    //删除权限
    public function delPermission(array $permissionIds) :int;
    //获取权限
    public function getPermissionInfo(int $permissionId):array;

    //用户是否用户权限
    public function permissionIsOk(int $admin_id, string $identity) : bool;
    //用户获取角色
    public function getRoleIdByUserid(int $adminId):array;
    //角色获取权限
    public function getPermissionIdsByRoleId(int $roleId):arrray;


    //获取所有角色
    public function roleAll():array;
    //角色列表
    public function roleList(int $pageSize, array $where):array;
    //添加角色
    public function addRole(array $data) :int;
    //编辑角色
    public function editRole(int $roleId,array $data) :int;
    //删除角色
    public function delRole(array $roleIds):int;
    //获取角色
    public function getRoleInfo(int $roleId);:array;

    //用户列表
    public function adminList(int $pageSize, array $where):array;
    //添加用户
    public function addAdmin(array $data):int;
    //编辑用户
    public function editAdmin(int $adminId,array $data):int;
    //删除用户
    public function delAdmin(int $adminIds):int;
    //获取用户
    public function getAdminInfo(int $adminId):array;
}