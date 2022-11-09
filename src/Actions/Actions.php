<?php

namespace Moovly\Actions;

use Moovly\Actions\Handlers\PostToTemplateActionHandler;

class Actions
{
    protected $actions = [
        // 'save_post' => [
        //     PostToTemplateActionHandler::class,
        // ],
    ];

    public function register()
    {
        foreach ($this->actions as $action => $handlers) {
            foreach ($handlers as $handler) {
                add_action($action, function (...$args) use ($handler) {
                    (new $handler(implode(',', $args)))->handle();
                });
            }
        }
    }
}
