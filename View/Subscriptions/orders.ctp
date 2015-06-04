<br/>
<?php
if($customer){
  $cards = array_merge($customer->creditCards , $customer->paypalAccounts);

//print_r($customer); die();
  if(!empty($cards))
  {

    foreach ($cards as $k => $card) {
      if($card->default==1){

           $creditCard = $card;
           break;
      }
    }
    $subscription = null;

    foreach($creditCard->subscriptions as $k=>$v){ 
        if($v->status!='Canceled'){
            $subscription = $v;
            break;
          }
        }
?>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/">Home</a></li>
    <li role="menuitem"><a href="/subscriptions/orders/">Payments</a></li>
    <li role="menuitem" class="current"><a href="#">Product Billing</a></li>
</nav>
<br/>
  <table summary="Subscription details" role="grid" width="100%">
    
      <tr>
          
          
          <!-- Customer & Card info -->
          <td style="vertical-align:top;">

            <!-- Card Billing info -->
              <table summary="Card Billing details" role="grid" width="100%">
                  <tr>
                    <td colspan="2"><h5>Active Payment Method</h5></td>
                    
                  </tr>
                  <tr>
                    <td>Card type</td>
                    <td><img src="<?php echo $creditCard->imageUrl; ?>" width="40px"> 
                      <b>
                      <?php if($creditCard instanceof Braintree_PayPalAccount) {?>
                      PayPal
                      <?php } else {?>
                      <?php echo $creditCard->cardType; ?>
                        <?php } ?>
                      </b></td>
                  </tr>
                  <?php if(!$creditCard instanceof Braintree_PayPalAccount) {?>
                  <tr>
                    <td>Card no</td>
                    <td><?php echo $creditCard->maskedNumber; ?></td>
                  </tr>

                  <tr>
                    <td>Cardholder name</td>
                    <td><?php echo $creditCard->cardholderName; ?></td>
                  </tr>
                  <tr>
                    <td>Expire date</td>
                    <td><?php echo $creditCard->expirationMonth; ?>/<?php echo $creditCard->expirationYear; ?></td>
                  </tr>
                  <?php } else{?>
                  <tr>
                    <td>Email</td>
                    <td><?php echo $creditCard->email; ?></td>
                  </tr>

                  <?php } ?>
                  <tr>
                    <td colspan="2"><a href="/paymentmethods/index/" class="button tiny radius" style="margin-bottom: 0px;">Update Payment Method</a></td>
                  </tr>

              </table>
                <!-- Customer info -->
               <table summary="Customer details" role="grid" width="100%">
                    <tr>
                    <td colspan="2"><h5>Customer Details</h5></td>
                  </tr>       
                    <tr>
                      <td>Id</td>
                      <td><?php echo $customer->id; ?></td>
                    </tr>
                    <tr>
                      <td>First Name</td>
                      <td><?php echo $customer->firstName; ?></td>
                    </tr>
                    <tr>
                      <td>Last name</td>
                      <td><?php echo $customer->lastName; ?></td>
                    </tr>
                    <tr>
                      <td>Company</td>
                      <td><?php echo $customer->company; ?></td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td><?php echo $customer->email; ?></td>
                    </tr>
                    <tr>
                      <td>Created at</td>
                      <td><?php echo $customer->createdAt->format('d, M Y H:i:s'); ?></td>
                    </tr>
                    <tr>
                      <td>Updated at</td>
                      <td><?php echo $customer->updatedAt->format('d, M Y H:i:s'); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><a href="/subscriptions/client/" class="button tiny radius" style="margin-bottom: 0px;">Update Customer Details</a></td>
                    </tr>

              </table>
          </td>

          <!-- Subscription -->
          <td style="vertical-align:top;">
            <?php
                 
              if($subscription!=null)
              {
                   ?>
              <table summary="Subscription details" role="grid" width="100%">

                 



                <tr>
                    <td><h5>Subscription Details</h5></td>

                      <td>
                        <a href="/subscriptions/cancel/<?php echo $subscription->id; ?>" class="button tiny secondary right radius" style="margin-bottom: 0px;">Cancel Subscription</a>
                      </td>
                  </tr>
            
                
                  <tr>
                      <td>Status</td>
                      <td><?php echo $subscription->status; ?></td>
                  </tr>
                  <tr>
                      <td>ID</td>
                      <td><?php echo $subscription->id; ?></td>
                  </tr>
                  <tr>
                      <td>Balance</td>
                      <td><?php echo $subscription->balance; ?></td>
                  </tr>
                  <tr>
                      <td>Created / Updated</td>
                      <td><?php echo $subscription->createdAt->format('d, M Y H:i:s'); ?> / <?php echo $subscription->updatedAt->format('d, M Y H:i:s'); ?></td>
                  </tr>
                  <tr>
                      <td>Day of month</td>
                      <td><?php echo $subscription->billingDayOfMonth; ?>, Billing start date: <?php echo $subscription->billingPeriodStartDate->format('d, M Y H:i:s'); ?></td>
                  </tr>
                  <tr>
                      <td>Current Billing Cycle</td>
                      <td><?php echo $subscription->currentBillingCycle; ?> / <?php echo $subscription->numberOfBillingCycles; ?></td>
                  </tr>
                  <tr>
                      <td>Next Billing Date</td>
                      <td><?php echo $subscription->nextBillingDate->format('d, M Y H:i:s'); ?></td>
                  </tr>
                  <tr>
                      <td>Billing Amount</td>
                      <td>$<?php echo $subscription->nextBillAmount; ?></td>
                  </tr>
                  <tr>
                      <td>Plan id</td>
                      <td><?php echo $subscription->planId; ?></td>
                  </tr>

                  <tr>
                      <td><h5>Payment History</h5></td>
                      <td>
                        <a href="/subscriptions/transactions/" class="button tiny secondary right radius" style="margin-bottom: 0px;">View all payments</a>
                      </td>
                  </tr>
                  <tr>
                    <td colspan="2">

                         <table summary="Transaction details" role="grid" width="100%">
                          <tr>
                            <th>Id</th>
                            <th>Payed with</th>
                            <th>Status</th>
                            <th>Amaount</th>
                            <th>Created</th>
                            
                         </tr>
                          <?php 

                          //print_r($v->transactions); die();
                          foreach($subscription->transactions as $key=>$transaction){ 

                            ?>

                          <tr>
                              <td><?php echo $transaction->id; ?></td>
                              <td><img src="<?php echo $transaction->creditCard['imageUrl']; ?>" width="25px"> <?php echo $transaction->creditCard['cardType']; ?></td> <!-- imageUrl,  maskedNumber-->
                              <td><?php echo $transaction->status; ?></td>
                              <td>$<?php echo $transaction->amount; ?></td>
                              <td><?php echo $transaction->createdAt->format('d, M Y H:i:s'); ?></td>
                          
                          </tr>

                          <?php } ?>
                          </table>
                    </td>

                  </tr>
              <table>
                <?php } else { ?>


                <table>

                    <tr>

                        <td>
                            
                            <a href="/subscriptions/subscribe/" class="button tiny radius" style="margin-bottom: 0px;">Create Subscription</a>
                        </td>
                    </tr>
                </table>
                <?php } ?>
          </td>


      </tr>
  </table>
    
<?php } else { ?>

<div data-alert class="alert-box alert radius">
      Your Ads are not running. Please <a href="/paymentmethods/add/">Create Valid Payment Method</a> to start running your ads.
      <a href="#" class="close">&times;</a>
    </div>

<?php } ?>

</table>
<?php
}else{
  ?>

  <div class="panel">Invalid customer.</div>

<?php
}
?>
