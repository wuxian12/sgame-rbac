<?php

namespace Wuxian\Rbac;

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
    //用户获取角色id
    public function getRoleIdByUserid(int $adminId):array;
    //获取角色用户id
    public function getAdminIdsByRoleId(int $adminId):array;
    //用户获取角色
    public function roleListByUserid(int $adminId):array;
    //角色获取权限id
    public function getPermissionIdsByRoleId(int $role_id):array;
    //新增角色权限
    public function addPermissionIdsRoleId(int $roleId, string $permissionIds):int;


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
    public function getRoleInfo(int $roleId):array;

    //用户列表
    public function adminList(int $pageSize, array $where):array;
    //添加用户
    public function addAdmin(array $data):int;
    //编辑用户
    public function editAdmin(int $adminId,array $data):int;
    //删除用户
    public function delAdmin(array $adminIds):int;
    //获取用户
    public function getAdminInfo(int $adminId):array;
}