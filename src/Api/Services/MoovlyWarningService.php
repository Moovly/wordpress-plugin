<?php

namespace Moovly\Api\Services;

use Exception;

class MoovlyWarningService
{
    use MoovlyApi;

    protected $isLoggedIn = false;

    protected $token = null;

    public function __construct($isLoggedIn, $token = null)
    {
        $this->isLoggedIn = $isLoggedIn;
        $this->token = $token;

        add_action('admin_notices', [$this, 'checkForWarnings']);
    }

    protected function decodedJwt()
    {
        if (!$this->token) {
            return null;
        }
        try {
            return (json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $this->token)[1])))));
        } catch (Exception $e) {
            return null;
        }
    }
    public function getDaysBetweenExpiry()
    {
        $decodedJwt = $this->decodedJwt();
        if (!$decodedJwt) {
            return null;
        }
        $exp = $decodedJwt->exp;
        $now = time();
        $datediff =  $exp - $now;
        $days = round($datediff / (60 * 60 * 24));
        return $days;
    }

    public function checkForWarnings()
    {


        $warnings = [];
        if ($this->isLoggedIn) {
            try {
                $creditsLeftResponse = $this->getMoovlyService()->getCreditAccount();

                if ($creditsLeftResponse) {
                    $creditsLeft = $creditsLeftResponse['total_balance'];
                    if ($creditsLeft < 5 && $creditsLeft > 0) {
                        $warnings[] = [
                            'text' => "<strong>Moovly Plugin: </strong>Only $creditsLeft automator credits left, contact your account manager or <a href=\"mailto:sales@moovly.com\">sales@moovly.com</a>",
                            'type' => 'warning'
                        ];
                    } else if ($creditsLeft === 0) {
                        $warnings[] = [
                            'text' => "<strong>Moovly Plugin: </strong>No automator credits left,  contact your account manager or <a href=\"mailto:sales@moovly.com\">sales@moovly.com</a>",
                            'type' => 'error'
                        ];
                    }
                }
            } catch (Exception $e) {
            }
            $daysBetween = $this->getDaysBetweenExpiry();
            if ($daysBetween !== null) {

                if ($daysBetween <= 0) {
                    $warnings[] = [
                        'text' => "<strong>Moovly Plugin: </strong>Your api token is expired, get a new token  <a target=\"_blank\" href=\"https://developer.moovly.com/developer-portal/personal-access-tokens\">here</a>",
                        'type' => 'error'
                    ];
                } else if ($daysBetween < 20) {
                    $warnings[] = [
                        'text' => "<strong>Moovly Plugin: </strong>Your api token will expire in $daysBetween days, get a new token  <a target=\"_blank\" href=\"https://developer.moovly.com/developer-portal/personal-access-tokens\">here</a>",
                        'type' => 'watning'
                    ];
                }
            }
            try {
                $license = $this->getMoovlyService()->getUserSubscription();
                if ($license->getExpired()) {
                    $warnings[] = [
                        'text' => "<strong>Moovly Plugin: </strong> Your subscription is expired",
                        'type' => 'error'
                    ];
                }
            } catch (Exception $e) {
            }
        }
        if (count($warnings) > 0) {
            foreach ($warnings as $warning) {
                echo '<div class="notice notice-' . $warning['type'] . ' is-dismissible"><p>' . $warning['text'] . '</p></div>';
            }
        }
    }
}
