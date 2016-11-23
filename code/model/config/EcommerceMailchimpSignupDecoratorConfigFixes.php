<?php


class EcommerceMailchimpSignupDecoratorConfigFixes extends DataExtension
{
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
