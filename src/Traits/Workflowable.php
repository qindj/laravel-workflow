<?php

namespace LaravelWorkflow\Traits;

use Workflow;

trait Workflowable
{
    private $workflow;

    public function workflow(string $workflowName = '')
    {
        $this->workflow = Workflow::get($this, $workflowName);
        return $this;
    }

    public function apply(string $transition = '')
    {
        return $this->workflow->apply($this, $transition);
    }

    public function can(string $transition = '')
    {
        return $this->workflow->can($this, $transition);
    }

    public function getEnabledTransitions()
    {
        return $this->workflow->getEnabledTransitions($this);
    }
}
