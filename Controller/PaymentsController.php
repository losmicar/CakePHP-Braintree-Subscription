<?php
/**
 * Payments Controller File
 *
 * Copyright (c) 2015 Milos Todorovic
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5.2.8
 * CakePHP version 2.4.5
 *
 * @package    braintree
 * @subpackage braintree.controllers
 * @copyright  2015 Milos Todorovic <losmi.todor@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link       https://github.com/losmicar/CakePHP-Braintree-Subscription
 */
/**
 * Payments Controller Class
 *
 * This is the main method where you should implement your own logic
 *
 * @package    braintree
 * @subpackage braintree.controllers
 */
class PaymentsController extends BraintreeAppController {

	public $uses = array('Braintree.Subscription');
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_canceled($webhookNotification){
		$this->log('subscription_canceled -> '.$webhookNotification->subscription->id, "webhook-".date('Y-m-d'));
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_expired($webhookNotification){
		$this->log('subscription_expired -> '.$webhookNotification->subscription->id, "webhook-".date('Y-m-d'));
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_charged_successfully($webhookNotification){
		$this->log('subscription_charged_successfully -> '.$webhookNotification->subscription->id, "webhook-".date('Y-m-d'));
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>1));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_went_past_due($webhookNotification){
		$this->log('subscription_went_past_due -> '.$webhookNotification->subscription->id, "webhook-".date('Y-m-d'));
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_charged_unsuccessfully($webhookNotification){
		$this->log('subscription_charged_unsuccessfully -> '.$webhookNotification->subscription->id, "webhook-".date('Y-m-d'));
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_went_active(){
		//$this->log('subscription_went_active -> '.$webhookNotification->subscription->id, "webhook-".date('Y-m-d'));
	}
	/**
	 * Update session details on subscribe success
	 * @param Array $subscription
	 */
	public function updateSession($subscription=array()){
		/**
 		 *
		 * If subscription is created or canceled, you may want to update currently logged in user
		 * Put your code here
		 */
	}
	/**
	 * Will return Plan Id that you have created in Braintree Control Panel
	 * If you have multiple Plans created, you can implement your own logic here
	 */
	public function getPlanId(){

		return DEFAULT_PLAN_ID;
	}

	/**
	* Save notify webhook response
	* @see https://developers.braintreepayments.com/javascript+php/guides/webhooks/parse
	* @param Object $Braintree_WebhookNotification
	*/
	public function saveNotify($webhookNotification){

		$message =
			        "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
			        . "Kind: " . $webhookNotification->kind . " | "
			        . "Subscription: " . $webhookNotification->subscription->id . "\n";
		$this->log($message, "webhook-".date('Y-m-d'));
	}
	/**
     * Get user ID, by default Auth.User.id is used but you can use your own logic
     */
    protected function getUserId(){
    	return 1; //$this->Auth->user('id');
    }
    /**
     * Get user info firstName, LastName, Companu from your DB source
     * You can use your own logic to generate Customer Details
     * @return Array $data  E.g. array('cardholderName'=>'John Smit', 'firstName'=>'John', 'lastName'=>'Smith');
     */
    protected function getUserInfo(){

    	$data = array();
    	
    	//IMPLEMENT YOUR OWN LOGIC

    	/*
    	$d = explode(' ', $this->Auth->user('displayname'));
		$data['firstName'] = isset($d[0]) ? $d[0] : $this->Auth->user('username');
		$data['lastName'] = isset($d[1]) ? $d[1] : $this->Auth->user('username');
		$data['company'] = $this->Auth->user('company');
		$data['cardholderName'] = (isset($d[0]) && isset($d[1])) ? $d[0].' '.$d[1] : $this->Auth->user('username');
		$data['email'] = $this->Auth->user('username');
		*/
		return $data;

    }
     /**
     *  Update Subscription Details
     *  This will be called when Subscription Canceled and Subscription Created.
     *  This is where you can implement your own logic for Subscription Created/Updated
     *  @param Array $data E.g. array('id'=>$subscription_id, 'status'=>0)
     */
    public function updateSubscription($data=array()){

    	$this->loadModel('Partneruser');

    	$subscription_id = $data['id'];
    	$status = $data['status'];

    	$subscription = $this->Subscription->findById($subscription_id);

    	if(!empty($subscription))
    	{
    		
    		//IMPLEMENT YOUR OWN LOGIC

    	}else{
    		$this->log('updateSubscription -> Error: Subscription not found.', "webhook-".date('Y-m-d'));
    	}

		

    }
}