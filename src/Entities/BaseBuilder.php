<?php
namespace Vnnit\Core\Entities;

use Illuminate\Database\Eloquent\Builder;
use Vnnit\Core\Traits\BuildPaginator;

class BaseBuilder extends Builder
{
    use BuildPaginator;
}
