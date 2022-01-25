<?php

namespace Moovly\Actions\Handlers;

use Moovly\Templates;
use Moovly\SDK\Model\Job;
use Moovly\SDK\Model\Variable;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Factory\JobFactory;
use Moovly\SDK\Factory\ValueFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostToTemplateActionHandler
{
    use MoovlyApi;

    private $templateService;

    protected $post;

    protected $template = null;

    /**
     * PostToTemplateActionHandler constructor.
     *
     * @param string $postId
     */
    public function __construct($postId)
    {
        $this->post = get_post($postId);
        $this->templateService = new Templates();
        $this->template = $this->templateService->getPostTemplate();
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $postId = $this->post->ID;
        $isPost = $this->post->post_type === 'post';
        $isPublish = $this->post->post_status === 'publish';

        if ($this->template && $this->template->getId() && $isPost && $isPublish) {
            $this->dispatchMoovlyJob();
        }

        return $postId;
    }

    /**
     * @return void
     *
     * @throws
     */
    protected function dispatchMoovlyJob()
    {
        /** @var Job $job */
        $job = JobFactory::create([
            ValueFactory::create(
                (string) Uuid::uuid4(),
                "Moovly Wordpress plugin post hook for {$this->post->post_title}",
                $this->mapPostToTemplateVariables()
            )
        ])
            ->setTemplate($this->template)
            ->setOptions([])
        ;

        try {
            $job = $this->getMoovlyService()->createJob($job);

            $this->savePostTemplate($job);
        } catch (\Exception $e) {
            if ($e->getCode() === 500) {
                $this->savePostTemplate(
                    $job->setTemplate($this->template)->setStatus('Something went wrong on our side...')
                );

                return;
            }

            $this->savePostTemplate(
                $job->setTemplate($this->template)->setStatus('Failed due to incompatible template')
            );
        }
    }

    /**
     * @param Job $job
     */
    protected function savePostTemplate($job)
    {
        $jobValues = [
            'job_id' => $job->getId(),
            'job_status' => $job->getStatus(),
            'job_template' => $job->getTemplate()->getName(),
        ];

        if (!add_post_meta($this->post->ID, Templates::$post_templates_job_key, $jobValues, $unique = true)) {
            update_post_meta($this->post->ID, Templates::$post_templates_job_key, $jobValues);
        }
    }

    protected function mapPostToTemplateVariables()
    {
        /** @var array[] $postValues */
        $postValues = $this->preparePost();
        return collect($this->template->getVariables())->flatten()->mapWithKeys(function ($variable) use ($postValues) {
            /** @var Variable $variable */
            $hasVariable = key_exists($variable->getName(), $postValues);

            return [$variable->getId() => $hasVariable ? $postValues[$variable->getName()] : '',];
        })->reject(function ($variable) {
            return is_null($variable);
        })->toArray();
    }

    protected function preparePost()
    {
        return [
            'post_name' => $this->getNormalizedPostTitle(),
            'post_title' => $this->getNormalizedPostTitle(),
            'post_content' => $this->getNormalizedPostContent(),
            'featured_image' => $this->getFeaturedImageAsFile(),
        ];
    }

    private function getNormalizedPostTitle()
    {
        return $this->stringLimit(
            $this->post->post_title,
            $this->getTemplateVariableRequirementsFor(['post_name', 'post_title'])['maximum_length'],
        );
    }

    public function stringLimit($value, $limit = 100, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }

    private function getNormalizedPostContent()
    {
        $strippedContent = strip_shortcodes($this->post->post_content);
        $strippedContent = explode('<!--more-->', $strippedContent);

        foreach ($strippedContent as $content) {
            if (mb_strlen($content) <= $this->getTemplateVariableRequirementsFor('post_content')['maximum_length']) {
                return $content;
            }

            return $this->stringLimit(
                $content,
                $this->getTemplateVariableRequirementsFor('post_content')['maximum_length'] - 3,
            );
        }

        return $strippedContent[0];
    }

    private function getFeaturedImageAsFile()
    {
        $imageUrl = get_the_post_thumbnail_url($this->post);

        if (!$imageUrl) {
            return null;
        }

        $rawImg = imagecreatefromstring(file_get_contents($imageUrl));

        $extension = pathinfo($imageUrl)['extension'];
        switch ($extension) {
            case 'jpg':
                $mime = "image/jpeg";
                $ext = '.jpg';
                imagejpeg($rawImg, wp_upload_dir()['path'] . "/moovly_plugin_tmp_featured_image.jpg", 100);
                break;
            case 'png':
                $mime = "image/png";
                $ext = '.png';
                imagepng($rawImg, wp_upload_dir()['path'] . "/moovly_plugin_tmp_featured_image.png", 0);
                break;
            default:
                $mime = "image/jpeg";
                $ext = '.jpg';
                imagejpeg($rawImg, wp_upload_dir()['path'] . "/moovly_plugin_tmp_featured_image.jpg", 100);
        }

        $file = new UploadedFile(
            wp_upload_dir()['path'] . "/moovly_plugin_tmp_featured_image{$ext}",
            "moovly_plugin_tmp_featured_image{$ext}",
            $mime
        );

        try {
            $object = $this->getMoovlyService()->uploadAsset($file);

            unlink($file);

            return $object->getId();
        } catch (\Exception $e) {
            unlink($file);
        }
    }

    private function getTemplateVariableRequirementsFor($variableNames)
    {
        return collect($this->template->getVariables())->flatten()->first(
            function ($variable) use ($variableNames) {
                return collect(array_wrap($variableNames))->contains($variable->getName());
            },
            (new Variable())->setRequirements([
                'maximum_length' => 100,
            ])
        )->getRequirements();
    }
}
