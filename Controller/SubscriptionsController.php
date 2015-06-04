<?php
/**
 * Subscriptions Controller File
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
 * Subscriptions Controller Class
 *
 * @package    braintree
 * @subpackage braintree.controllers
 */
App::uses('PaymentsController', 'Braintree.Controller');

class SubscriptionsController extends PaymentsController {

    public $uses = array('Braintree.Payment', 'Braintree.Subscription');

   
    public function beforeRender(){
    	parent::beforeRender();
    	//Create is a defualt (checkout) action so do not check subscription status there
    	if($this->action!='create')
    		$this->hasSubscription();
    }
	/**
	 * Webhook notify url
	 * @see https://developers.braintreepayments.com/javascript+php/guides/webhooks/parse
	 * @see https://developers.braintreepayments.com/javascript+php/reference/general/webhooks#triggers
	 */
	public function notify(){

		$this->autoRender = false;
		if(isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) {

			try{

				//An error is raised if the signature is invalid. For more information, see the exceptions section.
			    $webhookNotification = Braintree_WebhookNotification::parse(
			        $_POST["bt_signature"], $_POST["bt_payload"]
			    );
			    
		    	if(method_exists($this, $webhookNotification->kind)){
		    		$this->{$webhookNotification->kind}($webhookNotification);
		    	}
			    

			    $this->saveNotify($webhookNotification);

			}catch(Exception $e){

			}
		}else{
			//Verify Challenge Token
			//@see http://stevesohcot.com/tech-lessons-learned/2012/11/04/setting-up-braintree-payments-webhooks/
			if(isset($_GET['bt_challenge']))
				echo Braintree_WebhookNotification::verify($_GET['bt_challenge']);
		}
	}
   
	/**
	 * Cancel subscritpion
	 * @param $id - Subscription id (char)
	 */
    public function cancel($id){

    	if(!isset($id)){

    		throw new InvalidArgumentException('Invalid subscription id.');
    	}
    	$result = Braintree_Subscription::cancel($id);
 
	    if ($result->success) {
	    	$this->updateSession(array('status'=>0));
	        $this->Session->setFlash('SUBSCRIPTION CANCELED SUCCESSUFULLY', 'default', array('class'=>'success'));
	        $this->redirect('/subscriptions/orders/');

	    } else {
	        foreach ($result->errors->deepAll() as $error) {
	            echo($error->code . ": " . $error->message . "<br>");
	        }
	        exit;
	    }
    }
    /**
     * Common redirect
     */
    public function index(){
    	$this->redirect('/subscriptions/orders/');
    }
    /**
	 * Preview Subscription (Subscription, billing Info, Client Info)
	 */
    public function orders(){

    	$customer = null;
    	try{
    		//Try to find already created customer
			$customer = Braintree_Customer::find($this->getUserId());
			
		}catch(Exception $e){}

		$this->set('customer', $customer);
    }
    /**
	 * List transactions
	 */
    public function transactions(){

    	$collection = Braintree_Transaction::search(array(
		  Braintree_TransactionSearch::customerId()->is($this->getUserId()),
		));

		//$collection->maximumCount(); Maximalni broj itema -postoji opasnos od Race conditions-a pa ne moze paginacija klasicna

		$this->set('transactions', $collection);
    }
     /**
     * Show receipt
     */
    public function receipt(){

    	$this->layout = false;
    }
    /**
     * Checkout submit
     * @see https://developers.braintreepayments.com/javascript+php/guides/recurring-billing/create
     * @todo Better error handling
     */
    public function checkout(){

    	if(!isset($this->request->data['payment_method_nonce'])){

    		throw new InvalidArgumentException('No valid nonce.');
    	}

		$customer = null;
    	try{
    		//Try to find already created customer
			$customer = Braintree_Customer::find($this->getUserId());
			
		}catch(Exception $e){}
		
		//If there is no customer create one
		if(!$customer){

			
			$data = array_merge(array('paymentMethodNonce'=>$this->request->data['payment_method_nonce']), $this->getUserInfo());

			 /** 
			  *	First we create a new user using the BT API
			  *	For a subscription model to work, we need to save customer to the Vault
			  *	We user Auth->user('id') as customer id in braintree system
			  * @see https://developers.braintreepayments.com/javascript+php/reference/request/customer/create
			  */
    		$result = Braintree_Customer::create($data);
		   
			if ($result->success) {

				$customer = $result->customer;

			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        echo($error->code . ": " . $error->message . "\n");
			    }
			}

			
		}
		
