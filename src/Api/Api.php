<?php

namespace Moovly\Api;

use Moovly\Api\Auth;

class Api
{
    public $domain = 'moovly';

    public $version = "v1";

    protected $modules = [
        'auth' => Auth::class,
    ];

    public function __construct()
    {
        foreach ($this->modules as $name=>$module) {
            $this->{$name} = new $module($this->domain, $this->version);
        }
    }
}
