<?php
	//@see http://zurb.com/playground/foundation-icon-fonts-3#customize
	$css = array(
					'/braintree/css/foundation.min.css',
					'/braintree/css/foundation/app.css',
					'/braintree/css/foundation-icons/foundation-icons.css',
				);

	foreach ($css as $value) {
		$this->Html->css($value, null, array('inline'=>false));
	}
	//We will store all local JavaScript files into this array

	$jsScriptsLocal =  array(
		'/braintree/js/vendor/jquery.js',
		'/braintree/js/vendor/modernizr.js',
		'/braintree/js/foundation.min.js',
		'/braintree/js/foundation/foundation.tooltip.js',
		);

	//Do minify magic
	foreach ($jsScriptsLocal as $value) {
		$this->Html->script($value, array('inline'=>false));
	}
	
?>
