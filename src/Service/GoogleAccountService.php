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
        $allowed_emails = explode(",", Environment::getEnv('ALLOWED_EMAILS'));
        return $googleUserData['email_verified'] && in_array($googleUserData['email'], $allowed_emails);
    }
}
