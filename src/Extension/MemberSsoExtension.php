<?php

namespace Larsvanteeffelen\SilverStripeGoogleSSO\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\ValidationException;

class MemberSsoExtension extends Extension
{
    public function updateCMSFields(FieldList $fields): void
    {
        if ($this->owner->inGroup('google-administrators')) {
            $fields->makeFieldReadonly('Email');
            $fields->removeByName('Password');
            $fields->removeByName('Groups');
        }
    }

    public function onBeforeWrite(): void
    {
        if($this->owner->inGroup('google-administrators')) {
            if ($this->owner->isChanged('Email')) {
                throw new ValidationException('Email address cannot be changed for Google SSO Administrators.');
            }

            if ($this->owner->isChanged('Password')) {
                throw new ValidationException('Password changes are not allowed for Google SSO Administrators.');
            }
        }
    }
}
