<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\CMSSecurity;
use SilverStripe\Security\Group;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use League\OAuth2\Client\Provider\Google;

class GoogleLoginController extends Controller
{
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
        $provider = new Google([
            'clientId'     => Environment::getEnv('GOOGLE_CLIENT_ID'),
            'clientSecret' => Environment::getEnv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => $this->AbsoluteLink('callback')
        ]);

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile']
        ]);
        $request->getSession()->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    public function callback(HTTPRequest $request)
    {
        $provider = new Google([
            'clientId' => Environment::getEnv('GOOGLE_CLIENT_ID'),
            'clientSecret' => Environment::getEnv('GOOGLE_CLIENT_SECRET'),
            'redirectUri' => $this->AbsoluteLink('callback')
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

        $adminGroup = Group::get()->filter('Code', 'administrators')->first();

        if (!$adminGroup) {
            // Create the Administrators group if it doesn't exist
            $adminGroup = Group::create();
            $adminGroup->Title = 'Administrators';
            $adminGroup->Code = 'administrators';
            $adminGroup->write();
            Permission::grant($adminGroup->ID, 'ADMIN');
        }

        $member = Member::get()->filter('Email', $googleUserData['email'])->first();
        if (!$member) {
            $member = Member::create();
            $member->FirstName = $googleUserData['given_name'];
            $member->Surname = $googleUserData['family_name'];
            $member->Email = $googleUserData['email'];
            $member->write();
        }

        $adminGroup->Members()->add($member);
        Injector::inst()->get(IdentityStore::class)->logIn($member);
        return $this->redirect('/admin/pages');
    }
}
