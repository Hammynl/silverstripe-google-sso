<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Model;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBInt;
use SilverStripe\Security\Member;

class GoogleSsoProvider extends DataObject
{
    private static $table_name = 'GoogleSsoProvider';

    private static $db = [
        'Sub' => 'Varchar(255)',
        'PictureUrl' => 'Varchar(1024)',
        'SortOrder' => DBInt::class,
    ];

    private static $default_sort = 'SortOrder';

    private static $has_one = [
        'Member' => Member::class
    ];

    private static $indexes = [
        'Sub' => true
    ];

    private static $summary_fields = [
        'FullName' => 'Name',
        'ImageThumbnail' => 'Picture'
    ];

    public function getImageThumbnail()
    {
        $image = Image::get()->where(["Title" => $this->Sub])->first() ?? Image::create();
        $image->setFromString(file_get_contents($this->PictureUrl), $this->Sub . ".png");
        $image->write();
        $image->publishFile();
        return $image->CMSThumbnail();
    }

    public function getFullName()
    {
        return $this->Member()->FirstName . ' ' . $this->Member()->LastName;
    }

    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    public function canEdit($member = null)
    {
        return false;
    }
}
