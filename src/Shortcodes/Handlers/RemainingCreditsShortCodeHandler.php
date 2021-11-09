<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\RemainingCreditsShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class RemainingCreditsShortCodeHandler extends ShortcodeHandler
{
    use MoovlyApi, PermissionTrait;

    public function handle()
    {
        $error = $this->checkShortcodePermission(RemainingCreditsShortCodeFactory::$tag, true);
        if ($error) {
            return $error;
        }
        $credits = $this->getMoovlyService()->getRemainingCredits();

        if (!$credits) {
            return '-';
        }
        return $credits['total_left'];
    }
}
