<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<br/>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/">Home</a></li>
    <li role="menuitem" class="current"><a href="#">Checkout</a></li>
</nav>
<br/>

<h3>Payment Total US $179.00</h3>
<br/>
<div class="row">
    <div class="small-6 large-6 columns">
          <form id="checkout" method="post" action="/subscriptions/checkout" autocomplete="off">
             <table style="width:500px">
            	<tr>
            		<td>
                    <div class="row">
                      <div class="large-12 columns">
                        <label>Card number
                          <input type="text" data-braintree-name="number" value="4111111111111111" maxlength="30" placeholder="4111111111111111">
                        </label>
                      </div>
                    </div>


                      <div class="row">
                        
                        <div class="large-7 columns">
                           <label>Expiration date</label>
                            <input type="text" data-braintree-name="expiration_month" placeholder="MM" maxlength="2" style="width:50px;" class="left"><div style="float:left; text-align:center; padding-top:10px; width:20px;"> / </div>
                            <input type="text" data-braintree-name="expiration_year" placeholder="YYYY" maxlength="4" minlength="4" style="width:65px" class="left">
                          </label>
                        </div>

                        <div class="large-5 columns">
                          <label>Security Code

                            <input type="text" data-braintree-name="cvv" value="100" placeholder="XXX" maxlength="5" style="width:63px;">
                          </label>
                          
                          <small style="position: relative; top: -13px; left: 5px;">

                            <span data-tooltip aria-haspopup="true" class="has-tip" title="For MasterCard or VISA, it's the last three digits in the signature area on the back of your card. For Maestro cards,it is not required to enter this code if you don't have one. ">What is this?</span></small>
                        </div>
                        
                      </div>
               

                     <div class="row">
                      <div class="large-12 columns">
                        <label>Cardholder name
                          <input type="text" data-braintree-name="cardholder_name" value="John Smith" placeholder="John Smith">
                        </label>
                      </div>
                    </div>


                    <div class="row">
                      <div class="small 6 large-6 columns">
                       
                          <input type="submit" id="submit" value="PAY NOW" class="button small radius">

                      </div>
                      <div class="small 6 large-6 columns">
                          <div id="paypal-container" class="right"></div>
                      </div>
                    </div>
                </td>
            		
            	</tr>
            	
            </table>
          </form>
      </div>
      <div class="small-6 large-6 columns">
      </div>
</div>
 
<div class="panel">
<ul class="no-bullet" style="margin-bottom:0px;">
  <li>Note</li>
  </li>
</ul>
<h5>
<ul class="disc">
  <li><small>Your payment is secured with VeriSign SSL encryption, the highest commercially available encryption technology. Please be assured that your credit/debit card details will not be exposed.</small>
  </li>
  <li><small>Import duties, taxes and other customs related charges are not included. Buyers bear all responsibility for all extra charges incurred (if any).</small>
  </li>
</ul>
</h5>

</div>


<script>

var clientToken = "<?php echo $clientToken; ?>";

braintree.setup(clientToken, "custom", {id: "checkout"});


braintree.setup(clientToken, "paypal", {
  container: "paypal-container",
  displayName : "Silly5",
  onPaymentMethodReceived: function (obj) {
    console.log('Paypal', obj.nonce);
    $("input[name=payment_method_nonce]").val(obj.nonce);
    //$('#checkout').append('<input type="text" name="payment_method_nonce" value="'+obj.nonce+'">');
    $('#checkout').submit();
  }
});
</script>