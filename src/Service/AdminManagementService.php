<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Service;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\Group;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class AdminManagementService {

    public function createOrLoginSsoUser(array $googleUserData): void
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

        $existingMember = Member::get()->filter('Email', $googleUserData['email'])->first();
        if($existingMember) {
            $existingMember->Sub = $googleUserData['sub'];
            $existingMember->write();
        }

        $member = Member::get()->filter('sub', $googleUserData['sub'])->first();
        if (!$member) {
            $member = Member::create();
            $member->Sub = $googleUserData['sub'];
            $member->PictureUrl = $googleUserData['picture'];
            $member->FirstName = $googleUserData['given_name'];
            $member->Surname = $googleUserData['family_name'];
            $member->Email = $googleUserData['email'];
            $member->generateRandomPassword(128);
            $member->write();
        }

        if (!$member->inGroup($ssoGroup)) {
            $ssoGroup->Members()->add($member);
        }

        Injector::inst()->get(IdentityStore::class)->logIn($member, true);
    }
}
