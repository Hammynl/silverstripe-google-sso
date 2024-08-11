<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Controller;

use Larsvanteeffelen\SilverStripeGoogleSSO\Service\AdminManagementService;
use Larsvanteeffelen\SilverStripeGoogleSSO\Service\GoogleAccountService;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;

class GoogleLoginController extends Controller
{
    private static $dependencies = [
        'adminManagementService' => '%$' . AdminManagementService::class,
        'googleAccountService' => '%$' . GoogleAccountService::class,
    ];

    private static $url_segment = 'google-login';

    private static $url_handlers = [
        'google-login//$Action' => '$Action',
    ];

    private static $allowed_actions = [
        'login',
        'callback'
    ];

    public function login(HTTPRequest $request)
    {
        $session = $request->getSession();
        $provider = $this->googleAccountService->getGoogleOAuthProvider($this->AbsoluteLink('callback'));

        $authUrl = $provider->getAuthorizationUrl();
        $session->set('oauth2state', $provider->getState());
        return $this->redirect($authUrl);
    }

    public function callback(HTTPRequest $request)
    {
        $session = $request->getSession();
        $provider = $this->googleAccountService->getGoogleOAuthProvider($this->AbsoluteLink('callback'));

        if ($session->get('oauth2state') !== $request->getVar('state')) {
            $session->clear('oauth2state');
            return $this->customise(['Error' => "State has been tampered with and declared invalid"])->renderWith('Security_failed');
        }

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->getVar('code')
        ]);
        $user = $provider->getResourceOwner($token);
        $googleUserData = $user->toArray();

        if(!$this->googleAccountService->isValidGoogleAccount($googleUserData)) {
            return $this->customise(['Error' => "This Google account is unauthorized"])->renderWith('Security_failed');

        }
        $this->adminManagementService->createOrLoginAdminUser(
            $googleUserData['given_name'],
            $googleUserData['family_name'],
            $googleUserData['email']
        );
        return $this->redirect('/admin/pages');
    }
}
