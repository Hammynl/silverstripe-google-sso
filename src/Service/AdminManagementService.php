<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Service;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\Group;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class AdminManagementService {

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
