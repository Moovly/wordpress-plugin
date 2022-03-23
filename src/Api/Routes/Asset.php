<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Model\Asset as MoovlyAsset;

class Asset extends Api
{
    use MoovlyApi;

    public $group = "objects";

    public function __construct()
    {
        parent::__construct();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {

        register_rest_route($this->namespace, '/upload', [
            'methods' => 'POST',
            'callback' => [$this, 'objectUpload'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, '/upload-image', [
            'methods' => 'POST',
            'callback' => [$this, 'image'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, '/upload-video', [
            'methods' => 'POST',
            'callback' => [$this, 'video'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function objectUpload($request)
    {
        try {
            $object = $this->getMoovlyService()->getUploadUrl($request->get_param('filename'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $object;
    }

    public function image($request)
    {
        return $this->uploadFile($request);
    }

    public function video($request)
    {
        return $this->uploadFile($request);
    }

    /**
     * @param $request
     *
     * @return array
     */
    private function uploadFile($request)
    {
        $file = collect($request->get_file_params())->map(function ($file) {
            move_uploaded_file($file['tmp_name'], $file['name']);
            return new \SplFileInfo($file['name']);
        })->first();

        try {
            $object = $this->getMoovlyService()->uploadAsset($file);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        unlink($file->getPathName());

        return [
            'id' => $object->getId(),
            'type' => $object->getType(),
            'status' => $object->getStatus(),
            'tags' => $object->getTags(),
            'description' => $object->getDescription(),
            'thumbnail' => $object->getThumbnailPath(),
            'assets' => $this->mapAssetsToResponse($object->getAssets()),
        ];
    }

    /**
     * @param MoovlyAsset[] $assets
     *
     * @return static
     */
    private function mapAssetsToResponse($assets)
    {
        return collect($assets)->map(function ($asset) {
            /** @var MoovlyAsset $asset */
            return [
                'type' => $asset->getType(),
                'version' => $asset->getVersion(),
                'source' => $asset->getSource(),
                'scale' => $asset->getScale(),
            ];
        });
    }
}