		if($customer){
			//Get credit card info
			$cards = array_merge($customer->creditCards , $customer->paypalAccounts);
			//First Time Checkout
			if(empty($cards))
			{
				//Create Payment method
				$card = Braintree_PaymentMethod::create(array(
			    'customerId' => $this->getUserId(),
				    'paymentMethodNonce' => $this->request->data['payment_method_nonce'],
				    'options'=>array(
				    		'failOnDuplicatePaymentMethod'=>true,
				    		'makeDefault'=>true,
				    		/*'verifyCard'=>true*/
				    	)
				));
			}
			
			//Create subscitpion
			$this->subscribe();

		}
    	
		
    }

    /**
     * Create or update subscription
     * User has canceled subscription, but not deleted account and payment method - update
     * If there is nonce, than update
     * Crete new subscription for existing customer
	 */
    public function subscribe(){

    	$customer = null;
    	try{
    		//Try to find already created customer
			$customer = Braintree_Customer::find($this->getUserId());
			
		}catch(Exception $e){}

		if($customer!=null)
		{
			$card = $this->getDefaultPaymentCard($customer);

			if($card)
			{
				$token = $card->token;
		    	//Create subscription on the selected credit card
				$result = Braintree_Subscription::create(array(
				  'paymentMethodToken' => $token,
				  'planId' => $this->getPlanId(),
				  
				));

				if($result->success)
				{
					$subscription = array(
										'user_id' => $this->getUserId(),
										'card_token' => $token, 
										'id' => $result->subscription->id,
										'status'=> 1,
										'transaction_id' => $result->subscription->transactions[0]->id 

									);
					
					if(isset($this->request->data['payment_method_nonce']) && !empty($this->request->data['payment_method_nonce'])){
						$subscription['nonce'] = $this->request->data['payment_method_nonce'];
					}
					/**
					 * Save subscription results
					 * We do not save any card data, we don't need any Billing/Customer info eaither
					 * All sensitive data will be managed by Braintree
					 */
					if($this->Subscription->save($subscription)){

						$this->updateSession($subscription);

						$this->Session->setFlash('SUBSCRIPTION CREATED SUCCESSUFULLY.', 'default', array('class'=>'success'));
						$this->redirect('/subscriptions/orders/');
					}
				}
				else{

					foreach($result->errors->deepAll() AS $error) {
						    echo($error->code . ": " . $error->message . "\n");
					}
				}
			}else{
				$this->Session->setFlash('Please Add Payment Method first', 'default', array('class'=>'error'));
				$this->redirect('/subscriptions/orders/');
			}
		}else{
			$this->Session->setFlash('Please create checkout subscription first', 'default', array('class'=>'error'));
			$this->redirect('/subscriptions/create/');
		}
    }

    //Delete Customer from Vault
    public function delete($id=1){

    	Braintree_Customer::delete($id);

    }
    /**
     * Update Client Info
     * @see https://developers.braintreepayments.com/ios+php/guides/customers
     */
    public function client(){

    	$customer_id = $this->getUserId();

    	if ($this->request->is('post') && isset($this->request->data['email']) && !empty($this->request->data['email']))
    	{
	    	

	    	$updateResult = Braintree_Customer::update(
			    $customer_id,
			    $this->request->data
			);

			if($updateResult->success){

				$this->Session->setFlash('Client data updated SUCCESSUFULLY.', 'default', array('class'=>'success'));
				$this->redirect('/subscriptions/client/');

			}
		}
		$customer = null;
		try{
			$customer = Braintree_Customer::find($customer_id);
		}catch(Exception $e){}

		$this->set('customer', $customer);

    }
    
    /**
     * Checkout form
     */
    public function create(){
		
		$customer = null;
    	try{
    		//Try to find already created customer
			$customer = Braintree_Customer::find($this->getUserId());
			
		}catch(Exception $e){}

		if(!empty($customer)){

			$this->Session->setFlash('Customer is active, please check your subscription.', 'default', array('class'=>'error'));
			$this->redirect('/subscriptions/orders/');
		}
		
		$this->set('clientToken', $this->getClientToken());    	
		$this->set('customer', $customer);    	
		
    }
}