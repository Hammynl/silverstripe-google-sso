<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\Group;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
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

        $authUrl = $provider->getAuthorizationUrl();
        $request->getSession()->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    public function callback(HTTPRequest $request): HTTPResponse
    {
        $session = $request->getSession();
        $provider = new Google([
            'clientId' => Environment::getEnv('GOOGLE_CLIENT_ID'),
            'clientSecret' => Environment::getEnv('GOOGLE_CLIENT_SECRET'),
            'redirectUri' => $this->AbsoluteLink('callback')
        ]);

        if ($session->get('oauth2state') !== $request->getVar('state')) {
            $session->clear('oauth2state');
            return $this->customise(
                ['Error' => 'State has been tampered with and declared invalid']
            )->renderWith('Security_failed');
        }

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->getVar('code')
        ]);

        $user = $provider->getResourceOwner($token);
        $googleUserData = $user->toArray();

        $allowed_emails = explode(",", Environment::getEnv('ALLOWED_EMAILS'));
        if($googleUserData['email_verified'] && in_array($googleUserData['email'], $allowed_emails)) {
            $this->createOrLoginAdminUser(
                $googleUserData['given_name'],
                $googleUserData['family_name'],
                $googleUserData['email']
            );
        } else {
            return $this->customise(
                ['Error' => 'This Google account is unauthorized']
            )->renderWith('Security_failed');
        }
        return $this->redirect('/admin/pages');
    }

    public function createOrLoginAdminUser(string $firstName, string $lastName, string $email): void
    {
        $adminGroup = Group::get()->filter('Code', 'administrators')->first();
        if (!$adminGroup) {
            $adminGroup = Group::create();
            $adminGroup->Title = 'Administrators';
            $adminGroup->Code = 'administrators';
            $adminGroup->write();
            Permission::grant($adminGroup->ID, 'ADMIN');
        }

        $member = Member::get()->filter('Email', $email)->first();
        if (!$member) {
            $member = Member::create();
            $member->FirstName = $firstName;
            $member->Surname = $lastName;
            $member->Email = $email;
            $member->write();
        }

        if (!$member->inGroup($adminGroup)) {
            $adminGroup->Members()->add($member);
        }
        Injector::inst()->get(IdentityStore::class)->logIn($member, true);
    }
}
