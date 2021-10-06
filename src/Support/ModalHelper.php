<?php

namespace Vnnit\Core\Support;

use Vnnit\Core\Facades\Common;

class ModalHelper
{
    public function start($modal)
    {
        return view(Common::getViewName('modal.modal-start'), compact('modal'))->render();
    }

    public function end()
    {
        return view(Common::getViewName('modal.modal-end'))->render();
    }
}
