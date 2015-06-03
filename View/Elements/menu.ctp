<?php

$userName = 'Test Username';
?>
<div class="brid-top-nav">
	<div class="row">
	  	
	  	<div class="small-12 columns">
	  
				<nav class="top-bar" data-topbar role="navigation">
				  <ul class="title-area">
				    <li class="name">
				    
					      	<div id="logo" style="background-size: 195px 33px;width: 67px;position: relative; left: 6px; top: 7px;">
								<div id="clickLogo" title="Go to My Sites"></div>
							</div>
						
				    </li>
				     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
				    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				  </ul>

				  <section class="top-bar-section">
				    <!-- Right Nav Section -->
				    <?php if($userName!=''){ ?>
				    <ul class="right">
				      <li><a href="#"><?php echo AuthComponent::user('Plan.name'); ?></a></li>
				      <li class="divider"></li>
				      <li class="has-dropdown">
				        <a href="#"><i class="fi-torso" style="font-size: 16px; position:relative; top:1px"></i>&nbsp;&nbsp;<?php echo $userName; ?></a>
				        <ul class="dropdown">
				          <li><a href="/users/edit/<?php echo AuthComponent::user('id'); ?>">User Details</a></li>
				          <li><a href="/users/logout/" style="color:#ff1f5e">Logout</a></li>
				        </ul>
				      </li>
				      
				    </ul>
				    <?php } ?>
				    <!-- Left Nav Section -->
				    <ul class="left" style="margin-left:15px;">
				    
				      			<li class="has-dropdown active" >
							        <a href="/subscriptions/orders/" >Payments</a>
							        <ul class="dropdown">
							          <li><a href="/subscriptions/client/">Client Information</a></li>
							          <li><a href="/paymentmethods/index/">Payment Methods</a></li>
							          <li><a href="/subscriptions/orders/">Product Billing</a></li>
							          <li><a href="/subscriptions/transactions/">Billing History</a></li>
							        </ul>
							     </li>
				     
				    </ul>
				  </section>
				</nav>
	
		</div>
	  	
	</div>
</div>