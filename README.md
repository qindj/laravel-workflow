# Laravel workflow

Symfony workflow component wrapper for Laravel. Make your Eloquent models "workflowable".

* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
  * [Using "direct" permissions](#using-direct-permissions-see-below-to-use-both-roles-and-permissions)
  * [Using permissions via roles](#using-permissions-via-roles)
* [Unit Testing](#unit-testing)
* [Database](#database)
* [Extending](#extending)
* [Real Examples](#real-examples)

## Installation

```bash
composer require mguinea/laravel-workflow
```

### For laravel <= 5.4

Add a ServiceProvider to your providers array in `config/app.php`:

```php
<?php

'providers' => [
    LaravelWorkflow\WorkflowServiceProvider::class
];
```

Add the `Workflow` facade to your facades array:

```php
<?php
    'Workflow' => LaravelWorkflow\Facades\WorkflowFacade::class
```

## Configuration

Publish the config file

```bash
php artisan vendor:publish --provider="LaravelWorkflow\WorkflowServiceProvider"
```

Configure your workflow in `config/workflow.php`

```php
<?php

return [
    'straight'   => [
        'type'          => 'workflow', // or 'state_machine'
        'marking_store' => [
            'type'      => 'multiple_state',
            'arguments' => ['currentPlace']
        ],
        'supports'      => ['App\Post'],
        'places'        => ['draft', 'review', 'rejected', 'published'],
        'transitions'   => [
            'to_review' => [
                'from' => 'draft',
                'to'   => 'review'
            ],
            'publish' => [
                'from' => 'review',
                'to'   => 'published'
            ],
            'reject' => [
                'from' => 'review',
                'to'   => 'rejected'
            ]
        ],
    ]
];
```

Use the `Workflowable` inside supported classes

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use LaravelWorkflow\Traits\Workflowable;

class Post extends Model
{
  use Workflowable;

}
```

## Usage

```php
<?php

use App\Post;
use Workflow;

$post = Post::find(1);
$workflow = Workflow::get($post);
// if more than one workflow is defined for the BlogPost class
$workflow = Workflow::get($post, $workflowName);

$workflow->can($post, 'publish'); // False
$workflow->can($post, 'to_review'); // True
$transitions = $workflow->getEnabledTransitions($post);

// Apply a transition
$workflow->apply($post, 'to_review');
$post->save(); // Don't forget to persist the state

// Using the WorkflowTrait
$post->workflowCan('publish'); // True
$post->workflowCan('to_review'); // False

// Get the post transitions
foreach ($post->workflowTransitions() as $transition) {
    echo $transition->getName();
}

// Apply a transition
$post->workflowApply('publish');
$post->save();
```

### Use the events
Symofony package provides a list of events fired in places and transitions:

```php

```

You can subscribe to an event

```php
<?php

namespace App\Listeners;

use LaravelWorkflow\Events\GuardEvent;

class BlogPostWorkflowSubscriber
{
    /**
     * Handle workflow guard events.
     */
    public function onGuard(GuardEvent $event) {
        /** Symfony\Component\Workflow\Event\GuardEvent */
        $originalEvent = $event->getOriginalEvent();

        /** @var App\BlogPost $post */
        $post = $originalEvent->getSubject();
        $title = $post->title;

        if (empty($title)) {
            // Posts with no title should not be allowed
            $originalEvent->setBlocked(true);
        }
    }

    /**
     * Handle workflow leave event.
     */
    public function onLeave($event) {}

    /**
     * Handle workflow transition event.
     */
    public function onTransition($event) {}

    /**
     * Handle workflow enter event.
     */
    public function onEnter($event) {}

    /**
     * Handle workflow entered event.
     */
    public function onEntered($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'LaravelWorkflow\Events\GuardEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onGuard'
        );

        $events->listen(
            'LaravelWorkflow\Events\LeaveEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onLeave'
        );

        $events->listen(
            'LaravelWorkflow\Events\TransitionEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onTransition'
        );

        $events->listen(
            'LaravelWorkflow\Events\EnterEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onEnter'
        );

        $events->listen(
            'LaravelWorkflow\Events\EnteredEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onEntered'
        );
    }

}
```