<?php

App::import('Vendor', 'Braintree.Braintree/Braintree');
App::import('Config', 'Braintree.Config/config.php');


abstract class BraintreeAppController extends AppController {

	public $components = array(
	    'Auth' => array(
	        'authenticate' => array(
	            'Form' => array(
	                'fields' => array('username' => 'email')
	            )
	        )
	    )
	);

	public function beforeFilter(){

		parent::beforeFilter();
		/**
		  * Hande your ACL
		  * 
		  */
		$this->Auth->allow();
		$this->setup();
		$this->layout = 'payment';

	}
	/**
	 * Method that will handle save of notify web hooks responses (Called from SubscriptionsController.notify)
	 * @see https://developers.braintreepayments.com/javascript+php/guides/webhooks/parse
	 * @param Object $Braintree_WebhookNotification
	 */
	public abstract function saveNotify($Braintree_WebhookNotification);
	/**
	 * Method that will handle update subscription logic
	 * @param Array $data - E.g. array('id'=>$subscription_id, 'status'=>0) or array('id'=>$subscription_id, 'status'=>1)
	 */
	public abstract function updateSubscription($data=array());
	/**
	 * Method that will handle return of the User.id
	 */
	protected abstract function getUserId();
	/**
	 * Method that will handle return of the array data for creating Customer
	 */
	protected abstract function getUserInfo();
	/**
	 * Method that will update session if necessary on subscribe action
	 * E.g. Change plan of the user etc.
	 */
	public abstract function updateSession($subscription=array());
	/**
	 * Get plan id - You may have multiple plans and want ti implement different logic for different user to 
	 * return defferent plan ids
	 */
	public abstract function getPlanId();
	
	/**
	 *	Setup Braintree API
	 */
	private function setup(){
		Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
		Braintree_Configuration::merchantId(BRAINTREE_MERCHANT_ID);
		Braintree_Configuration::publicKey(BRAINTREE_PUBLIC_KEY);
		Braintree_Configuration::privateKey(BRAINTREE_PRIVATE_KEY);
    }
    /**
     * Get Braintree Client Token
     */
    protected function getClientToken($id=''){
    	return Braintree_ClientToken::generate(array(
	    	"customerId" => $id
		));
    }
    /**
     * Get Default Payment Card
     * @param Braintree_Customer $customer
     */
    protected function getDefaultPaymentCard($customer){

		$cards = array_merge($customer->creditCards , $customer->paypalAccounts);
		foreach ($cards as $key => $card) {
			if($card->default==1){
				return $card;
			}
		}
		return null;
    }
    /**
     * Helper f-tion to get Subscription ID using User id
     */
    public function getSubscriptionId(){
    	
    	$id = $this->getUserId();
    	$subscription = $this->Subscription->findByUserId($id);
    	return $subscription['Subscription']['id'];

    }
   	/**
     * Helper f-tion to check does user has active Subscription
     * Useful to print error msg
     */
    protected function hasSubscription(){

    	$customer = null;
    	try{
    		//Try to find already created customer
			$customer = Braintree_Customer::find($this->getUserId());
			
		}catch(Exception $e){}

		if(!$customer){
			$this->redirect('/subscriptions/create/');
		}else{
	    	$creditCard = $this->getDefaultPaymentCard($customer);
	    	$subscription = null;

		    foreach($creditCard->subscriptions as $k=>$v){ 
		        if($v->status=='Active'){
		            $subscription = $v;
		            break;
		          }
		        }
		    if(!$subscription){
		    	$this->set('error', 'Your Ads are not running. Please <a href="/subscriptions/subscribe/">Create Subscription</a> to start running your ads.');
		    }
		}
    }
}