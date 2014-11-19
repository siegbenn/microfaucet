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

<div class="container">
    <h1 class="text-center"><?php echo $view['main']['name']; ?></h1>
    <h4 class="text-center text-muted"><?php echo $view['main']['slogan']; ?></h4>
    <hr />
    <?php echo $view['main']['block_full_wide_1_html']; ?>
    <div class="row faucet">
        <div class="col-md-3">
            <div class="text-center"><img src="themes/default/bitcoin.png" width="60" height="60" /></div>
            <h3 class="text-center">Rewards here</h3>
            <hr />
            <?php echo $view['main']['reward_list_html']; ?>
            <hr />
            <h5 class="text-center">Get a reward every <?php echo $view['main']['timer']; ?>!</h5>
            <?php echo $view['main']['block_narrow_1_html']; ?>
        </div>
        <div class="col-md-6">
            <?php echo $view['main']['block_wide_1_html']; ?>
            <h4 class="text-center">Faucet balance: <?php echo $view['main']['balance']; ?></h4>
            <hr />
            <?php echo $view['main']['result_html']; ?>
            <?php echo $view['main']['faucet_input_html']; ?>
            <?php echo $view['main']['faucet_captcha_html']; ?>
            <?php echo $view['main']['faucet_button_html']; ?>
            <?php echo $view['main']['referral_program_html']; ?>
            <?php echo $view['main']['block_wide_2_html']; ?>
        </div>
        <div class="col-md-3">
            <?php echo $view['main']['block_narrow_2_html']; ?>
        </div>
    </div>
    <?php echo $view['main']['block_full_wide_2_html']; ?>
    <hr />
    <p class="text-center">Powered by <a target="_blank" href="http://www.freebitcoinfaucet.org/">Microfaucet</a> | <?php echo $view['main']['login_link_html']; ?></p>
</div>

</body>
</html>
