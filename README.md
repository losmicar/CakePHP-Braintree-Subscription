# Cake PHP Plugin for easy creating Braintree Subscriptions

This Braintree CakePHP plugin is in Beta Testing Phase. 

It is tested and developed on Cake PHP ver. 2.4.5.

Auth component is required.

Webhooks management is implemented also, but you'll need to create and verify your webhook URL from Braintree Control Panel.

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

* For working with Braintree Api download [Braintree Api](https://developers.braintreepayments.com/javascript+php/start/hello-server) and put it into plugins **app/Plugin/Vendor/Braintree/Braintree** folder.

* Here you can create your sandbox credentials [Braintree Sandbox](https://www.braintreepayments.com/get-started)

* In your **app/Config/core.php** (NOT IN PLUGIN) setup your Braintree Credentials for testing.
```
define('DEFAULT_PLAN_ID', 'your_name_of_the_plan');
define('BRAINTREE_ENVIRONMENT', 'sandbox');
define('BRAINTREE_MERCHANT_ID', 'your_merchant_id');
define('BRAINTREE_PUBLIC_KEY', 'your_public_key');
define('BRAINTREE_PRIVATE_KEY', 'your_private_key);
```
* Use PaymentsController.php to implement your own logic for subscription actions.