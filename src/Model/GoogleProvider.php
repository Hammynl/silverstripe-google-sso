<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class GoogleProvider extends DataObject
{
    private static $table_name = 'GoogleSsoProvider';

    private static $db = [
        'Sub' => 'Varchar(255)',
        'Email' => 'Varchar(255)'
    ];

    private static $has_one = [
        'Member' => Member::class
    ];

    private static $indexes = [
        'Sub' => true
    ];
}
