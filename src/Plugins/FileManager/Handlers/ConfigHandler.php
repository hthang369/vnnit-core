<?php

namespace Vnnit\Core\Plugins\FileManager\Handlers;

class ConfigHandler
{
    public function userField()
    {
        return auth()->id();
    }
}
