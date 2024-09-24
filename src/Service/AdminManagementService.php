<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Service;

use Larsvanteeffelen\SilverStripeGoogleSSO\Model\GoogleProvider;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\Group;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class AdminManagementService {

    public function createOrLoginSsoUser(array $googleUserData): void
    {
        $googleProvider = GoogleProvider::get()->filter([
            'Sub' => $googleUserData['sub'],
            'Email' => $googleUserData['email'],
        ])->first();

        // If Member connected to provider does not exist, delete & clear provider
        if($googleProvider && $googleProvider->Member()->ID === 0) {
            $googleProvider->delete();
            $googleProvider = null;
        }

        // Create provider if non existent
        if(!$googleProvider) {
            $googleProvider = GoogleProvider::create();
            $googleProvider->Sub = $googleUserData['sub'];
            $googleProvider->Email = $googleUserData['email'];

            // Get or create member
            $member = Member::get()->filter(['Email' => $googleUserData['email']])->first();
            if(!$member) {
                $member = Member::create();
                $member->FirstName = $googleUserData['given_name'];
                $member->Surname = $googleUserData['family_name'];
                $member->Email = $googleUserData['email'];
                $member->generateRandomPassword(128);
                $member->write();
            }

            $googleProvider->MemberID = $member->ID;
            $googleProvider->write();
        }

        // Create group for Google SSO
        $ssoGroup = Group::get()->filter('Code', 'google-administrators')->first();
        if (!$ssoGroup) {
            $ssoGroup = Group::create();
            $ssoGroup->Title = 'Google SSO Administrators';
            $ssoGroup->Code = 'google-administrators';
            $ssoGroup->Locked = true;
            $ssoGroup->write();
            Permission::grant($ssoGroup->ID, 'ADMIN');
        }

        // Add member to group
        $member = $googleProvider->Member();
        if (!$member->inGroup($ssoGroup)) {
            $ssoGroup->Members()->add($member);
        }

        // Programmatically log in user
        Injector::inst()->get(IdentityStore::class)->logIn($member, true);
    }
}
