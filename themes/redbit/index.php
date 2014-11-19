<?php if (!defined('VERSION')) { exit; } ?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo $view['main']['name']; ?></title>
<meta name="description" content="<?php echo $view['main']['slogan']; ?>" />
<link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/style.css" />
</head>

<body>
<div id="main">
	<div id="top-block">
		<center>
		<?php echo $view['main']['block_full_wide_1_html']; ?>
		</center>
	</div>
	<div id="container">
		<h1><?php echo $view['main']['name']; ?></h1> <?php echo $view['main']['slogan']; ?>
			
            <?php echo $view['main']['result_html']; ?>
            <?php echo $view['main']['faucet_input_html']; ?>
            <?php echo $view['main']['faucet_captcha_html']; ?>
            <?php echo $view['main']['faucet_button_html']; ?>	
		<p>
		Bitcoin is a consensus network that enables a new payment system and a completely digital money. It is the first decentralized peer-to-peer payment network that is powered by its users with no central authority or middlemen. From a user perspective, Bitcoin is pretty much like cash for the Internet. 
		</p>
		
	    <?php echo $view['main']['referral_program_html']; ?>
		
	</div>
	<div id="clear"></div>
	<div id="bottom">
		<p class="text-center">Powered by <a target="_blank" href="http://www.freebitcoinfaucet.org/">Microfaucet</a> Design by <a target="_blank" href="http://www.faucettheme.com/">FaucetTheme</a> | <?php echo $view['main']['login_link_html']; ?></p>
	</div>
</div>

</body>

</html>
