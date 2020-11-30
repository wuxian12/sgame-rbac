## 该组件是RBAC权限角色组件，适合各种php框架,方便扩展，例如角色表名，字段可以自由定义

##  所有的方法可以参照 接口RbacInterface,数据驱动可以自由添加

##  支持4张表的BRAC和5张表的RBAC

```php
		$obj = new Rbac($config); OR new RbacFactory();
        $data['identity'] = 'test/11,test/44';  //后端提供了接口
        $data['url'] = 'test/66';  //前端url
		$data['is_web'] = 1;  //是否是前端页面
        $data['name'] = '测试子类33';
        $data['parent_id'] = 12;
		//添加
        $res = $obj->addPermission($data);
		//编辑
        $res = $obj->editPermission(14,$data);
		//删除
        $res = $obj->delPermission([14]);
		//左侧边栏【只展示前端】
        $res = $obj->menu(1);
		//所有权限【包括前后端的】
        $res = $obj->permissionList();
		//单个权限信息
        $res = $obj->getPermissionInfo(16);
        
		
        // $data['name'] = '测试';
		//添加
        // //$res = $obj->addRole($data);
		//编辑
        // $res = $obj->editRole(10,$data);
		//删除
        //$res = $obj->delRole([10]);
		//列表
        //$res = $obj->roleList(1);
		//单个角色信息
        //$res = $obj->getRoleInfo(10);
		//所有角色
        //$res = $obj->roleAll();
		
		
        // $data['name'] = 'test';
        // $data['password'] = '12345678';
        // $data['role_id'] = 10;
		//添加
        //$res = $obj->addAdmin($data);
		//编辑
        //$res = $obj->editAdmin(15,$data);
		//删除
        //$res = $obj->delAdmin([15]);
		//用户列表
        //$res = $obj->adminList(3);
		//单个用户信息
        //$res = $obj->getAdminInfo(15);
		
		//添加角色权限
        //$res = $obj->addPermissionIdsRoleId(10,'10,12,13');
		//获取角色的权限id
        //$res = $obj->getPermissionIdsByRoleId(10);
		
		//判断用户是否有权限访问接口
        $res = $obj->permissionIsOk(14,'api');
```