<?php if (!defined('VERSION')) { exit; } ?>
<html>
<head>
<title><?php echo $view['main']['name']; ?></title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="<?php echo $view['main']['slogan']; ?>" />
<link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/style.css" />
</head>
<body>
<div id="wrap">
  <div id="top">
    <h2> <a href="./"><?php echo $view['main']['name']; ?></a></h2>
    <div id="menu">
      <ul>
        <li><a href="./">home</a></li>
        <li><a href="?do=refferal">referral</a></li>
        <li><a href="?do=contact">contact</a></li>
      </ul>
    </div>
  </div>
  <div class="text-center"><h2><?php echo $view['main']['slogan']; ?></h2></div>
  	<br /><div id="adv_up"><?php echo $view['main']['block_full_wide_1_html']; ?></div> 
  <div id="content">
  
    <div id="left">
			<?php echo $view['main']['result_html']; ?>	
            <?php if ($_GET['do'] == 'refferal'): ?>
			   <?php if ($view['main']['referral_program_html']) : ?>
                <div class="text-center"><br />
   				<b><?php echo $view['main']['referral_program_html']; ?></b>
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
                <br /><div class="text-center"><a href="http://www.getbitco.com/">Get more 250 Satoshi</a></div><br /><br /><div id="adv_left"><?php echo $view['main']['block_narrow_1_html']; ?></div>	  
            <?php else : ?>
            <div class="form">
				<?php echo $view['main']['faucet_input_html']; ?>
				<br />
				<?php echo $view['main']['faucet_captcha_html']; ?>
				<br />
				<?php echo $view['main']['faucet_button_html']; ?>
           </div>
            <?php endif; ?>
    </div>
    <div id="right">
      <div class="box">
      	<div class="text-center">
            <h2>Rewards here</h2>
            <b><?php echo $view['main']['reward_list_html']; ?></b>
            <p>Claim every <?php echo $view['main']['timer']; ?>!</p>
        </div>
        <hr />
            <h3 class="text-center">Faucet balance: <?php echo $view['main']['balance']; ?></h3>
        <hr />
        <br />
		<h2>Related Sites</h2>
        <ul>
          <li><a href="http://www.getbitco.com/">Get Bitcoins</a> <i>Up to 250 satoshi every 2 hours</i></li>
          <li><a href="https://www.landofbitcoin.com/">Land of Bitcoin</a> <i>Get free bitcoins instantly</i></li>
          <li><a href="http://freebitco.in/?r=272970">Free Bitcoin</a> <i>Win free Bitcoins</i></li>
        </ul>
       </div>
    </div>
    <div id="clear"></div>
  </div>
  <br /><div id="adv_up"><?php echo $view['main']['block_full_wide_2_html']; ?></div>
   <div class="text-center">
		<br />
		<?php echo $view['main']['referral_program_html']; ?>
   </div>
  <div id="footer">
    <p>Copyright 2014 <?php echo $view['main']['name']; ?>. All Rights reserved.</p>
	<p>Powered by <a target="_blank" href="http://www.freebitcoinfaucet.org/">Microfaucet</a> Design by <a target="_blank" href="http://www.faucettheme.com/">FaucetTheme</a> | <?php echo $view['main']['login_link_html']; ?></p>
  </div>
</div>
</body>
</html>
