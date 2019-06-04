<?php

namespace LaravelWorkflow\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class WorkflowFacade
 *
 * @package LaravelWorkflow\Facades
 */
class WorkflowFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'workflow';
    }
}
