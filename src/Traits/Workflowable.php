<?php

namespace LaravelWorkflow\Traits;

use Workflow;

trait Workflowable
{
    public function workflowApply($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->apply($this, $transition);
    }

    public function workflowCan($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->can($this, $transition);
    }

    public function workflowTransitions()
    {
        return Workflow::get($this)->getEnabledTransitions($this);
    }
}
