<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Controller;

use Larsvanteeffelen\SilverStripeGoogleSSO\Service\AdminManagementService;
use Larsvanteeffelen\SilverStripeGoogleSSO\Service\GoogleAccountService;
use League\OAuth2\Client\Provider\AbstractProvider;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;

class GoogleLoginController extends Controller
{
    private AbstractProvider $provider;

    private AdminManagementService $adminManagementService;

    private GoogleAccountService $googleAccountService;

    private static $url_segment = 'google-login';

    private static $url_handlers = [
        'google-login//$Action' => '$Action',
    ];

    private static $allowed_actions = [
        'login',
        'callback'
    ];
    public function __construct()
    {
        $this->adminManagementService = Injector::inst()->get(AdminManagementService::class);
        $this->googleAccountService = Injector::inst()->get(GoogleAccountService::class);
        $this->provider = $this->googleAccountService->getGoogleOAuthProvider($this->AbsoluteLink('callback'));
        parent::__construct();
    }

    public function login(HTTPRequest $request)
    {
        $session = $request->getSession();

        $authUrl = $this->provider->getAuthorizationUrl();
        $session->set('oauth2state', $this->provider->getState());
        return $this->redirect($authUrl);
    }

    public function callback(HTTPRequest $request)
    {
        $session = $request->getSession();
        if ($session->get('oauth2state') !== $request->getVar('state')) {
            $session->clear('oauth2state');
            return $this->error("State has been tampered with and declared invalid");
        }

        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->getVar('code')
        ]);

        $googleUserData = $this->provider->getResourceOwner($token)?->toArray();
        if(!$this->googleAccountService->isValidGoogleAccount($googleUserData)) {
            return $this->error("This Google account is unauthorized");
        }

        $this->adminManagementService->createOrLoginSsoUser($googleUserData);
        return $this->redirect('/admin/pages');
    }

    private function error(string $error)
    {
        return $this->customise(['Message' => $error])->renderWith('Security_failed');
    }
}
