<?php

namespace Vnnit\Core\Permissions;

use Vnnit\Core\Contracts\CanCheckInUse;
use Vnnit\Core\Traits\Common\CheckInUse;
use Vnnit\Core\Traits\Entities\FullTextSearch;

class Role extends \Spatie\Permission\Models\Role implements CanCheckInUse
{
    use CheckInUse, FullTextSearch;

    protected $dispatchesEvents = [
        'deleting' => ModelDeleting::class,
    ];

    protected $searchable = [
        'name',
        'description'
    ];

    public function findTablesUsingModel()
    {
        return ['user_has_roles.role_id'];
    }

    public function getModelValue()
    {
        return $this->id;
    }
}
