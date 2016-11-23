<?php


class EcommerceMailchimpSignupDecoratorFormFixes extends Extension
{

    /**
     *
     * @var string
     */
    private static $mailchimp_api_key = '';

    /**
     *
     * @var string
     */
    private static $mailchimp_list_id = '';

    /**
     * @param FieldList
     */
    public function updateFields(FieldList $fields)
    {
        $order = ShoppingCart::current_order();
        $member = $order->Member();
        if ($member) {
            if (! $member->existsOnMailchimp()) {
                $config = EcommerceDBConfig::current_ecommerce_db_config();
                if ($config->MailchimpSignupHeader) {
                    $fields->push(new HeaderField("MailchimpNewsletterSignupHeader", $config->MailchimpSignupHeader, 3));
                }
                if ($config->MailchimpSignupIntro) {
                    $fields->push(new LiteralField("MailchimpNewsletterSignupContent", "<p class=\"ecommerceMailchimpSignupContent\">".$config->MailchimpSignupIntro."</p>"));
                }
                $label = $config->MailchimpSignupLabel;
                if (!$label) {
                    $label = _t("EcommerceMailchimpSignupDecoratorFormFixes.JOIN", "Join");
                }
                $fields->push(new CheckboxField("MailchimpNewsletterSubscribeCheckBox", $label));
            }
        }
    }


    /**
     * Process the form
     *
     * @param type $data
     * @param type $form
     * @return type
     */
    public function onRawSubmit($data, $form, $order)
    {
        if (! empty($data['MailchimpNewsletterSubscribeCheckBox'])) {
            if ($data['MailchimpNewsletterSubscribeCheckBox'] == 1) {
                $member = $order->Member();
                if (! $member->existsOnMailchimp()) {
                    $member->subscribeToMailchimp();
                }
                $member->updateOnMailchimp();
            }
        }
    }
}
