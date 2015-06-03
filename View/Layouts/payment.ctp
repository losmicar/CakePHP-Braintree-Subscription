<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php  echo $this->element('scripts', array()); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>Braintree Payment Plugin | <?php echo $title_for_layout; ?></title>

<?php
	echo $this->Html->meta('icon');
	echo $this->Html->meta('description','Braintree Subscription Plugin Cake Php');
	echo $this->fetch('meta');
	echo $this->fetch('css');

	echo $this->fetch('script');
?>
</head>
<body>

<?php echo $this->Session->flash(); ?>

<!-- Start Menu -->
<?php echo $this->element('menu'); ?>
<!-- End Menu -->

<!-- Start Content div -->
<div class="row">
		<div class="small-12 large-centered columns">
			<?php
				if(isset($error)){
					?>
					<br/>
			<div data-alert class="alert-box alert radius">
		      <?php echo $error; ?>
		      <a href="#" class="close">&times;</a>
		    </div>
		    <?php } ?>
    <?php echo $this->fetch('content'); ?></div>
</div>	
<!-- End Content div -->

<!-- Default values JS -->
<?php 
if(Configure::read('debug')==2){
?>
<div style="float:left; width:100%">
<?php echo $this->element('sql_dump'); ?>
</div>
<?php } ?>
<div id="myModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<script>
    $(document).foundation({
		  tooltip: {
		    selector : '.has-tip',
		    additional_inheritable_classes : [],
		    tooltip_class : '.tooltip',
		    touch_close_text: 'tap to close',
		    disable_for_touch: false,
		    tip_template : function (selector, content) {
		      return '<span data-selector="' + selector + '" class="'
		        + Foundation.libs.tooltip.settings.tooltip_class.substring(1)
		        + '">' + content + '<span class="nub"></span></span>';
		    }
		  }
		});
  </script>
</body>
</html>