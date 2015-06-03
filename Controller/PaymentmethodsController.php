<?php
/**
 * Manage Payment Methods
 */
App::uses('PaymentsController', 'Braintree.Controller');

class PaymentMethodsController extends PaymentsController {

    public $uses = array('Braintree.Payment', 'Braintree.Subscription');

    public function beforeRender(){
    	parent::beforeRender();
		$this->hasSubscription();
    }
    /**
     * List Payment methods
     * @see https://developers.braintreepayments.com/javascript+php/reference/request/payment-method/update#card-verification
     */
    public function index(){
    	$customer_id = $this->getUserId();

    	$customer = null;
		try{
			$customer = Braintree_Customer::find($customer_id);
		}catch(Exception $e){}

		$this->set('customer', $customer);
    }
    /**
     * Make Payment method default payment method
     * @see https://developers.braintreepayments.com/javascript+php/reference/request/payment-method/update#card-verification
     */

    public function makedefault ($token){

    	$result = Braintree_PaymentMethod::update(
		  $token,
		  array(
		    'options'=>array(
			    		'makeDefault'=>true
			    	)
		));

		//Find default payment card and update
		if($result->success){
				//Update subscription with new default method
				$this->updateSubscriptionToDefaultPaymentMethod($token);

				$this->Session->setFlash('Payment method updated.', 'default', array('class'=>'success'));
				$this->redirect('/braintree/paymentmethods/index/');
			}else{
				$errorMsg = '';
				foreach($result->errors->deepAll() AS $error) {
				    		   $errorMsg .= $error->code . ": " . $error->message . "<br/>";
				    	}
				$this->Session->setFlash($errorMsg, 'default', array('class'=>'error'));
			}
    }
    public function paypal(){
    	$this->set('clientToken', $this->getClientToken());    	
    }
    /**
     * Add Payment method
     * @see https://developers.braintreepayments.com/javascript+php/reference/request/payment-method/update#card-verification
     */
    public function add(){

    	//$customer = Braintree_Customer::find($this->Auth->user('id'));
		if ($this->request->is('post') && isset($this->request->data['payment_method_nonce']) && !empty($this->request->data['payment_method_nonce'])){
			
			$default = isset($this->request->data['makedefault']) ? true : false;

			$result = Braintree_PaymentMethod::create(array(
			    'customerId' => $this->getUserId(),
			    'paymentMethodNonce' => $this->request->data['payment_method_nonce'],
			    'options'=>array(
			    		'failOnDuplicatePaymentMethod'=>true,
			    		'makeDefault'=>$default,
			    		/*'verifyCard'=>true*/
			    	)
			));

			if($result->success){

				//$this->updateSubscriptionToDefaultPaymentMethod($result->token);

				$this->Session->setFlash('Payment method ready and active.', 'default', array('class'=>'success'));
				$this->redirect('/braintree/paymentmethods/index/');
			}else{
				$errorMsg = '';
				foreach($result->errors->deepAll() AS $error) {
				    		   $errorMsg .= $error->code . ": " . $error->message . "<br/>";
				    	}
				$this->Session->setFlash($errorMsg, 'default', array('class'=>'error'));
			}

		}

		$this->set('clientToken', $this->getClientToken());
    }
   
