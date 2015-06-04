<br/>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/">Home</a></li>
    <li role="menuitem"><a href="/subscriptions/orders/">Payments</a></li>
    <li role="menuitem" class="current"><a href="#">Billing History</a></li>
</nav>
<br/>
<h3>Last 10 transactions</h3>
<table summary="Transaction list" role="grid" width="100%">
  <tr>
    <th>Transaction ID</th>
    <th>Status</th>
    <!-- <th>Payer details</th> -->
    <th>Payment details</th>
    <th>Amount</th>
    <th>Created</th>
    <th>Actions</th>
  </tr>
  <?php
	foreach($transactions as $k=>$v){

		//print_r($v); die();
  ?>
  <tr>
    <td><?php echo $v->id; ?></td>
    <td><?php echo $v->processorResponseText; ?><br/><?php echo $v->status; ?></td>
    <!-- <td>
    	Full Name: <?php echo $v->customer['firstName']; ?> <?php echo $v->customer['lastName']; ?><br/>
    	Email: <?php echo $v->customer['email']; ?><br/>
    	Created: <?php echo $v->createdAt->format('d, M Y'); ?><br/>
    	
    	
    </td> -->
    
    <td>
    	<img src="<?php echo $v->creditCard['imageUrl']; ?>" width="35px" /> <?php echo $v->creditCard['cardType']; ?> <?php echo $v->creditCard['bin']; ?>************<?php echo $v->creditCard['last4']; ?><br/>
    	
    	<br/>
    </td>
    
    <td><?php echo $v->amount.' '.$v->currencyIsoCode; ?></td>
    <td>
    	<?php echo $v->createdAt->format('d, M Y H:i:s'); ?><br/>
    </td>
    <td>
      <a href="/subscriptions/receipt/" data-reveal-id="myModal" data-reveal-ajax="true" class="button tiny success radius"><small>View receipt</small></a></td>
  </tr>
  <?php } ?>
</table>