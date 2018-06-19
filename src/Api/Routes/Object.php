<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;

class Object extends Api
{
    use MoovlyApi;

    public $group = "objects";

    public function __construct()
    {
        parent::__construct();
        $this->registerMoovlyService();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/upload-image', [
            'methods' => 'POST',
            'callback' => [$this, 'image'],
        ]);

        register_rest_route($this->namespace, '/upload-video', [
            'methods' => 'POST',
            'callback' => [$this, 'video'],
        ]);
    }

    public function image($request)
    {
        return $this->uploadFile($request);
    }

    public function video($request)
    {
        return $this->uploadFile($request);
    }

    private function uploadFile($request)
    {
        $file = collect($request->get_file_params())->map(function ($file) {
            move_uploaded_file($file['tmp_name'], $file['name']);
            return new \SplFileInfo($file['name']);
        })->first();
        return $this->moovlyApi('uploadAsset', $file, function ($object) use ($file) {
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
        });
    }

    private function mapAssetsToResponse($assets)
    {
        return collect(array_wrap($assets))->map(function ($asset) {
            return [
                'type' => $asset->getType(),
                'version' => $asset->getVersion(),
                'source' => $asset->getSource(),
                'scale' => $asset->getScale(),
            ];
        });
    }
}
