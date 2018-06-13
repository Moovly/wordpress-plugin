<?php

namespace Moovly\Api\Services;

use WP_Error;
use Moovly\Api\Api;
use Moovly\Api\Routes\Auth;
use Moovly\SDK\Client\APIClient;
use Moovly\SDK\Service\MoovlyService;
use Moovly\SDK\Exception\MoovlyException;

trait MoovlyApi
{
    protected $client;

    protected $moovly;

    public function registerMoovlyService()
    {
        $this->client = new APIClient;
        $this->moovly = new MoovlyService($this->client, Auth::getToken());
    }

    public function call($method, $arguments = null, $successCallback = null, $errorCallback = null)
    {
        if (is_callable($arguments)) {
            $errorCallback = $successCallback;
            $successCallback = $arguments;
        }

        try {
            $response = $this->moovly->{$method}($arguments);
            if (is_callable($successCallback)) {
                return $successCallback($response);
            }

            return $response;
        } catch (MoovlyException $e) {
            if (is_callable($errorCallback)) {
                return $errorCallback($e);
            }

            return new WP_Error($e->getCode(), $e->getMessage(), ['status' => $e->getCode()]);
        }
    }
}
