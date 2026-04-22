<?php

declare(strict_types=1);

namespace Sunnysideup\EcommerceMailchimpSignup\Model\Config;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class EcommerceMailchimpSignupDecoratorConfigFixes extends Extension
{
    private static $db = [
        'MailchimpSignupHeader' => 'Varchar(50)',
        'MailchimpSignupIntro' => 'Varchar(255)',
        'MailchimpSignupLabel' => 'Varchar(30)',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Newsletter',
            [
                TextField::create('MailchimpSignupHeader', _t('EcommerceMailchimpSignup.HEADER', 'Header')),
                TextField::create('MailchimpSignupIntro', _t('EcommerceMailchimpSignup.INTRO', 'Intro')),
                TextField::create('MailchimpSignupLabel', _t('EcommerceMailchimpSignup.LABEL', 'Label')),
            ]
        );
    }
}
