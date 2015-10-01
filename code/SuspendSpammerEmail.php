<?php

//Email the admin to let them know to check the registration and re-enable if it was a false positive.

class SuspendSpammerEmail extends Email {
  
  //enable emails to be sent to admin on suspected spammer registrations and posts.
  private static $enable_email = false;

  //Email address to send ghosted registrations and posts to for review.
  private static $email_to;
  
  protected $ss_template = "SuspendSpammerEmail";

    public function __construct($content) {
        //Email is not set as on - none shall pass...
        if(!$this->config()->enable_email){
          return;
        }
          
        //Check for a to and throw error if none set.
        if(!$this->config()->email_to){
          user_error("You have not set an 'email_to' in the config", E_USER_ERROR);
          return;
        }
        
        $from = Config::inst()->get('Email', 'admin_email');
        $to = $this->config()->email_to;
        $subject = "Suspected spammer: Please review";
        
        parent::__construct($from, $to, $subject);

        $this->populateTemplate(new ArrayData(array(
            'Content' => $content
        )));
    }  
}