    /**
     * Edit Payment method
     * @see https://developers.braintreepayments.com/javascript+php/reference/request/payment-method/update#card-verification
     */
    public function edit($token){

    	if ($this->request->is('post')){

	    	$default = (isset($this->request->data['makedefault']) && ($this->request->data['makedefault']=='on' || $this->request->data['makedefault']=='1')) ? true : false;


	    	$data = array('options'=>array(
					    		'makeDefault'=>$default
					    	));

	    	//CARD HOLDER NAME
	    	if(!isset($this->request->data['cardholder_name']) || empty($this->request->data['cardholder_name']) || strlen($this->request->data['cardholder_name'])<3){
	    		throw new InvalidArgumentException("Check Card Holder name.");
	    		
	    	}else{
	    		//Sanitize
	    		$data['cardholderName'] = $this->request->data['cardholder_name'];
	    	}

	    	//EXPIRE DATE - MONTH
	    	if(!isset($this->request->data['expiration_month']) || empty($this->request->data['expiration_month']) || strlen($this->request->data['expiration_month'])<2 || !is_numeric($this->request->data['expiration_month'])){
	    		throw new InvalidArgumentException("Check Expire Month Value.");
	    		
	    	}else{
	    		//Sanitize
	    		$data['expirationMonth'] = $this->request->data['expiration_month'];
	    	}
	    	$year = intval(date('Y'));
	    	//EXPIRE DATE - YEAR
	    	if(!isset($this->request->data['expiration_year']) || empty($this->request->data['expiration_year']) || strlen($this->request->data['expiration_year'])<4 || !is_numeric($this->request->data['expiration_year']) || intval($this->request->data['expiration_year'])<$year){
	    		throw new InvalidArgumentException("Check Expire Year Value.");
	    		
	    	}else{
	    		//Sanitize
	    		$data['expirationYear'] = $this->request->data['expiration_year'];
	    	}

	    	//CARD NUMBER
	    	if(!isset($this->request->data['number']) || empty($this->request->data['number']) || strlen($this->request->data['number'])<3){
	    		throw new InvalidArgumentException("Check Card Holder name.");
	    		
	    	}else{

	    		if(strpos($this->request->data['number'], '*')===false)
	    		{
	    			//Sanitize
	    			$data['number'] = $this->request->data['number'];
	    		}
	    		
	    	}
	    	//CVV
	    	if(isset($this->request->data['cvv']) && !empty($this->request->data['cvv'])){
	    		$data['cvv'] = $this->request->data['cvv'];
	    	}

	    	$result = Braintree_PaymentMethod::update(
			  $token,
			  $data
			);
			if($result->success){

				//Update subscription with new default method
				$this->updateSubscriptionToDefaultPaymentMethod($token);

				$this->Session->setFlash('Payment method updated.', 'default', array('class'=>'success'));
				$this->redirect('/braintree/paymentmethods/index/');
			}else{
				$errorMsg = '';
				foreach($result->errors->deepAll() AS $error) {
				    		   $errorMsg .= $error->code . ": " . $error->message . "<br/>";
				    	}
				$this->Session->setFlash($errorMsg, 'default', array('class'=>'error'));
			}

    	}

    	$card = Braintree_PaymentMethod::find($token);

		$this->set('token', $token);
		$this->set('card', $card);
		$this->set('clientToken', $this->getClientToken());
    }
    
    private function updateSubscriptionToDefaultPaymentMethod($token){
    	//Update subscription with new default method
		$result = Braintree_Subscription::update($this->getSubscriptionId(), array(
		    'paymentMethodToken' => $token,
		));
    }
    /**
     * Delete Payment method
     * @see https://developers.braintreepayments.com/javascript+php/reference/request/payment-method/update#card-verification
     * @todo Sta se radi kad nema vise payment metoda?
     * @todo Sta se radi kad je imao 2 obrisao Default-nu i ostala mu 1 ne-defualt?
     */
    public function delete($token){

    	if(!isset($token)){

    		throw new InvalidArgumentException('No valid token.');
    	}
    	try{
    		$result = Braintree_PaymentMethod::delete($token);

    	}catch(Exception $e){

    	}

    	if($result->success){
				$this->Session->setFlash('Payment method removed.', 'default', array('class'=>'success'));
				$this->redirect('/braintree/paymentmethods/index/');
			}else{
				$errorMsg = '';
				foreach($result->errors->deepAll() AS $error) {
				    		   $errorMsg .= $error->code . ": " . $error->message . "<br/>";
				    	}
				$this->Session->setFlash($errorMsg, 'default', array('class'=>'error'));
			}

    }
   
   
    


}