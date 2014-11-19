<?php if (!defined('VERSION')) { exit; } ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $view['main']['name']; ?></title>
    <meta name="description" content="<?php echo $view['main']['slogan']; ?>" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="themes/<?php echo $view['main']['theme']; ?>/style.css" />
</head>
<body>
<div id="header">
	<div id="header-name">
		<h1><?php echo $view['main']['name']; ?></h1>
		<p><?php echo $view['main']['slogan']; ?></p>
	</div>
	<div id="header-line"></div>
</div>
<div id="clear"></div>
<div>
<?php echo $view['main']['block_full_wide_1_html']; ?>
</div>
<div id="clear"></div>
<div id="wrapper">
	<div id="main-block">
	<h3 class="brd1">Sidebar</h3>
	<?php echo $view['main']['block_narrow_2_html']; ?>
	</div>
	<div id="main-center">
	<h3 class="brd2">Get Free Bitcoins</h3>
        <?php echo $view['main']['result_html']; ?>
        <?php echo $view['main']['faucet_input_html']; ?>
        <?php echo $view['main']['faucet_captcha_html']; ?>
        <?php echo $view['main']['faucet_button_html']; ?>
        <?php echo $view['main']['referral_program_html']; ?>
        <?php echo $view['main']['block_narrow_1_html']; ?>
	</div>
	<div id="main-block">
	<h3 class="brd3">About Us</h3>
		<h5 class="text-center">Rewards here</h5>
		<?php echo $view['main']['reward_list_html']; ?>
		<hr />
		<h5 class="text-center">Get a reward every <?php echo $view['main']['timer']; ?>!</h5>
		<h5 class="text-center">Faucet balance: <?php echo $view['main']['balance']; ?></h5>
        <?php echo $view['main']['block_narrow_1_html']; ?>
	</div>
</div>
<div id="clear"></div>
<div id="wide-block">
	<?php echo $view['main']['block_wide_1_html']; ?>
</div>
<div id="clear"></div>
<div id="footer">
	<p>Powered by <a target="_blank" href="http://www.freebitcoinfaucet.org/">Microfaucet</a> Design by <a target="_blank" href="http://www.faucettheme.com/">FaucetTheme</a> | <?php echo $view['main']['login_link_html']; ?></p>
</div>
</body>

</html>
