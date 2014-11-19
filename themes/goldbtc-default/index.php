<?php if (!defined('VERSION')) { exit; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $view['main']['name']; ?></title>
    <meta name="description" content="<?php echo $view['main']['slogan']; ?>" />
    <link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/style.css" />
    <link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/classes.css" />
</head>

<body>

<div id="header-wrappew">
	<div id="header-name"><p><a href="/"><?php echo $view['main']['name']; ?></a></p></div>
	<div id="header-more"><p><?php echo $view['main']['slogan']; ?></p></div>
	<div id="clear"></div>
</div>
<div id="main-wrapper">
	<div id="main-left">
        <?php echo $view['main']['result_html']; ?>
        <?php echo $view['main']['faucet_input_html']; ?>
        <?php echo $view['main']['faucet_captcha_html']; ?>
        <?php echo $view['main']['faucet_button_html']; ?>
		<br />
		<center><?php echo $view['main']['block_wide_1_html']; ?></center>
	</div>
	<div id="main-right">
		<div id="main-right-inner" class="btc">
			<div id="main-right-inner-block">
				<h4>Rewards:</h4>
				<?php echo $view['main']['reward_list_html']; ?>
				<br/><b>Claim every <?php echo $view['main']['timer']; ?> hours!</b>
			</div>
		</div>
		<div id="main-right-inner" class="earn">
			<div id="main-right-inner-block">
				<h4>Faucet balance:</h4>
				<p class="balance"><?php echo $view['main']['balance']; ?></p>
				<br />		
			</div>		
		</div>
		<div id="main-right-inner" class="links">
				<div id="main-right-inner-block">
					<h4>Get more free bitcoins</h4>
					<p>						    		
          			<a href="http://www.getbitco.com/" class="read-link">Get Bitcoins</a> Up to 250 satoshi<br />
          			<a href="https://www.landofbitcoin.com/" class="read-link">Land of Bitcoin</a> Get free bitcoins<br />
          			<a href="http://freebitco.in/?r=272970" class="read-link">Free Bitcoin</a> Win free Bitcoins<br />
					<?php echo $view['main']['block_narrow_1_html']; ?>
          			</p>					
				</div>
		</div>
	</div>
	<div id="clear"></div>
</div>
<div id="main-full-wrapper">
	<div id="main-full-inner">
		<center><?php echo $view['main']['block_wide_2_html']; ?></center>
	</div>
</div>
<div id="footer-wrapper">
	<div id="footer-split"></div>
	<div id="footer-inner">
		<div id="footer-inner-block">
			<h4>Something you wish</h4>
			<p>In hac habitasse platea dictumst. Sed non ultrices enim. Aliquam erat volutpat. Ut porta convallis nisl non auctor. Nunc mi nulla, ultricies ac tincidunt a, scelerisque et libero. 
			Phasellus sit amet augue fermentum leo facilisis tempor. Nam vel massa laoreet, tristique velit eu, tempor ante. 
			Nunc eget pellentesque eros. Donec tempus, nisl id dictum varius, est diam molestie neque, sit amet commodo justo leo in dui. 
			Nam neque massa, rutrum vel dapibus nec, congue ut risus. Phasellus non lobortis arcu, a congue urna. Fusce sed mi magna. 
			</p>
			<?php echo $view['main']['referral_program_html']; ?>
			<br />
		</div>
	</div>
</div>
<div id="copy">
&copy Copyright 2014. All Rights Reserved. Powered by <a target="_blank" href="http://www.freebitcoinfaucet.org/">Microfaucet</a> Design by <a target="_blank" href="http://www.faucettheme.com/">FaucetTheme</a> | <?php echo $view['main']['login_link_html']; ?>
</div>
</body>

</html>
