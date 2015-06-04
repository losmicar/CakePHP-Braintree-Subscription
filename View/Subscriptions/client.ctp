<br/>
<nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
    <li role="menuitem"><a href="/">Home</a></li>
    <li role="menuitem"><a href="/subscriptions/orders/">Payments</a></li>
    <li role="menuitem" class="current"><a href="#">Client Information</a></li>
</nav>
<br/>

<?php //print_r($customer); ?>

<h3>Update Client Information</h3>

<form id="checkout" method="post" action="/subscriptions/client/" autocomplete="off">
  <table style="width:60%">
  	<tr>
  		<td>
          <div class="row">
            <div class="large-12 columns">
              <label>First Name
                <input type="text" name="firstName" value="<?php echo $customer->firstName; ?>" maxlength="30" placeholder="First name">
              </label>
            </div>
          </div>
        </td>
        <td>
          <div class="row">
            <div class="large-12 columns">
              <label>Last Name
                <input type="text" name="lastName" value="<?php echo $customer->lastName; ?>" maxlength="30" placeholder="Last name">
              </label>
            </div>
          </div>
        </td>
    </tr>
    <tr>
  		<td>
          <div class="row">
            <div class="large-12 columns">
              <label>Email
                <input type="text" name="email" value="<?php echo $customer->email; ?>" maxlength="30" placeholder="Email">
              </label>
            </div>
          </div>
        </td>
        <td>
          <div class="row">
            <div class="large-12 columns">
              <label>Phone
                <input type="text" name="phone" value="<?php echo $customer->phone; ?>" maxlength="30" placeholder="Telephone">
              </label>
            </div>
          </div>
        </td>
    </tr>
    <tr>
  		<td>
          <div class="row">
            <div class="large-12 columns">
              <label>Company
                <input type="text" name="company" value="<?php echo $customer->company; ?>" maxlength="30" placeholder="Company">
              </label>
            </div>
          </div>
        </td>
        <td>
        	<div class="row collapse">
        		Website
        	</div>
        	<div class="row collapse">
        		
			    <div class="small-4 large-2 columns">
			      <span class="prefix">http://</span>
			    </div>
			    <div class="small-8 large-10 columns">
			    	
			      <input type="text" name="website"  value="<?php echo $customer->website; ?>" maxlength="30" placeholder="Website">
			      	
			    </div>

			 </div>
        </td>
    </tr>
    <tr>
  		<td colspan="2">
          <div class="row">
            <div class="large-12 columns">
              <input type="submit" id="submit" value="Update" class="button small radius">
            </div>
          </div>
          <h6><small>Last updated at: <?php echo $customer->updatedAt->format('d, M Y H:i:s'); ?><small></h6>
        </td>
    </tr>
   </table>
</form>