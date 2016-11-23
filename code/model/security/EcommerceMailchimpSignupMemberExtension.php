<?php



class EcommerceMailchimpSignupMemberExtension extends DataExtension
{
    private static $db = array(
        'SignedUpToMailchimp' => "Boolean"
    );

    /**
     * Store the user in MailChimp
     * @param array $mergeVars
     * @return Boolean
     */
    public function subscribeToMailchimp($mergeVars = array())
    {
        $listID = Config::inst()->get('EcommerceMailchimpSignupDecoratorFormFixes', 'mailchimp_list_id');

        $mailChimp = $this->getMailChimpAPI();

        $result = $mailChimp->post(
            "lists/".$listID."/members",
            array(
                'status'        => 'subscribed',
                'email_address' => $this->owner->Email,
                "merge_fields" => $mergeVars + array(
                    "FNAME" => $this->owner->FirstName,
                    "LNAME" => $this->owner->Surname
                )
            )
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
     *
     * @return bool
     */
    public function existsOnMailchimp()
    {
        if ($this->owner->SignedUpToMailchimp) {
            return true;
        }

        $mailChimp = $this->getMailChimpAPI();

        $listID = Config::inst()->get('EcommerceMailchimpSignupDecoratorFormFixes', 'mailchimp_list_id');

        $subscriberHash = $mailChimp->subscriberHash($this->owner->Email);

        $result = $mailChimp->get(
            "lists/".$listID."/members/".$subscriberHash
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
     *
     * @return bool
     */
    public function updateOnMailchimp()
    {
        $mailChimp = $this->getMailChimpAPI();

        $listID = Config::inst()->get('EcommerceMailchimpSignupDecoratorFormFixes', 'mailchimp_list_id');

        $subscriberHash = $mailChimp->subscriberHash($this->owner->Email);

        $result = $mailChimp->patch(
            "lists/".$listID."/members/".$subscriberHash,
            array(
                'merge_fields' => array(
                    'FNAME'=> $this->owner->FirstName,
                    'LNAME'=>$this->owner->Surname
                )
            )
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
    private static $_mailchimp_api = null;

    /**
     * @return MailChimp
     */
    protected function getMailChimpAPI()
    {
        require_once(Director::baseFolder().'/vendor/drewm/mailchimp-api/src/MailChimp.php');
        if (self::$_mailchimp_api) {
            //..
        } else {
            self::$_mailchimp_api = new \DrewM\MailChimp\MailChimp(Config::inst()->get('EcommerceMailchimpSignupDecoratorFormFixes', 'mailchimp_api_key'));
        }
        return self::$_mailchimp_api;
    }
}
