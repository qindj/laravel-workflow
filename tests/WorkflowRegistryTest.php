<?php

namespace Test;

use LaravelWorkflow\WorkflowRegistry;

class WorkflowRegistryTest extends TestCase
{
    public function testOne()
    {
        $workflowRegistry = new WorkflowRegistry([]);
        $this->assertTrue(true);
    }
}