<?php

namespace Moovly\Api;

use Moovly\Api\Routes\Auth;
use Moovly\Api\Routes\Account;

class Api
{
    public $domain = 'moovly';

    public $version = "v1";

    public $group = '';

    protected $routes = [
        'auth' => Auth::class,
        'accounts' => Account::class,
    ];

    public function __construct()
    {
        $this->namespace = "{$this->domain}/{$this->version}/{$this->group}";
    }

    public function registerRoutes()
    {
        foreach ($this->routes as $group=>$route) {
            $this->{$group} = new $route();
        }

        return $this;
    }
}
