<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<br/>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/partnerusers/index/">My Sites</a></li>
    <li role="menuitem"><a href="/subscriptions/orders/">Payments</a></li>
    <li role="menuitem"><a href="/paymentmethods/index/">Payment Methods</a></li>
    <li role="menuitem" class="current"><a href="#">Add</a></li>
</nav>
<br/>

<h3>Add Payment Method</h3>


<form id="checkout" method="post" action="/paymentmethods/add/" autocomplete="off">
    <ul class="tabs" data-tab>
      <li class="tab-title active"><a href="#panel11">Credit or Debit card</a></li>
      <li class="tab-title"><a href="#panel21">PayPal</a></li>
    </ul>
    <div class="tabs-content">
      <div class="content active" id="panel11">
        <table style="width:500px">
              <tr>
                <td>

                    <div class="row">
                      <div class="large-12 columns">
                        <label>Card number
                          <input type="text" data-braintree-name="number" maxlength="30" placeholder="378282246310005">
                        </label>
                      </div>
                    </div>


                      <div class="row">
                        
                        <div class="large-7 columns">
                           <label>Expiration date (MM/YYYY)</label>
                            <input type="text" data-braintree-name="expiration_month" placeholder="MM" maxlength="2" style="width:50px;" class="left"><div style="float:left; text-align:center; padding-top:10px; width:20px;"> / </div>
                            <input type="text" data-braintree-name="expiration_year" placeholder="YYYY" maxlength="4" minlength="4" style="width:65px" class="left">
                          </label>
                        </div>

                        <div class="large-5 columns">
                          <label>Security Code

                            <input type="text" data-braintree-name="cvv" placeholder="XXX" maxlength="5" style="width:63px;">
                          </label>
                          
                          <small style="position: relative; top: -13px; left: 5px;">

                            <span data-tooltip aria-haspopup="true" class="has-tip" title="For MasterCard or VISA, it's the last three digits in the signature area on the back of your card. For Maestro cards,it is not required to enter this code if you don't have one. ">What is this?</span></small>
                        </div>
                        
                      </div>
               

                     <div class="row">
                      <div class="large-12 columns">
                        <label>Cardholder name
                          <input type="text" data-braintree-name="cardholder_name" placeholder="John Smith">
                        </label>
                      </div>
                    </div>
           <div class="row">
                      <div class="large-12 columns">
                        <label for="checkbox1">
                <input id="checkbox1" type="checkbox" name="makedefault">
                Default Payment Method for Customer?</label>
      
                      </div>
                    </div>


                    <div class="row">
                      <div class="small-6 large-6 columns">
                       
                          <input type="submit" id="submit" value="Create Payment Method" class="button small radius">

                      </div>
                    </div>
                </td>
                
              </tr>
              
            </table>
      </div>
      <div class="content" id="panel21">
         <div class="row">
            <div class="small-12 large-12 columns">
              
                <h5>Authorize with PayPal account</h5>
            </div>
          </div>
         <div class="row">
            <div class="small-12 large-12 columns">
              
                <div id="paypal-container" class="left"></div>

            </div>
          </div>
         <div class="row" style="margin-top:20px;">
            <div class="small-12 large-12 columns">
              
                <input type="submit" id="submit2" value="Create Paypal Payment Method" class="button small radius" style="display:none">

            </div>
          </div>
      </div>
    </div>
</form>



<script>

var clientToken = "<?php echo $clientToken; ?>";
var initPP = false;


$('.tabs').on('toggled', function (event, tab) {
    console.log('aaaaaaaa', $(tab).find('a').attr('href'));
    if($(tab).find('a').attr('href')=='#panel11'){
        //alert('card');      
        braintree.setup(clientToken, "custom", {id: "checkout"});

    }else{
      $('#paypal-container').html('');
      $("input[name=payment_method_nonce]").remove();
      braintree.setup(clientToken, "paypal", {
        container: "paypal-container",
        displayName : "Silly5",
        onPaymentMethodReceived: function (obj) {
          console.log('Paypal', obj.nonce);
          $("input[name=payment_method_nonce]").val(obj.nonce);
          $('#submit2').show();
          //$('#checkout').append('<input type="text" name="payment_method_nonce" value="'+obj.nonce+'">');
          //$('#checkout').submit();
        }
      });      

    }
});

braintree.setup(clientToken, "custom", {id: "checkout"});

</script>