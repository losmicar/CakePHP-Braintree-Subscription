<?php
/**
 * Custom User Implemented logic to handle all hooks
 */
class PaymentsController extends BraintreeAppController {

	public $uses = array('Braintree.Subscription');
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_canceled($webhookNotification){
		$this->logResult('subscription_canceled -> '.$webhookNotification->subscription->id);
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_expired($webhookNotification){
		$this->logResult('subscription_expired -> '.$webhookNotification->subscription->id);
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_charged_successfully($webhookNotification){
		$this->logResult('subscription_charged_successfully -> '.$webhookNotification->subscription->id);
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>1));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_went_past_due($webhookNotification){
		$this->logResult('subscription_went_past_due -> '.$webhookNotification->subscription->id);
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_charged_unsuccessfully($webhookNotification){
		$this->logResult('subscription_charged_unsuccessfully -> '.$webhookNotification->subscription->id);
		$this->updateSubscription(array('id'=>$webhookNotification->subscription->id, 'status'=>0));
	}
	/**
	 * @param Object $Braintree_WebhookNotification
	 */
	public function subscription_went_active(){
		//$this->logResult('subscription_went_active -> '.$webhookNotification->subscription->id);
	}
	/**
	 * Log into file/db
	 * @param String $message
	 */
	public function logResult($message){

		file_put_contents("/tmp/webhook-".date('Y-m-d').".log", '['.date('Y-m-h H:i:s').'] '.$message, FILE_APPEND);
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
		$this->logResult($message);
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
    	
    	$d = explode(' ', $this->Auth->user('displayname'));
		$data['firstName'] = isset($d[0]) ? $d[0] : $this->Auth->user('username');
		$data['lastName'] = isset($d[1]) ? $d[1] : $this->Auth->user('username');
		$data['company'] = $this->Auth->user('company');
		$data['cardholderName'] = (isset($d[0]) && isset($d[1])) ? $d[0].' '.$d[1] : $this->Auth->user('username');
		$data['email'] = $this->Auth->user('username');

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
    		//$this->logResult('updateSubscription -> subscription: '.print_r($subscription, true));

    		$userId = $subscription['Subscription']['user_id'];
			

			if(!empty($userId))
			{
				$this->Subscription->id = $subscription_id;
				$this->Subscription->saveField('status', $status);

				
				$this->Partneruser->User->id = $userId;
				$this->Partneruser->User->saveField('payed', $status);

				
				$partnerList = $this->Partneruser->find('list',
									array(
											'conditions'=>array(0=>'User.id = '.$userId),
											'fields'=>array('Partner.id', 'Partner.domain_short'),
											'recursive'=>0,
											'order' => array('Partner.domain_short' => 'asc')
									));
				if(!empty($partnerList))
				{
					$this->Partneruser->Partner->query('UPDATE partners SET ads='.$status.' WHERE id IN ('.implode(',', array_keys($partnerList)).')');
					
					//Find all other users that this partner has created and update status?

					//$this->Partneruser->Partner->query('UPDATE partners_users SET ads='.$status.' WHERE id IN ('.implode(',', array_keys($partnerList)).')');
				}
			}
    	}else{
    		$this->logResult('updateSubscription -> Error: Subscription not found.');
    	}

		

    }
}