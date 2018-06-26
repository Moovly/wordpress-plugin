<?php

namespace Moovly\Actions\Handlers;

use Moovly\Templates;
use Moovly\Api\Routes\Job;
use Illuminate\Support\Str;
use Moovly\SDK\Model\Variable;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Factory\JobFactory;
use Moovly\SDK\Factory\ValueFactory;

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
            if ($this->template && $this->post->post_type === 'post') {
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
            $this->savePostTemplate($job->setStatus('failed'));
        });
    }

    protected function savePostTemplate($job)
    {
        $jobValues = [
            'job_id' => $job->getId(),
            'job_status' => $job->getStatus(),
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
            'post_content' => $this->getNormalizedPostContent(),
        ]);
    }

    private function getNormalizedPostTitle()
    {
        return Str::limit($this->post->post_title, $this->getTemplateVariableRequirementsFor('post_title')['maximum_length'], $endWith = "...");
    }

    private function getNormalizedPostContent()
    {
        $strippedContent = strip_shortcodes($this->post->post_content);
        $content = str_replace('<!--more-->', '', $strippedContent);
        $limitedContent = Str::limit($content, $this->getTemplateVariableRequirementsFor('post_content')['maximum_length'] - 3, $endWith = '...');

        return $limitedContent;
    }

    private function getTemplateVariableRequirementsFor($variableName)
    {
        return collect($this->template->getVariables())->flatten()->first(
            function ($variable) use ($variableName) {
                return $variable->getName() === $variableName;
            },
            (new Variable())->setRequirements([
                'maximum_length' => 300,
            ])
        )->getRequirements();
    }
}
