<?php

namespace Sunnysideup\EcommerceMailchimpSignup\Model\Security;

use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use Sunnysideup\EcommerceMailchimpSignup\Model\Forms\EcommerceMailchimpSignupDecoratorFormFixes;

/**
 * ### @@@@ START REPLACEMENT @@@@ ###
 * WHY: automated upgrade
 * OLD:  extends DataExtension (ignore case)
 * NEW:  extends DataExtension ...  (COMPLEX)
 * EXP: Check for use of $this->anyVar and replace with $this->anyVar[$this->owner->ID] or consider turning the class into a trait
 * ### @@@@ STOP REPLACEMENT @@@@ ###
 */
class EcommerceMailchimpSignupMemberExtension extends DataExtension
{
    /**
     * ### @@@@ START REPLACEMENT @@@@ ###
     * OLD: private static $db
     * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
     * ### @@@@ STOP REPLACEMENT @@@@ ###
     */
    private static $table_name = 'EcommerceMailchimpSignupMemberExtension';

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

        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: Config::inst()->get('
         * NEW: Config::inst()->get(' ...  (COMPLEX)
         * EXP: Check if you should be using Name::class here instead of hard-coded class.
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
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

        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: Config::inst()->get('
         * NEW: Config::inst()->get(' ...  (COMPLEX)
         * EXP: Check if you should be using Name::class here instead of hard-coded class.
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
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

        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: Config::inst()->get('
         * NEW: Config::inst()->get(' ...  (COMPLEX)
         * EXP: Check if you should be using Name::class here instead of hard-coded class.
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
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

            /**
             * ### @@@@ START REPLACEMENT @@@@ ###
             * WHY: automated upgrade
             * OLD: Config::inst()->get('
             * NEW: Config::inst()->get(' ...  (COMPLEX)
             * EXP: Check if you should be using Name::class here instead of hard-coded class.
             * ### @@@@ STOP REPLACEMENT @@@@ ###
             */
            self::$_mailchimp_api = new \DrewM\MailChimp\MailChimp(Config::inst()->get(EcommerceMailchimpSignupDecoratorFormFixes::class, 'mailchimp_api_key'));
        }
        return self::$_mailchimp_api;
    }
}
