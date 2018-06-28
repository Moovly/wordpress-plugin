<?php

namespace Moovly\Actions\Handlers;

use Moovly\Templates;
use Moovly\Api\Routes\Job;
use Illuminate\Support\Str;
use Moovly\SDK\Model\Variable;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Factory\JobFactory;
use Moovly\SDK\Factory\ValueFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostToTemplateActionHandler
{
    use MoovlyApi;

    protected $post;

    protected $template = null;

    public function __construct($postId)
    {
        $this->registerMoovlyService();
        $this->post = get_post($postId);
        $this->template = Templates::getPostTemplate();
    }

    public function handle()
    {
        return tap($this->post->ID, function () {
            if ($this->template && $this->template->getId() && $this->post->post_type === 'post') {
                $this->dispatchMoovlyJob();
            }
        });
    }

    protected function dispatchMoovlyJob()
    {
        $job = JobFactory::create([
            ValueFactory::create(
                Str::uuid(),
                "Moovly Wordpress plugin post hook for {$this->post->post_title}",
                $this->mapPostToTemplateVariables()
            )
        ])
        ->setTemplate($this->template)
        ->setOptions([])
        ;

        $this->moovlyApi('createJob', $job, function ($job) {
            $this->savePostTemplate($job);
        }, function ($error) use ($job) {
            if ($error->getCode() === 500) {
                $this->savePostTemplate($job->setTemplate($this->template)->setStatus('Something went wrong on our side...'));
            } else {
                $this->savePostTemplate($job->setTemplate($this->template)->setStatus('Failed due to incompatible template'));
            }
        });
    }

    protected function savePostTemplate($job)
    {
        $jobValues = [
            'job_id' => $job->getId(),
            'job_status' => $job->getStatus(),
            'job_template' => $job->getTemplate()->getName(),
        ];

        if (! add_post_meta($this->post->ID, Templates::$post_templates_job_key, $jobValues, $unique = true)) {
            update_post_meta($this->post->ID, Templates::$post_templates_job_key, $jobValues);
        }
    }

    protected function mapPostToTemplateVariables()
    {
        $postValues = $this->preparePost();
        return collect($this->template->getVariables())->flatten()->mapWithKeys(function ($variable) use ($postValues) {
            return [
                $variable->getId() => $postValues->get($variable->getName(), $default = '')
            ];
        })->toArray();
    }

    protected function preparePost()
    {
        return collect([
            'post_name' => $this->getNormalizedPostTitle(),
            'post_title' => $this->getNormalizedPostTitle(),
            'post_content' => $this->getNormalizedPostContent(),
            'featured_image' => $this->getFeaturedImageAsFile(),
        ]);
    }

    private function getNormalizedPostTitle()
    {
        return Str::limit($this->post->post_title, $this->getTemplateVariableRequirementsFor(['post_name', 'post_title'])['maximum_length'], $endWith = "...");
    }

    private function getNormalizedPostContent()
    {
        $strippedContent = strip_shortcodes($this->post->post_content);
        $strippedContent = explode('<!--more-->', $strippedContent);

        foreach ($strippedContent as $content) {
            if (Str::length($content) <= $this->getTemplateVariableRequirementsFor('post_content')['maximum_length']) {
                return $content;
            }
            return  Str::limit($content, $this->getTemplateVariableRequirementsFor('post_content')['maximum_length'] - 3, $endWith = '...');
        }
    }

    private function getFeaturedImageAsFile()
    {
        $imageUrl = get_the_post_thumbnail_url($this->post);
        if ($imageUrl) {
            $rawImg = imagecreatefromstring(file_get_contents($imageUrl));
            imagejpeg($rawImg, sys_get_temp_dir() . "moovly_plugin_tmp_featured_image.jpg", 100);

            $file =  new UploadedFile(sys_get_temp_dir() . "moovly_plugin_tmp_featured_image.jpg", 'moovly_plugin_tmp_featured_image.jpg', 'image/jpeg');

            return $this->moovlyApi('uploadAsset', $file, function ($object) {
                return $object->getId();
            }, function ($error) {
                $this->savePostTemplate($job->setTemplate($this->template)->setStatus('Something went wrong on our side...'));
            });
        }

        return null;
    }

    private function getTemplateVariableRequirementsFor($variableNames)
    {
        return collect($this->template->getVariables())->flatten()->first(
            function ($variable) use ($variableNames) {
                return collect(array_wrap($variableNames))->contains($variable->getName());
            },
            (new Variable())->setRequirements([
                'maximum_length' => 300,
            ])
        )->getRequirements();
    }
}
