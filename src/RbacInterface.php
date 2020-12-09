<?php

namespace Wuxian\Rbac;

interface RbacInterface
{
    /**
     * 获取用户权限【左侧边栏】
     *
     * @param integer $adminId  用户id
     * @return array
     */
    public function menu(int $adminId) :array;
    /**
     * 获取所有权限
     *
     * @return array
     */
    public function permissionList(array $where, int $adminId) :array;
    /**
     * 未结构化的全部权限
     *
     * @param array $where 
     * @param integer $admin_id
     * @return array
     */
    public function permissionAll(array $where, int $admin_id) :array;
    /**
     * 添加权限
     *
     * @param array $data
     * @return integer
     */
    public function addPermission(array $data) :int;
    //编辑权限
    public function editPermission(int $permissionId,array $data) :int;
    //删除权限
    public function delPermission(array $permissionIds) :int;
    //获取权限
    public function getPermissionInfo(int $permissionId):array;

    /**
     * 用户是否拥有接口权限
     *
     * @param integer $adminId  用户id
     * @param string $identity  接口权限地址
     * @return boolean
     */
    public function permissionIsOk(int $adminId, string $identity) : bool;
    //用户获取角色id
    public function getRoleIdByUserid(int $adminId):array;
    //获取角色用户id
    public function getAdminIdsByRoleId(int $roleId):array;
    //用户获取角色
    public function roleListByUserid(int $adminId):array;
    //角色获取权限id
    public function getPermissionIdsByRoleId(int $roleId):array;
    /**
     * 新增角色权限
     *
     * @param integer $roleId  角色id
     * @param string $permissionIds  权限id，逗号分割 例如 1,2,3
     * @return integer
     */
    public function addPermissionIdsRoleId(int $roleId, string $permissionIds):int;
    /**
     * 删除角色的权限
     *
     * @param string $key  要删除表字段的key
     * @param array $roleIds  要删除值id
     * @return integer
     */
    public function delPermissionIdsRoleId(string $key,array $roleIds):int;
    /**
     * 删除角色用户表
     *
     * @param string $key  要删除表字段的key
     * @param array $roleIds  要删除值id
     * @return integer
     */
    public function delAdminIdsRoleIds(string $key,array $roleIds):int;
    /**
     * 新增用户角色
     *
     * @param integer $adminId  用户id
     * @param string $roleIds  角色id，逗号分割 例如 1,2,3
     * @return integer
     */
    public function addAdminIdRoleIds(int $adminId, string $roleIds):int;

    /**
     * 新增角色用户
     *
     * @param integer $RoleId  角色id
     * @param string $AdminIds  用户id，逗号分割 例如 1,2,3
     * @return integer
     */
    public function addRoleIdAdminIds(int $roleId, string $adminIds):int;


    //获取所有角色
    public function roleAll():array;
    /**
     * 角色列表
     *
     * @param integer $pageSize  每页数量
     * @param array $where
     * @return array
     */
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
    //通过用户名获取用户
    public function getAdminByName(string $name):array;
}