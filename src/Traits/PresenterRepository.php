<?php

namespace App\Traits;

use Laka\Core\Exceptions\RepositoryException;
use Laka\Core\Contracts\PresenterInterface;

trait PresenterRepository
{
    /**
     * @var bool
     */
    protected $skipPresenter = false;

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return $this->presenterClass;
    }

    /**
     * Set Presenter
     *
     * @param $presenter
     *
     * @return $this
     */
    public function setPresenter($presenter)
    {
        $this->makePresenter($presenter);

        return $this;
    }

    /**
     * @param null $presenter
     *
     * @return PresenterInterface
     * @throws RepositoryException
     */
    public function makePresenter($presenter = null)
    {
        $presenter = !is_null($presenter) ? $presenter : $this->presenter();

        if (!is_null($presenter)) {
            $presenterObject = is_string($presenter) ? resolve($presenter) : $presenter;

            if (!$presenterObject instanceof PresenterInterface) {
                throw new RepositoryException("Class {$presenter} must be an instance of Laka\\Core\\Contracts\\PresenterInterface");
            }

            return $presenterObject;
        }

        return null;
    }
}
