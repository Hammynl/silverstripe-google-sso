<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Extension;

use SilverStripe\ORM\DataExtension;

class MemberSsoExtension extends DataExtension {

    private static $table_name = 'MemberSsoExtension';

    private static $db = [
        'Sub' => 'Varchar(255)',
        'PictureUrl' => 'Varchar(1024)',
    ];

    private static $indexes = [
        'Sub' => true,
    ];

    private static $hidden_fields = [
        'Sub',
        'PictureUrl',
    ];





}
