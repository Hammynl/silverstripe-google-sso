<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use League\OAuth2\Client\Provider\Google;

class GoogleLoginController extends Controller
{
    private static $url_handlers = [
        'test//$Action' => '$Action',
    ];
    private static $allowed_actions = [
        'login',
        'callback'
    ];

    public function login()
    {
        phpinfo();
        die();
        $provider = new Google([
            'clientId'     => getenv('GOOGLE_CLIENT_ID'),
            'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => $this->AbsoluteLink('callback')
        ]);

        $authUrl = $provider->getAuthorizationUrl();
        $this->getRequest()->getSession()->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    public function callback()
    {
        $provider = new Google([
            'clientId'     => getenv('GOOGLE_CLIENT_ID'),
            'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => $this->AbsoluteLink('callback')
        ]);

        if ($this->getRequest()->getSession()->get('oauth2state') !== $this->getRequest()->getVar('state')) {
            $this->getRequest()->getSession()->clear('oauth2state');
            return $this->httpError(400, 'Invalid state');
        }

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $this->getRequest()->getVar('code')
        ]);

        $user = $provider->getResourceOwner($token);
        $googleUserData = $user->toArray();

        // Handle the logic to either log in the user or create a new SilverStripe member
        $member = Member::get()->filter('Email', $googleUserData['email'])->first();

        if (!$member) {
            $member = Member::create();
            $member->FirstName = $googleUserData['given_name'];
            $member->Surname = $googleUserData['family_name'];
            $member->Email = $googleUserData['email'];
            $member->write();
        }

        Security::setCurrentUser($member);
        return $this->redirect(Security::config()->get('login_return_url'));
    }
}
