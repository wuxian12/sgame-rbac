<?php

declare(strict_types=1);

namespace Wuxian\Rbac;

class RbacFactory
{

    public static function getInstance(): Rbac
    {
        return new Rbac(FunctionUtil::getDefualtConfig());
    }

    
}
