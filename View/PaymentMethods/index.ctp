<br/>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/partnerusers/index/">My Sites</a></li>
    <li role="menuitem"><a href="/subscriptions/orders/">Payments</a></li>
    <li role="menuitem" class="current"><a href="#">Payment Methods</a></li>
</nav>
<br/>
<a href="/paymentmethods/add/" class="button small radius">Add Payment Method</a>
<!--
<a href="/braintree/paymentmethods/paypal/" class="button small">Add Paypal</a>
-->
<table style="width:100%">
	<tr>
		<th></th>
		<th>Token</th>
		<th>Payment method</th>
		<th class="text-center">Default</th>
		<th>Last Charged</th>
		<th></th>

	</tr>
<?php 

$cards = array_merge($customer->creditCards , $customer->paypalAccounts);
//print_r($cards);
//print_r($customer->paypalAccounts);
//die();

foreach($cards as $k=>$v){

	?>
	<tr>
		<td></td>
		<td><?php echo $v->token; ?></td>
		<?php if($v instanceof Braintree_PayPalAccount) {?>
		<td><img src="<?php echo $v->imageUrl; ?>" width="30px" />&nbsp;&nbsp;&nbsp;<?php echo $v->email; ?></td>


		<?php }else{ ?>

		<td><img src="<?php echo $v->imageUrl; ?>" width="30px" />&nbsp;&nbsp;&nbsp;<?php echo $v->cardType; ?>&nbsp;&nbsp;&nbsp;<?php echo $v->maskedNumber; ?></td>

		<?php }?>
		
		<td class="text-center"><?php echo $v->default==1 ? '<i class="fi-check"></i>' : ''; ?></td>
		<td>
			<?php 
				foreach ($v->subscriptions as $key => $value) {
					if($value->status!='canceled'){

						foreach ($value->transactions[0]->statusHistory as $k => $trans) {
							if($trans->status=='settled'){
							echo $trans->timestamp->format('d, M Y H:i:s');
							?>
							<br/>
							<!-- myModal je u payment layout-u -->
							<a href="/payments/receipt/" data-reveal-id="myModal" data-reveal-ajax="true"><small>View Receipt</small></a>
							<?php
							break;
							}
						}
						break;
					}
				}
			?>
		</td>
		<td>
			<a href="#" class="tiny button split radius">Action <span data-dropdown="drop<?php echo $v->token; ?>"></span></a><br>
			<ul id="drop<?php echo $v->token; ?>" class="f-dropdown" data-dropdown-content>
			 
				<li><a href="/paymentmethods/edit/<?php echo $v->token; ?>">Update</a></li>
				<li><a href="/paymentmethods/delete/<?php echo $v->token; ?>">Delete</a></li>
				<?php 
					if(!$v->default){
				?>
				<li><a href="/paymentmethods/makedefault/<?php echo $v->token; ?>">Set as Default</a></li>
				<?php } ?>
			</ul>
		</td>
	</tr>
<?php } ?>
</table>