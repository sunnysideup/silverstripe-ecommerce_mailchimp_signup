<?php

namespace Sunnysideup\EcommerceMailchimpSignup\Model\Security;

use SilverStripe\Core\Extension;
use DrewM\MailChimp\MailChimp;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use Sunnysideup\EcommerceMailchimpSignup\Model\Forms\EcommerceMailchimpSignupDecoratorFormFixes;

class EcommerceMailchimpSignupMemberExtension extends Extension
{
    private static $db = [
        'SignedUpToMailchimp' => 'Boolean',
    ];

    /**
     * Store the user in MailChimp
     * @param array $mergeVars
     * @return boolean
     */
    public function subscribeToMailchimp($mergeVars = [])
    {
        $listID = Config::inst()->get(EcommerceMailchimpSignupDecoratorFormFixes::class, 'mailchimp_list_id');

        $mailChimp = $this->getMailChimpAPI();

        $result = $mailChimp->post(
            'lists/' . $listID . '/members',
            [
                'status' => 'subscribed',
                'email_address' => $this->owner->Email,
                'merge_fields' => $mergeVars + [
                    'FNAME' => $this->owner->FirstName,
                    'LNAME' => $this->owner->Surname,
                ],
            ]
        );
        if ($mailChimp->success()) {
            $this->owner->SignedUpToMailchimp = true;
            $this->owner->write();
            return true;
        } else {
            return false;
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function existsOnMailchimp()
    {
        if ($this->owner->SignedUpToMailchimp) {
            return true;
        }

        $mailChimp = $this->getMailChimpAPI();

        $listID = Config::inst()->get(EcommerceMailchimpSignupDecoratorFormFixes::class, 'mailchimp_list_id');

        $subscriberHash = $mailChimp->subscriberHash($this->owner->Email);

        $mailChimp->get(
            'lists/' . $listID . '/members/' . $subscriberHash
        );
        if ($mailChimp->success()) {
            $this->owner->SignedUpToMailchimp = true;
            $this->owner->write();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function updateOnMailchimp()
    {
        $mailChimp = $this->getMailChimpAPI();

        $listID = Config::inst()->get(EcommerceMailchimpSignupDecoratorFormFixes::class, 'mailchimp_list_id');

        $subscriberHash = $mailChimp->subscriberHash($this->owner->Email);

        $mailChimp->patch(
            'lists/' . $listID . '/members/' . $subscriberHash,
            [
                'merge_fields' => [
                    'FNAME' => $this->owner->FirstName,
                    'LNAME' => $this->owner->Surname,
                ],
            ]
        );
        if ($mailChimp->success()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * casted variable
     */
    private static $_mailchimp_api;

    /**
     * @return MailChimp
     */
    protected function getMailChimpAPI()
    {
        require_once(Director::baseFolder() . '/vendor/drewm/mailchimp-api/src/MailChimp.php');
        if (self::$_mailchimp_api) {
            //..
        } else {

            self::$_mailchimp_api = new MailChimp(Config::inst()->get(EcommerceMailchimpSignupDecoratorFormFixes::class, 'mailchimp_api_key'));
        }

        return self::$_mailchimp_api;
    }
}
