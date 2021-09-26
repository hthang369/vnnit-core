<?php

namespace Vnnit\Core\Permissions;

use Vnnit\Core\Entities\BaseModel;

class RoleHasPermissions extends BaseModel
{
    protected $table = 'role_has_permissions';

    protected $fillable = [
        'permission_id',
        'role_id',
    ];
}
