<?php if (!defined('VERSION')) { exit; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title><?php echo $view['main']['name']; ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="<?php echo $view['main']['slogan']; ?>" />
    <link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/style.css" />
    <link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/pink.css" />
</head>
<body>
	<!-- Begin Wrapper -->
	<div id="wrapper">
		<!-- Begin Header Holder -->
		<div id="header-holder">
			<!-- Begin Shell -->
			<div class="shell">
				<!-- Begin Header -->
				<div id="header">
					<div id="navigation">
						<ul>
						    <li><a href="./" <? if (!isset($_GET['do'])) echo 'class="active"'; ?>>Home</a></li>
        					<li><a href="?do=refferal" <? if ($_GET['do'] == 'refferal') echo 'class="active"'; ?>>Referral</a></li>
        					<li><a href="?do=contact" <? if ($_GET['do'] == 'contact') echo 'class="active"'; ?>>Contact</a></li>

						</ul>
					</div>
					<h1 id="logo"><a href="./"><?php echo $view['main']['name']; ?></a></h1>
				</div>
				<!-- End Header -->
			</div>
			<!-- End Shell -->
		</div>
		<!-- End Header Holder -->
		<br /><div class="add-big"><?php echo $view['main']['block_full_wide_1_html']; ?></div>
		<!-- Begin Main Holder -->
		<div id="main-holder">
			<!-- Begin Shell -->
			<div class="shell">
				<!-- Begin Main -->
				<div id="main">
					<div class="cl">&nbsp;</div>
					<!-- Begin Sidebar -->
					<div id="sidebar">
						<h2>Bitcoin World</h2>
						<div class="side-list">
							<ul>
							    <li>
							    	<div class="image">
							    		<img src="themes/<?php echo $view['main']['theme']; ?>/img/budget.png" />
							    	</div>
							    	<div class="entry">
							    		<h3>Rewards here</h3>
							    		<b><?php echo $view['main']['reward_list_html']; ?></b>
										<br />
							    		<h4>Claim every <?php echo $view['main']['timer']; ?>!</h4>
							    	</div>
							    </li>

							    <li>
							    	<div class="image">
							    		<img src="themes/<?php echo $view['main']['theme']; ?>/img/bank.png" />
							    	</div>
							    	<div class="entry">
							    		<h3>Faucet balance:</h3>
							    		<p><?php echo $view['main']['balance']; ?></p>
							    	</div>
							    </li>

							    <li class="last">
							    	<div class="image">
							    		<img src="themes/<?php echo $view['main']['theme']; ?>/img/links.png" />
							    	</div>
							    	<div class="entry">
							    		<h3>Related Sites</h3>							    		
          									<a href="http://www.getbitco.com/" class="read-link">Get Bitcoins</a> <i>Up to 250 satoshi every 2 hours</i><br />
          									<a href="https://www.landofbitcoin.com/" class="read-link">Land of Bitcoin</a> <i>Get free bitcoins instantly</i><br />
          									<a href="http://freebitco.in/?r=272970" class="read-link">Free Bitcoin</a> <i>Win free Bitcoins</i><br />
							    	</div>
							    </li> 							    
							</ul>
							
							<?php echo $view['main']['block_narrow_1_html']; ?>
							
						</div>
					</div>
					<!-- End Sidebar -->
					<!-- Begin Content -->
					<div id="content">
						<div class="article">
							<h2><?php echo $view['main']['slogan']; ?></h2>
								<?php if (!isset($_GET['do'])) echo $view['main']['result_html']; ?>
 					            <?php if ($_GET['do'] == 'refferal'): ?>
								   <?php if ($view['main']['referral_program_html']) : ?>
					                <div class="text-center"><br />
										<?php echo $view['main']['referral_program_html']; ?>
					   				</div>
								  <?php else : ?>
								    <div class="text-center"><br />
								    <h3>Referreal programm is disabled.</h3>
								    ...sorry!
								    </div>
								  <?php endif; ?>
					            <?php elseif ($_GET['do'] == 'contact'): ?>
								    <div class="text-center"><br />
								    <h3>Contact Us</h3>
								    email: myemail@email.com <br />
								    tel: +123456789
								    </div>
								<?php elseif ($view['main']['waiting_time'] > 0): ?>
					                <br /><div class="text-center"><a href="http://www.getbitco.com/">Get more 250 Satoshi</a></div><br /><br /><div class="adv-small"><?php echo $view['main']['block_narrow_2_html']; ?></div>	  
					            <?php else : ?>
					            <div class="form">
									<?php echo $view['main']['faucet_input_html']; ?>
									<br />
									<?php echo $view['main']['faucet_captcha_html']; ?>
									<br />
									<?php echo $view['main']['faucet_button_html']; ?>
					           </div>
				              <?php endif; ?>

							<div class="cl">&nbsp;</div>
						</div>
						<div class="boxes-holder">
							<div class="box">
								<div class="imag">
							    	<img src="themes/<?php echo $view['main']['theme']; ?>/img/wallet.png" />
							    </div>
							    	<h2>Bitcoin Wallet</h2>
									<p><strong>Still don't have Bitcoin wallet?</strong> 
									Blockchain is a free online bitcoin wallet which you can use to make worldwide payments for free. Creating a new bitcoin wallet takes literally a few seconds. You will be able to send and receive payments immediately.</p>
									<p class="more-link text-right"><a href="https://blockchain.info" target="_blank">Get Wallet</a></p>
							</div>
							<div class="box last-box">
								<h2>Something...</h2>
								<p><strong>Lorem ipsum dolor</strong> sit amet, consectetur adipiscing elit. Integer dictum, neque ut imperdiet pellentesque, nulla tellus tempus magna, sed consectetur orci metus a justo. Integer. Lorem ipsum dolor sit amet, consectetur adipiscing elit sed consectetur orci metus a justo. Integer.</p>
								<p class="more-link text-right"><a href="#">Learn More</a></p>
							</div>
							<div class="cl">&nbsp;</div>
						</div>
					</div>
					<!-- End Content -->
					<div class="cl">&nbsp;</div>
				</div>
				<!-- End Main -->
			</div>
			<!-- End Shell -->
		</div>
		<!-- End Main Holder -->
		<div id="footer-push">&nbsp;</div>
	</div>
	<!-- End Wrapper -->
	<div class="add-big"><?php echo $view['main']['block_full_wide_2_html']; ?></div><br />
	<!-- Begin Footer Holder -->
	<div id="footer-holder">
		<!-- Begin Shell -->
		<div class="shell">
			<!-- Begin Footer -->
			<div id="footer">
				<p class="right">
					© Copyright 2014 <?php echo $view['main']['name']; ?>. All Rights reserved.
					Powered by <a target="_blank" href="http://www.freebitcoinfaucet.org/">Microfaucet</a> Design by <a target="_blank" href="http://www.faucettheme.com/">FaucetTheme</a> | <?php echo $view['main']['login_link_html']; ?>
				</p>
				<p>
					<a href="./">Home</a> <span>|</span>  
					<a href="?do=refferal">Referral</a> <span>|</span>
					<a href="?do=contact">Contact</a>
				</p>
			</div>
			<!-- End Footer -->
		</div>
		<!-- End Shell -->
	</div>
	<!-- End Footer Holder -->
	
</body>
</html>