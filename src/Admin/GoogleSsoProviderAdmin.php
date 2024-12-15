<?php
namespace Larsvanteeffelen\SilverStripeGoogleSSO\Admin;

use Larsvanteeffelen\SilverStripeGoogleSSO\Model\GoogleSsoProvider;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridField;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

class GoogleSsoProviderAdmin extends ModelAdmin
{

    private static $managed_models = [
        GoogleSsoProvider::class,
    ];

    private static $url_segment = 'google-sso-providers';

    private static $menu_title = 'Google Administrators';

    private static $menu_icon_class = 'font-icon-lock';


    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        if ($this->modelClass === GoogleSsoProvider::class) {
            $gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass));

            if ($gridField instanceof GridField) {
                $gridField->getConfig()->addComponent(GridFieldSortableRows::create('SortOrder'));
            }
        }
        return $form;
    }
}
