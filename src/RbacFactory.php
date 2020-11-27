<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Wuxian\Rbac;

class RbacFactory
{

    public static function getInstance(): Rbac
    {
        return new Rbac(['type' => 'hyperf','modelDriver' => '','super' => [1],]);
    }

    
}
