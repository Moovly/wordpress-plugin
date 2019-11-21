<?php

namespace Moovly\Api\Services;

use WP_Error;
use Moovly\Api\Api;
use Moovly\Api\Routes\Auth;
use Moovly\SDK\Client\APIClient;
use Moovly\SDK\Service\MoovlyService;
use Moovly\SDK\Exception\MoovlyException;

/**
 * Trait MoovlyApi
 * @package Moovly\Api\Services
 *
 * Trait to be used until we introduce DI
 */
trait MoovlyApi
{
    /**
     * @var APIClient
     */
    private $client;

    /**
     * @var MoovlyService
     */
    private $moovly;

    /**
     * Creates a WordPress error object
     *
     * @param array|null|mixed $errorCallback
     * @param \Exception $e
     *
     * @return WP_Error
     */
    public function throwWPError($errorCallback, $e)
    {
        if (is_callable($errorCallback)) {
            return $errorCallback($e);
        }

        return new WP_Error($e->getCode(), $e->getMessage(), ['status' => $e->getCode()]);
    }

    /**
     * Lazily loads the MoovlyService and returns it
     *
     * @return MoovlyService
     */
    public function getMoovlyService()
    {
        if (is_null($this->moovly)) {
            $this->registerMoovlyServiceAndClient();
        }

        return $this->moovly;
    }

    /**
     * Lazily loads the MoovlyService and returns it
     *
     * @return APIClient
     */
    public function getMoovlyClient()
    {
        if (is_null($this->client)) {
            $this->registerMoovlyServiceAndClient();
        }

        return $this->client;
    }


    /**
     * Constructs the pseudo-DI for the MoovlyService
     */
    private function registerMoovlyServiceAndClient()
    {
        $this->client = new APIClient;
        $this->moovly = new MoovlyService($this->client, Auth::getToken());
    }
}
