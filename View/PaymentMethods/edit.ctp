<br/>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/">Home</a></li>
    <li role="menuitem"><a href="/subscriptions/orders/">Payments</a></li>
    <li role="menuitem"><a href="/paymentmethods/index/">Payment Methods</a></li>
    <li role="menuitem" class="current"><a href="#">Update</a></li>
</nav>
<br/>

<?php if($card instanceof Braintree_PayPalAccount) {?>
      <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
      <form id="checkout" method="post" action="/paymentmethods/add/" autocomplete="off">
            <div class="row">
                <div class="small-12 large-12 columns">
                    <div id="paypal-container" class="left"></div>
                </div>
            </div>

             <div class="row">
                <div class="small-12 large-12 columns">
                  
                    <input type="submit" id="submit" value="Update Paypal Payment Method" class="button small radius" style="display:none;">

                </div>
              </div>

      </form>

      <script>
      var clientToken = "<?php echo $clientToken; ?>";
       braintree.setup(clientToken, "paypal", {
        container: "paypal-container",
        displayName : "Silly5",
        onPaymentMethodReceived: function (obj) {
          console.log('Paypal', obj.nonce);
          $("input[name=payment_method_nonce]").val(obj.nonce);
          $('#submit').show();
        }
      });
      </script>

<?php } else { ?>
<form id="checkout" method="post" action="/paymentmethods/edit/<?php echo $token; ?>" autocomplete="off">

            <table>
            	<tr>
            		<td>
            		
                    <div class="row">
                      <div class="large-12 columns">
                        <label>Card number (<?php echo $card->cardType; ?>)
                          <input type="text" name="number" value="<?php echo $card->maskedNumber; ?>" maxlength="30" placeholder="4111*****1111">
                        </label>
                      </div>
                    </div>


                      <div class="row">
                        
                        <div class="large-5 columns">
                           <label>Expiration date (MM/YYYY)</label>
                            <input type="text" value="<?php echo $card->expirationMonth; ?>" name="expiration_month" placeholder="MM" maxlength="2" style="width:50px;" class="left"><div style="float:left; text-align:center; padding-top:10px; width:20px;"> / </div>
                            <input type="text" value="<?php echo $card->expirationYear; ?>" name="expiration_year" placeholder="YYYY" maxlength="4" minlength="4" style="width:65px" class="left">
                          </label>
                        </div>

                        <div class="large-7 columns">
                          <label>Security Code

                            <input type="text" name="cvv" value="" placeholder="XXX" maxlength="4" style="width:63px;">
                          </label>
                          
                          <small style="position: relative; top: -13px; left: 5px;">

                            <span data-tooltip aria-haspopup="true" class="has-tip" title="For MasterCard or VISA, it's the last three digits in the signature area on the back of your card. For Maestro cards,it is not required to enter this code if you don't have one. ">What is this?</span></small>
                        </div>
                        
                      </div>
               

                     <div class="row">
                      <div class="large-12 columns">
                        <label>Cardholder name
                          <input type="text" name="cardholder_name" value="<?php echo $card->cardholderName; ?>" placeholder="John Smith">
                        </label>
                      </div>
                    </div>
					 <div class="row">
                      <div class="large-12 columns">
                      	<label for="checkbox1">
      					<input id="checkbox1" type="checkbox" name="makedefault" <?php echo $card->default!='' ? 'checked' : ''; ?>>
      					Default Payment Method for Customer?</label>
      
                      </div>
                    </div>


                    <div class="row">
                      <div class="small 6 large-6 columns">
                       
                          <input type="submit" id="submit" value="Update Payment Method" class="button small radius">

                      </div>
                    </div>
                </td>
            		
            	</tr>
            	
            </table>
          </form>
<?php } ?>