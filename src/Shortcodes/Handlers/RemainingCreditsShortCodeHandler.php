<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Api\Services\MoovlyApi;

class RemainingCreditsShortCodeHandler extends ShortcodeHandler
{
    use MoovlyApi;

    public function handle()
    {
        $credits = $this->getMoovlyService()->getRemainingCredits();
        
        if (!$credits) {
            return '-';
        }
        return $credits['total_left'];
    }
}