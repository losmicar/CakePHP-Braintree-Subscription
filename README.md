# Cake PHP Braintree Plugin for Subscriptions

This CakePHP Braintree plugin is in Beta Testing Phase. 

It is tested and developed using Cake PHP ver. 2.4.5.

Plugin is developed for creating subscriptions, not common transactions (that will come in future). All credit card (paypall) account info is stored in Braintreee Vault, nothing of these sensitive data is not stored on your server.

Webhooks management is implemented, but you'll need to create and verify your webhook URL from Braintree Control Panel.

#Requirements

* Cake Auth component
* [Foundation HTML Framework](http://foundation.zurb.com/develop/download.html) for better preview
* [Braintree Php Linrary](https://developers.braintreepayments.com/javascript+php/start/hello-server)

#Preview

* [Checkout Page](https://www.dropbox.com/s/gcbwhb604bd270w/Screenshot%202015-06-04%2009.39.39.png?dl=0) Url Example: yourpage.com/subscriptions/create/
* [Subscription Details Page](https://www.dropbox.com/s/zyciwl7f67gvvwx/Screenshot%202015-06-04%2009.37.17.png?dl=0) Url Example: yourpage.com/subscriptions/orders/
* [Payment Method Manage](https://www.dropbox.com/s/vvqdw51zpiha75j/Screenshot%202015-06-04%2009.41.12.png?dl=0) Url Example: yourpage.com/paymentmethods/index/
* [Add Payment Method](https://www.dropbox.com/s/e2btq6vwwpk7czv/Screenshot%202015-06-04%2009.42.13.png?dl=0) Url Example: yourpage.com/paymentmethods/add/
* [Billing History](https://www.dropbox.com/s/2trgm96s3t2n2do/Screenshot%202015-06-04%2009.42.40.png?dl=0) Url Example: yourpage.com/subscriptions/transactions/

=======

#Instalation instructions

* Create subscriptions table from subscriptions.sql file.

* Create routes in your **app/Config/route.php** file.
```
Router::connect(
   "/subscriptions/:action/*", array('plugin' => 'braintree', 'controller'=>'subscriptions')
);

Router::connect(
   "/subscriptions/", array('plugin' => 'braintree', 'controller'=>'subscriptions', 'action'=>'orders')
);

Router::connect(
   "/paymentmethods/:action/*", array('plugin' => 'braintree', 'controller'=>'paymentmethods')
);
```
* Import plugin in your **app/Config/bootstrap.php**

```
CakePlugin::load('Braintree');
```
* For a propper HTML rendering please download [Foundation HTML Framework](http://foundation.zurb.com/develop/download.html) and put it in Braintree Plugins webroot folders (css and js)
E.g app/Plugin/Braintree/webroot/css/foundation.min.css etc.

* For working with Braintree Api download [Braintree Php Linrary](https://developers.braintreepayments.com/javascript+php/start/hello-server) and put it into plugins **app/Plugin/Vendor/Braintree/Braintree** folder.

* Here you can create your sandbox credentials [Braintree Sandbox](https://www.braintreepayments.com/get-started)

* In your **app/Config/core.php** (NOT IN PLUGIN) setup your Braintree Credentials for testing.

```
Configure::write('Braintree', array(
	    'plan_id' => 'your_plan_id',
	    'env' => 'sandbox',
	    'merchantId' => 'your_merchant_id',
	    'publicKey' => 'your_public_key',
	    'privateKey' => 'your_private_key',

    ));
```
* Use PaymentsController.php to implement your own logic for subscription actions.

Enjoy.