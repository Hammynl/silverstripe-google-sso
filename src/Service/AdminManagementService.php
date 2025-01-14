<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Service;

use Larsvanteeffelen\SilverStripeGoogleSSO\Model\GoogleSsoProvider;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\Group;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class AdminManagementService {

    private IdentityStore $identityStore;

    public function __construct()
    {
        $this->identityStore = Injector::inst()->get(IdentityStore::class);
    }

    public function createOrLoginSsoUser(array $googleUserData): void
    {
        $this->validateGoogleUserData($googleUserData);

        $googleProvider = $this->getOrCreateGoogleProvider($googleUserData);
        $member = $this->getOrCreateMember($googleProvider, $googleUserData);

        $this->identityStore->logIn($member, true);
    }

    private function validateGoogleUserData(array $googleUserData): void
    {
        if (empty($googleUserData['sub']) || empty($googleUserData['email'])) {
            throw new \InvalidArgumentException('Google user data is missing required fields.');
        }
    }

    private function getOrCreateGoogleProvider(array $googleUserData): GoogleSsoProvider
    {
        $googleProvider = GoogleSsoProvider::get()->filter(['Sub' => $googleUserData['sub']])->first();

        if ($googleProvider && (!$googleProvider->Member() || !$googleProvider->Member()->exists())) {
            $googleProvider->delete();
            $googleProvider = null;
        }

        if (!$googleProvider) {
            $googleProvider = GoogleSsoProvider::create();
            $googleProvider->Sub = $googleUserData['sub'];
        }
        $googleProvider->PictureUrl = $googleUserData['picture'];
        $googleProvider->write();

        return $googleProvider;
    }

    private function getOrCreateMember(GoogleSsoProvider $googleProvider, array $googleUserData): Member
    {
        $member = $googleProvider->Member();
        if (!$member || !$member->exists()) {
            $member = Member::get()->filter(['Email' => $googleUserData['email']])->first() ?? Member::create();
            $member->FirstName = $googleUserData['given_name'];
            $member->Surname = $googleUserData['family_name'];
            $member->Email = $googleUserData['email'];
            $member->generateRandomPassword(128);
            $member->write();

            $googleProvider->MemberID = $member->ID;
            $googleProvider->write();
        }

        $ssoGroup = $this->createOrGetSsoGroup();
        if (!$ssoGroup->Members()->byID($member->ID)) {
            $ssoGroup->Members()->add($member);
        }

        return $member;
    }

    private function createOrGetSsoGroup(): Group
    {
        $ssoGroup = Group::get()->filter('Code', 'google-administrators')->first();
        if (!$ssoGroup) {
            $ssoGroup = Group::create();
            $ssoGroup->Title = 'Google SSO Administrators';
            $ssoGroup->Code = 'google-administrators';
            $ssoGroup->Locked = true;
            $ssoGroup->write();
            Permission::grant($ssoGroup->ID, 'ADMIN');
        }

        return $ssoGroup;
    }
}
