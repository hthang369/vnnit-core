<?php

namespace Vnnit\Core\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Vnnit\Core\Contracts\CanCheckInUse;
use Vnnit\Core\Contracts\ModelEvent;
use Vnnit\Core\Entities\ModelInUseException;

class CheckModelInUseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModelEvent $event
     * @return void
     */
    public function handle(ModelEvent $event)
    {
        $model = $event->getModel();

        if (!($model instanceof CanCheckInUse)) {
            throw new \Exception('Model ' . get_class($model) . ' need to implement interface ' . CanCheckInUse::class . ' to use CheckModelInUseListener.');
        }

        if ($model->isInUse()) {
            throw new ModelInUseException();
        }
    }
}
