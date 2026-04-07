<?php

namespace Sunnysideup\EcommerceMailchimpSignup\Model\Config;




use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;





/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  extends DataExtension (ignore case)
  * NEW:  extends DataExtension ...  (COMPLEX)
  * EXP: Check for use of $this->anyVar and replace with $this->anyVar[$this->owner->ID] or consider turning the class into a trait
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
class EcommerceMailchimpSignupDecoratorConfigFixes extends DataExtension
{

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD: private static $db
  * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    
    private static $table_name = 'EcommerceMailchimpSignupDecoratorConfigFixes';

    private static $db = array(
        "MailchimpSignupHeader" => "Varchar(50)",
        "MailchimpSignupIntro" => "Varchar(255)",
        "MailchimpSignupLabel" => "Varchar(30)"
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            "Root.Newsletter",
            array(
                new TextField("MailchimpSignupHeader", _t("EcommerceMailchimpSignup.HEADER", "Header")),
                new TextField("MailchimpSignupIntro", _t("EcommerceMailchimpSignup.INTRO", "Intro")),
                new TextField("MailchimpSignupLabel", _t("EcommerceMailchimpSignup.LABEL", "Label"))
            )
        );
    }
}

