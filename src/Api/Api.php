<?php

namespace Moovly\Api;

use Moovly\Api\Routes\Job;
use Moovly\Api\Routes\Auth;
use Moovly\Api\Routes\Object;
use Moovly\Api\Routes\Account;
use Moovly\Api\Routes\Project;
use Moovly\Api\Routes\Template;

class Api
{
    public $domain = 'moovly';

    public $version = "v1";

    public $group = '';

    protected $routes = [
        'auth' => Auth::class,
        'accounts' => Account::class,
        'templates' => Template::class,
        'jobs' => Job::class,
        'objects' => Object::class,
        'projects' => Project::class,
    ];

    public function __construct()
    {
        $this->namespace = "{$this->domain}/{$this->version}/{$this->group}";
    }

    public function register()
    {
        foreach ($this->routes as $group=>$route) {
            $this->{$group} = new $route();
        }

        return $this;
    }
}
