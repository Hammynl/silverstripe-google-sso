<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Service;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google;
use SilverStripe\Core\Environment;

class GoogleAccountService
{

    public function getGoogleOAuthProvider(string $redirectUri): AbstractProvider
    {
        return new Google([
            'clientId'     => Environment::getEnv('GOOGLE_CLIENT_ID'),
            'clientSecret' => Environment::getEnv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => $redirectUri
        ]);
    }

    public function isValidGoogleAccount(array $googleUserData): bool
    {
        $allowedEmails = explode(",", Environment::getEnv('ALLOWED_EMAILS'));
        if(
            !array_key_exists('email_verified', $googleUserData)
            || !$googleUserData['email_verified']
            || empty($allowedEmails)
        )  {
            return false;
        }

        $userEmail = strtolower($googleUserData['email']);
        foreach ($allowedEmails as $allowedEmail) {
            if (fnmatch(strtolower($allowedEmail), $userEmail)) {
                return true;
            }
        }
        return false;
    }
}
