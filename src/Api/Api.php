<?php

namespace Moovly\Api;

use Moovly\Api\Routes\Job;
use Moovly\Api\Routes\Auth;
use Moovly\Api\Routes\Asset;
use Moovly\Api\Routes\Account;
use Moovly\Api\Routes\Project;
use Moovly\Api\Routes\Template;
use Moovly\Api\Routes\PostVideo;
use Moovly\Api\Routes\Render;

class Api
{
    public $domain = 'moovly';

    public $version = "v1";

    public $group = '';

    public $namespace;

    protected $routes = [
        'auth' => Auth::class,
        'accounts' => Account::class,
        'templates' => Template::class,
        'jobs' => Job::class,
        'assets' => Asset::class,
        'projects' => Project::class,
        'postVideos' => PostVideo::class,
        'renders' => Render::class
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