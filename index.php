<?php

// Copyright (c) 2014 FreeBitcoinFaucet.org
// Website: http://www.freebitcoinfaucet.org/

// Redistribution of a modified or a non-modified copy of this file is not allowed.
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND.
// To support the development of this script, donate bitcoins: http://www.freebitcoinfaucet.org/donate

error_reporting(0);
define('VERSION', 20140821);
define('CONFIG_FILE', 'config.php');

fix_magic_quotes();
$mysqlHost = '';
$mysqlUsername = '';
$mysqlPassword = '';
$mysqlDatabase = '';
$db = false;
$settings = array();
$time = time();

if (file_exists(CONFIG_FILE)) {
    if (!is_readable(CONFIG_FILE)) {
        echo 'Can\'t read the ', CONFIG_FILE, ' file, set read permissions for this file.';
        exit;
    }

    require_once CONFIG_FILE;
}

if (!$mysqlUsername && ((file_exists(CONFIG_FILE) && !is_writable(CONFIG_FILE)) || (!file_exists(CONFIG_FILE) && !is_writable(dirname(__FILE__))))) {
    echo 'Edit the ', CONFIG_FILE, ' file manually to setup the faucet.';
    exit;
}

if ($mysqlUsername) {
    $db = mysqli_connect($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDatabase);
    if (!$db) {
        echo 'Can\'t connect to the MySQL. Error: ', mysqli_connect_error(), '<br />', 'You may want edit or delete the ', CONFIG_FILE, ' file.';
        exit;
    }
    mysqli_set_charset($db, 'latin1');
    $result = mysqli_query($db, "select * from microfaucet_settings");
    if (mysqli_errno($db) && mysqli_errno($db) !== 1146) {
        echo 'MySQL error: ', mysqli_error($db);
        exit;
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $settings[$row['name']] = $row['value'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$db) {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Microfaucet setup</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Microfaucet setup</h1>
            <hr />
            <p class="text-center">Fill out the form below to setup the faucet.</p>
            <div class="row">
                <form class="col-md-6 col-md-offset-3" method="POST" autocomplete="off">
                    <div class="form-group">
                        <label for="mysql_host">MySQL host</label>
                        <input class="form-control" type="text" name="mysql_host" id="mysql_host" value="localhost" />
                    </div>
                    <div class="form-group">
                        <label for="mysql_username">MySQL username</label>
                        <input class="form-control" type="text" name="mysql_username" id="mysql_username" />
                    </div>
                    <div class="form-group">
                        <label for="mysql_host">MySQL password</label>
                        <input class="form-control" type="password" name="mysql_password" id="mysql_password" />
                    </div>
                    <div class="form-group">
                        <label for="mysql_host">MySQL database name</label>
                        <input class="form-control" type="text" name="mysql_database" id="mysql_database" value="microfaucet" />
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-success btn-lg">Setup the faucet</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif (($_SERVER['REQUEST_METHOD'] === 'POST' && !$db) || ($_SERVER['REQUEST_METHOD'] === 'GET' && $db && !isset($settings['installed_at']))) {
    if (!$db) {
        if (empty($_POST['mysql_host']) || empty($_POST['mysql_username']) || empty($_POST['mysql_database'])) {
            echo 'Missing MySQL host, username or database.<br /><a href="">Go back and fill out the form to setup the faucet</a>.';
            exit;
        }

        $db = mysqli_connect($_POST['mysql_host'], $_POST['mysql_username'], $_POST['mysql_password'], $_POST['mysql_database']);
        if (!$db) {
            echo 'Can\'t connect to the MySQL. Error: ', mysqli_connect_error(), '<br />', '<a href="">Go back and try again</a>.';
            exit;
        }
        mysqli_set_charset($db, 'latin1');
    }

    $passphrase = get_random_string(4) . ' ' . get_random_string(4) . ' ' . get_random_string(4) . ' ' . get_random_string(4) . ' ' . get_random_string(4) . ' ' . get_random_string(4);
    $escapedPassphrase = mysqli_real_escape_string($db, sha1(strtolower(str_replace(' ', '', $passphrase))));

    $query = "DROP TABLE IF EXISTS `microfaucet_settings`";
    mysqli_query($db, $query);
    $query = "CREATE TABLE IF NOT EXISTS `microfaucet_settings` (`name` VARCHAR(45) NOT NULL, `value` TEXT NOT NULL,  PRIMARY KEY (`name`)) ENGINE = MyISAM DEFAULT CHARACTER SET = latin1 COLLATE = latin1_general_ci";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('name', 'Microfaucet')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('slogan', 'Just another Microfaucet')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('timer', '360')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('rewards', '100')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('referral_percentage', '20')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('theme', 'default')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('passphrase', '$escapedPassphrase')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('balance', 'N/A')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('balance_checked_at', '1')";
    mysqli_query($db, $query);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('behind_proxy', '0')";
    mysqli_query($db, $query);
    $escapedValue = mysqli_real_escape_string($db, '<h5 class="text-center">My favorite links</h5>' . "\n" . '<p class="text-center"><a target="_blank" href="http://bitcoin.org/">Bitcoin.org</a></p>' . "\n" . '<p class="text-center"><a target="_blank" href="http://www.landofbitcoin.com/">Land of Bitcoin</a></p>' . "\n");
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('block_narrow_2_html', '$escapedValue')";
    mysqli_query($db, $query);
    $escapedTime = mysqli_real_escape_string($db, $time);
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('installed_at', '$escapedTime')";
    mysqli_query($db, $query);
    $query = "DROP TABLE IF EXISTS `microfaucet_users`";
    mysqli_query($db, $query);
    $query = "CREATE TABLE IF NOT EXISTS `microfaucet_users` (`id` INT NOT NULL AUTO_INCREMENT, `username` VARCHAR(45) NOT NULL, `ip` INT UNSIGNED NOT NULL, `referral_earnings` INT SIGNED NOT NULL DEFAULT 0, `claimed_at` INT UNSIGNED NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `username_UNIQUE` (`username` ASC)) ENGINE = MyISAM DEFAULT CHARACTER SET = latin1 COLLATE = latin1_general_ci";
    mysqli_query($db, $query);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $escapedMysqlHost = addslashes($_POST['mysql_host']);
        $escapedMysqlUsername = addslashes($_POST['mysql_username']);
        $escapedMysqlPassword = addslashes($_POST['mysql_password']);
        $escapedMysqlDatabase = addslashes($_POST['mysql_database']);

        $content = <<<END
<?php

// Enter MySQL infos
\$mysqlHost = '$escapedMysqlHost';
\$mysqlUsername = '$escapedMysqlUsername';
\$mysqlPassword = '$escapedMysqlPassword';
\$mysqlDatabase = '$escapedMysqlDatabase';

END;

        file_put_contents(CONFIG_FILE, $content);
    }

    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Microfaucet setup</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Success, the faucet is up!</h1>
            <hr />
            <h3 class="text-center">Write down the secret passphrase, this will allow you to access the admin interface.</h3>
            <hr />
            <h2 class=text-center><?php echo htmlspecialchars($passphrase); ?></h2>
            <hr />
            <div class="text-center">
                <a class="btn btn-success btn-lg" href="">Check the faucet</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page']) && $_GET['page'] === 'login' && $settings && !isset($settings['passphrase'])) {
    $query = "delete from microfaucet_settings where name = 'microwallet_api_key'";
    mysqli_query($db, $query);

    $passphrase = get_random_string(4) . ' ' . get_random_string(4) . ' ' . get_random_string(4) . ' ' . get_random_string(4);
    $escapedPassphrase = mysqli_real_escape_string($db, sha1(strtolower(str_replace(' ', '', $passphrase))));
    $query = "INSERT INTO `microfaucet_settings` (`name`, `value`) VALUES ('passphrase', '$escapedPassphrase')";
    mysqli_query($db, $query);

    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Microfaucet setup</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">New passphrase generated!</h1>
            <hr />
            <h3 class="text-center">Write down the secret passphrase, this will allow you to access the admin interface.</h3>
            <hr />
            <h2 class=text-center><?php echo htmlspecialchars($passphrase); ?></h2>
            <hr />
            <div class="text-center">
                <a class="btn btn-success btn-lg" href="?page=login">Login</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page']) && $_GET['page'] === 'diagnostics') {
    echo '<pre>';
    var_dump($_SERVER['REMOTE_ADDR']);
    var_dump($_SERVER['HTTP_X_FORWARDED_FOR']);
    var_dump($settings['behind_proxy']);
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page']) && $_GET['page'] === 'login') {
    session_start();
    $_SESSION['t'] = get_random_string(5);

    if ($_SESSION['logged_in']) {
        header('Location: ?page=admin');
        exit;
    }

    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Microfaucet admin</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Microfaucet admin</h1>
            <hr />
            <div class="row">
                <form class="col-md-6 col-md-offset-3" method="POST">
                    <div class="form-group text-center">
                        <label for="passphrase"><h3>Enter the passphrase to access the admin</h3></label>
                        <input class="form-control input-lg" type="password" name="passphrase" id="passphrase" />
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-success btn-lg">Login</button>
                    </div>
                </form>
                <div class="col-md-6 col-md-offset-3">
                    <div class="well well-sm">
                        <strong>Lost the passphrase?</strong><br />
                        Login into phpmyadmin and run this SQL command:<br />
                        <code>delete from <?php echo htmlspecialchars($mysqlDatabase); ?>.microfaucet_settings where name = 'passphrase';</code>
                        This will delete your old passphrase.<br />
                        After that <a href="?page=login">refresh this page</a> to generate a new passphrase.
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] === 'login') {
    if (sha1(strtolower(str_replace(' ', '', $_POST['passphrase']))) !== $settings['passphrase']) {
        echo 'Invalid passphrase.', '<br />', '<a href="?page=login">Try again</a>.';
        exit;
    }

    session_start();
    $_SESSION['logged_in'] = true;
    header('Location: ?page=admin');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page']) && $_GET['page'] === 'admin') {
    session_start();
    if (!$_SESSION['logged_in']) {
        echo 'You are not logged in.', '<br />', '<a href="?page=login">Login here</a>.';
        exit;
    }

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
        $query = "insert into microfaucet_settings (name, value) values('behind_proxy', '1') on duplicate key update value = values(value)";
        mysqli_query($db, $query);
    } else {
        $query = "insert into microfaucet_settings (name, value) values('behind_proxy', '0') on duplicate key update value = values(value)";
        mysqli_query($db, $query);
    }

    $content = file_get_contents('themes/' . $settings['theme'] . '/index.php');
    preg_match_all('/(block\_.+?\_html)/', $content, $matches);
    $blocks = array();
    foreach ($matches[1] as $block) {
        if (in_array($block, $blocks)) continue;
        $blocks[] = $block;
    }

    $themes = array();
    foreach (glob('themes/*', GLOB_ONLYDIR) as $theme) {
        if (!$content = file_get_contents(dirname(__FILE__) . '/' . $theme . '/style.css')) {
            continue;
        }

        $themeName = str_replace('themes/', '', $theme);
        $paidTheme = parse_paid_theme($content);
        if ($paidTheme) {
            $themeTitle = $themeName . ' (PAID THEME, additional ' . $paidTheme['percentage'] . '% referral commission go to the theme\'s author)';
        } else {
            if (preg_match('/Type\: paid/i', $content)) {
                continue;
            }
            $themeTitle = $themeName . ' (FREE THEME)';
        }
        $themes[$themeName] = $themeTitle;
    }
    $reward = get_rewards();
    $reward = $reward['average_reward'];

    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Microfaucet admin</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Microfaucet admin <a class="btn btn-default" href="<?php echo htmlspecialchars(get_main_url(true)); ?>">Back to the faucet</a> <a class="btn btn-default" href="?page=logout">Logout</a></h1>
            <hr />
            <?php if ((int) VERSION < (int) $settings['secure_version']): ?>
                <div class="alert alert-danger text-center">New version of the faucet script is available. It's a security update, your faucet is disabled until you upgrade to the new version. <a target="_blank" href="http://www.freebitcoinfaucet.org/upgrade">Click here to upgrade!</a></div>
            <?php else: ?>
                <?php if ((int) VERSION < (int) $settings['latest_version']): ?>
                    <div class="alert alert-info text-center">New version of the faucet script is available. <a target="_blank" href="http://www.freebitcoinfaucet.org/upgrade">Click here to upgrade!</a></div>
                <?php endif; ?>
                <?php if (!is_enabled()): ?>
                    <div class="alert alert-danger text-center">You have to fill out all the required fields to enable the faucet.</a></div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="row">
                <form method="POST">
                    <input type="hidden" name="t" id="t" value="<?php echo htmlspecialchars($_SESSION['t']); ?>" />
                    <div class="col-md-6">
                        <h3 class="text-center">Basic settings</h3>
                        <div class="form-group">
                            <label for="enabled">Enable or disable the faucet <span class="text-danger">(required)</span></label>
                            <select class="form-control" name="enabled" id="enabled">
                                <option value="0"<?php if (!$settings['enabled']) { echo ' selected="selected"'; } ?>>disable</option>
                                <option value="1"<?php if ($settings['enabled']) { echo ' selected="selected"'; } ?>>enable</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Faucet name <span class="text-danger">(required)</span></label>
                            <p>The name of the faucet.</p>
                            <input class="form-control" type="text" name="name" id="name" value="<?php echo htmlspecialchars($settings['name']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="slogan">Faucet slogan</label>
                            <p>An optional slogan, displayed below the title usually.</p>
                            <input class="form-control" type="text" name="slogan" id="slogan" value="<?php echo htmlspecialchars($settings['slogan']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="timer">Timer in minutes <span class="text-danger">(required)</span></label>
                            <p>How often can the visitors claim a reward in minutes? (360 = every 6 hours). Must be between 15-3000.</p>
                            <input class="form-control" type="text" name="timer" id="timer" value="<?php echo htmlspecialchars($settings['timer']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="rewards">Rewards <span class="text-danger">(required)</span></label>
                            <p>Comma-separated list of rewards in satoshi, 1 satoshi = 0.00000001 BTC. Weighted rewards allowed, for example: 50*20, 300*5, 500</p>
                            <input class="form-control" type="text" name="rewards" id="rewards" value="<?php echo htmlspecialchars($settings['rewards']); ?>" />
                            <p class="text-center">Your current average: <?php echo htmlspecialchars($reward); ?></p>
                        </div>
                        <div class="form-group">
                            <label for="referral_percentage">Referral earnings percentage</label>
                            <p>The percentage of the referral earnings. Must be between 0-100.<br />0 = referral program disabled.</p>
                            <input class="form-control" type="text" name="referral_percentage" id="referral_percentage" value="<?php echo htmlspecialchars($settings['referral_percentage']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="theme">Select a theme for the faucet</label>
                            <select class="form-control" name="theme" id="theme">
                                <?php foreach ($themes as $themeName => $themeTitle): ?>
                                    <option value="<?php echo htmlspecialchars($themeName); ?>"<?php if ($themeName === $settings['theme']) { echo ' selected="selected"'; } ?>><?php echo htmlspecialchars($themeTitle); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p><a target="_blank" href="http://www.freebitcoinfaucet.org/themes">Download more themes here</a></p>
                        </div>
                        <div class="form-group">
                            <label for="plugins_enabled">Enable plugins?</label>
                            <select class="form-control" name="plugins_enabled" id="plugins_enabled">
                                <option value="0"<?php if (!$settings['plugins_enabled']) { echo ' selected="selected"'; } ?>>plugins disabled</option>
                                <option value="1"<?php if ($settings['plugins_enabled']) { echo ' selected="selected"'; } ?>>plugins enabled</option>
                            </select>
                            <p>Downloadable plugins will be added in the future.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-center">API settings</h3>
                        <div class="form-group">
                            <label for="microwallet_api_key">Microwallet API key <span class="text-danger">(required)</span></label>
                            <p>Get your Microwallet API key from here: <a target="_blank" href="https://www.microwallet.org/api">https://www.microwallet.org/api</a></p>
                            <input class="form-control" type="text" name="microwallet_api_key" id="microwallet_api_key" value="<?php echo htmlspecialchars($settings['microwallet_api_key']); ?>" />
                        </div>
                        <hr />
                        <p><strong>Choose a CAPTCHA provider <span class="text-danger">(required)</span></strong><br />You need to fill out the reCAPTCHA API key fields for reCAPTCHA or the Solvemedia API key fields for Solvemedia CAPTCHA.</p>
                        <div class="form-group">
                            <label for="recaptcha_public_key">reCAPTCHA public key</label>
                            <p>If you choose reCAPTCHA: get your reCAPTCHA API keys from here: <a target="_blank" href="https://www.google.com/recaptcha/">https://www.google.com/recaptcha/</a></p>
                            <input class="form-control" type="text" name="recaptcha_public_key" id="recaptcha_public_key" value="<?php echo htmlspecialchars($settings['recaptcha_public_key']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="recaptcha_private_key">reCAPTCHA private key</label>
                            <input class="form-control" type="text" name="recaptcha_private_key" id="recaptcha_private_key" value="<?php echo htmlspecialchars($settings['recaptcha_private_key']); ?>" />
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="solvemedia_challenge_key">Solvemedia challenge key</label>
                            <p>If you choose Solvemedia: get your Solvemedia API keys here: <a target="_blank" href="http://solvemedia.com/publishers/">http://solvemedia.com/publishers/</a></p>
                            <input class="form-control" type="text" name="solvemedia_challenge_key" id="solvemedia_challenge_key" value="<?php echo htmlspecialchars($settings['solvemedia_challenge_key']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="solvemedia_verification_key">Solvemedia verification key</label>
                            <input class="form-control" type="text" name="solvemedia_verification_key" id="solvemedia_verification_key" value="<?php echo htmlspecialchars($settings['solvemedia_verification_key']); ?>" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group text-center">
                        <button class="btn btn-success btn-lg">Save the settings</button>
                    </div>
                    <?php if ($blocks): ?>
                        <hr />
                        <h3 class="text-center">Blocks</h3>
                        <div class="well well-sm text-center">Your current theme has some pre-defined blocks. It allows you to easily insert advertisements or any HTML code without editing the theme file.<br /><a target="_blank" href="?show_blocks=1">Click here to check the blocks on the faucet</a></div>
                        <?php foreach ($blocks as $block): ?>
                            <div class="form-group">
                                <label for="<?php echo htmlspecialchars($block); ?>"><?php echo htmlspecialchars($block); ?></label>
                                <textarea class="form-control" rows="6" name="<?php echo htmlspecialchars($block); ?>" id="<?php echo htmlspecialchars($block); ?>"><?php echo htmlspecialchars($settings[$block]); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-group text-center">
                            <button class="btn btn-success btn-lg">Save the settings</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] === 'admin') {
    session_start();
    if (!$_SESSION['logged_in']) {
        echo 'You are not logged in.', '<br />', '<a href="?page=login">Login here</a>.';
        exit;
    }

    if ($_SESSION['t'] !== $_POST['t']) {
        echo 'Invalid token.<br /><a href="?page=login">Try again</a>.';
        exit;
    }

    foreach ($_POST as $name => $value) {
        if ($name === 't') continue;

        if ($name === 'name') {
            if (strlen($value) === 0 || strlen($value) > 200) {
                $value = 'Microfaucet';
            }
        }

        if ($name === 'timer') {
            $value = (int) $value;
            if ($value < 15 || $value > 3000) {
                $value = 360;
            }
        }

        if ($name === 'rewards') {
            $rewards = get_rewards($value);
            if (!$rewards) {
                $value = '50, 100, 150';
            }
        }

        if ($name === 'referral_percentage') {
            $value = (int) $value;
            if ($value < 0 || $value > 100) {
                $value = 20;
            }
        }

        if ($name === 'microwallet_api_key' && $settings['microwallet_api_key'] !== $value) {
           $query = "insert into microfaucet_settings (name, value) values('balance_checked_at', '1') on duplicate key update value = values(value)";
            mysqli_query($db, $query);
        }

        $escapedName = mysqli_real_escape_string($db, $name);
        $escapedValue = mysqli_real_escape_string($db, $value);
        $query = "insert into microfaucet_settings (name, value) values('$escapedName', '$escapedValue') on duplicate key update value = values(value)";
        mysqli_query($db, $query);
    }

    header('Location: ?page=admin');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page']) && $_GET['page'] === 'logout') {
    session_start();
    session_destroy();

    header('Location: ' . get_main_url());
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_for_update();

    if (!is_enabled()) {
        header('Location: ' . get_main_url());
        exit;
    }

    set_view_variables();

    if ($settings['recaptcha_public_key'] && $settings['recaptcha_private_key']) {
        $captchaChallange = $_POST['recaptcha_challenge_field'];
        $captchaResponse = $_POST['recaptcha_response_field'];
        $recaptcha = true;
    } else {
        $captchaChallange = $_POST['adcopy_challenge'];
        $captchaResponse = $_POST['adcopy_response'];
        $recaptcha = false;
    }

    $view['main']['result_html'] = '';
    $view['main']['waiting_time'] = 0;
    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
        if (preg_match('/^[A-Za-z0-9\.\+\-\_\@]{3,50}$/', $username)) {
            $microwallet = new Microwallet($settings['microwallet_api_key']);
            $escapedUsername = mysqli_real_escape_string($db, $username);

            mysqli_query($db, "lock tables microfaucet_users write");
            $result = mysqli_query($db, "select * from microfaucet_users where username = '$escapedUsername'");
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row['referral_earnings'] >= 100) {
                    $referralEarningsInSatoshi = (int) ($row['referral_earnings'] / 100);
                    $result = $microwallet->sendReferralEarnings($username, $referralEarningsInSatoshi);
                    if ($result['success']) {
                        $escapedReferralEarnings = mysqli_real_escape_string($db, $referralEarningsInSatoshi * 100);
                        mysqli_query($db, "update microfaucet_users set referral_earnings = referral_earnings - $escapedReferralEarnings where username = '$escapedUsername'");
                        $view['main']['result_html'] .= $result['html'];
                    }
                }
            }
            mysqli_query($db, "unlock tables");

            if (!empty($captchaChallange) && !empty($captchaResponse)) {
                $ip = get_ip();
                if ($recaptcha) {
                    $response = @file('https://www.google.com/recaptcha/api/verify?privatekey=' . $settings['recaptcha_private_key'] . '&challenge=' . rawurlencode($captchaChallange). '&response=' . rawurlencode($captchaResponse) . '&remoteip=' . $ip);
                } else {
                    $response = @file('http://verify.solvemedia.com/papi/verify?privatekey=' . $settings['solvemedia_verification_key'] . '&challenge=' . rawurlencode($captchaChallange) . '&response=' . rawurlencode($captchaResponse) . '&remoteip=' . $ip);
                }
                if (isset($response[0]) && trim($response[0]) === 'true') {
                    $escapedIp = mysqli_real_escape_string($db, sprintf('%u', ip2long($ip)));
                    mysqli_query($db, "lock tables microfaucet_users write");
                    $result = mysqli_query($db, "select * from microfaucet_users where username = '$escapedUsername' or ip = '$escapedIp' order by claimed_at desc");
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        if ($row === null || $row['claimed_at'] <= $time - ($settings['timer'] * 60)) {
                            $amount = $rewards['random_reward'];
                            $result = $microwallet->send($username, $amount);
                            $view['main']['result_html'] .= $result['html'];
                            if ($result['success']) {
                                $view['main']['claimed'] = true;
                                $view['main']['claimed_amount'] = $amount;
                                $escapedClaimedAt = mysqli_real_escape_string($db, $time);
                                $result = mysqli_query($db, "insert into microfaucet_users (username, ip, claimed_at) values ('$escapedUsername', '$escapedIp', '$escapedClaimedAt') on duplicate key update ip = values(ip), claimed_at = values(claimed_at)");

                                if (isset($_COOKIE['r']) && $_COOKIE['r'] && preg_match('/^[A-Za-z0-9\.\+\-\_\@]{3,50}$/', $_COOKIE['r']) && $_COOKIE['r'] !== $username) {
                                    $escapedR = mysqli_real_escape_string($db, $_COOKIE['r']);
                                    $referralAmount = (int) ($amount * $settings['referral_percentage']);
                                    $escapedReferralAmount = mysqli_real_escape_string($db, $referralAmount);
                                    $result = mysqli_query($db, "insert into microfaucet_users (username, ip, referral_earnings, claimed_at) values ('$escapedR', '0', '$escapedReferralAmount', '0') on duplicate key update referral_earnings = referral_earnings + '$escapedReferralAmount'");

                                    $result = mysqli_query($db, "select * from microfaucet_users where username = '$escapedR'");
                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        if ($row['referral_earnings'] >= 100000) {
                                            $referralEarningsInSatoshi = (int) ($row['referral_earnings'] / 100);
                                            $result = $microwallet->sendReferralEarnings($_COOKIE['r'], $referralEarningsInSatoshi);
                                            if ($result['success']) {
                                                $escapedReferralEarnings = mysqli_real_escape_string($db, $referralEarningsInSatoshi * 100);
                                                mysqli_query($db, "update microfaucet_users set referral_earnings = referral_earnings - $escapedReferralEarnings where username = '$escapedR'");
                                            }
                                        }
                                    }

                                }
                            }
                        } else {
                            $waitingTime = ($row['claimed_at'] + ($settings['timer'] * 60)) - $time;
                            if ($waitingTime > 0) {
                                $view['main']['waiting_time'] = $waitingTime;
                                $waitingTime = format_timer($waitingTime);
                            }
                            $view['main']['result_html'] .= '<div class="alert alert-danger">You can get a reward again in ' . htmlspecialchars($waitingTime) . '.</div>';
                        }
                    } else {
                        $view['main']['result_html'] .= '<div class="alert alert-danger text-center">An error occured.</div>';
                    }

                    if ($view['main']['claimed']) {
                        $paidTheme = parse_paid_theme();
                        if ($paidTheme) {
                            $escapedPaidThemeUsername = mysqli_real_escape_string($db, $paidTheme['username']);
                            $paidThemeAmount = (int) ($amount * $paidTheme['percentage']);
                            $escapedPaidThemeAmount = mysqli_real_escape_string($db, $paidThemeAmount);
                            $result = mysqli_query($db, "insert into microfaucet_users (username, ip, referral_earnings, claimed_at) values ('$escapedPaidThemeUsername', '0', '$escapedPaidThemeAmount', '0') on duplicate key update referral_earnings = referral_earnings + '$escapedPaidThemeAmount'");

                            $result = mysqli_query($db, "select * from microfaucet_users where username = '$escapedPaidThemeUsername'");
                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                if ($row['referral_earnings'] >= 100000) {
                                    $referralEarningsInSatoshi = (int) ($row['referral_earnings'] / 100);
                                    $result = $microwallet->sendReferralEarnings($paidTheme['username'], $referralEarningsInSatoshi);
                                    if ($result['success']) {
                                        $escapedReferralEarnings = mysqli_real_escape_string($db, $referralEarningsInSatoshi * 100);
                                        mysqli_query($db, "update microfaucet_users set referral_earnings = referral_earnings - $escapedReferralEarnings where username = '$escapedPaidThemeUsername'");
                                    }
                                }
                            }
                        }
                    }
                    mysqli_query($db, "unlock tables");
                } else {
                    $view['main']['result_html'] .= '<div class="alert alert-danger">Wrong captcha, try again!</div>';
                }
            } else {
                $view['main']['result_html'] .= '<div class="alert alert-danger text-center">Missing captcha!</div>';
            }
        } else {
            $view['main']['result_html'] .= '<div class="alert alert-danger text-center">Invalid Bitcoin address!</div>';
        }
    } else {
        $view['main']['result_html'] .= '<div class="alert alert-danger text-center">Missing Bitcoin address!</div>';
    }

    if ($settings['plugins_enabled']) {
        foreach (glob('plugins/*', GLOB_ONLYDIR) as $plugin) {
            include dirname(__FILE__) . '/' . $plugin . '/index.php';
            call_user_func(str_replace('plugins/', '', $plugin) . '_plugin');
        }
    }

    require 'themes/' . $settings['theme'] . '/index.php';
    exit;
} else {
    if (!isset($_GET['page']) || strlen($_GET['page']) === 0) {
        $page = 'index';
    } else {
        $page = $_GET['page'];
    }
    $isReadable = is_readable('themes/' . $settings['theme'] . '/' . $page . '.php');
    if (!preg_match('/^[a-z0-9\-]+$/', $page) || (!$isReadable && !is_readable('themes/default/' . $page . '.php'))) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo 'No such page.<br /><a href="' . htmlspecialchars(get_main_url(true)) . '">Main page</a>';
        exit;
    }

    if (!$isReadable) {
        $settings['theme'] = 'default';
    }

    if ($page !== 'index') {
        require 'themes/' . $settings['theme'] . '/' . $page . '.php';
        exit;
    }

    if (isset($_GET['r']) && $_GET['r']) {
        $_GET['r'] = trim($_GET['r']);
        if (preg_match('/^[A-Za-z0-9\.\+\-\_\@]{3,50}$/', $_GET['r'])) {
            setcookie('r', $_GET['r'], $time + 60 * 60 * 24 * 30);
        }
    }

    check_for_update();
    update_balance();
    set_view_variables();

    $view['main']['result_html'] = '';
    $view['main']['waiting_time'] = 0;
    if (is_enabled()) {
        $waitingTime = 0;
        $ip = get_ip();
        $username = false;
        if (isset($_GET['u']) && $_GET['u']) {
            $username = trim($_GET['u']);
        }
        $escapedIp = mysqli_real_escape_string($db, sprintf('%u', ip2long($ip)));
        if ($username) {
            $escapedUsername = mysqli_real_escape_string($db, $username);
            $query = "select * from microfaucet_users where username = '$escapedUsername' or ip = '$escapedIp' order by claimed_at desc";
        } else {
            $query = "select * from microfaucet_users where ip = '$escapedIp' order by claimed_at desc";
        }
        $result = mysqli_query($db, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $waitingTime = ($row['claimed_at'] + ($settings['timer'] * 60)) - $time;
                if ($waitingTime > 0) {
                    $view['main']['waiting_time'] = $waitingTime;
                    $waitingTime = format_timer($waitingTime);
                    $view['main']['result_html'] .= '<div class="alert alert-info">You can get a reward again in ' . htmlspecialchars($waitingTime) . '.</div>';
                }
            }
        }
    } else {
        $view['main']['result_html'] .= '<div class="alert alert-danger text-center">This faucet is disabled temporarily.<br />If you are the admin, <a href="?page=login">log in to find out why</a>.</div>';
    }

    if ($settings['plugins_enabled']) {
        foreach (glob('plugins/*', GLOB_ONLYDIR) as $plugin) {
            include dirname(__FILE__) . '/' . $plugin . '/index.php';
            call_user_func(str_replace('plugins/', '', $plugin) . '_plugin');
        }
    }

    require 'themes/' . $settings['theme'] . '/' . $page . '.php';
    exit;
}

/////////////////
/// Functions ///
/////////////////
function get_random_string($length)
{
    $key = '';
    $keys = array_merge(range('A', 'Z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

function get_main_url($omitHost = false)
{
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === '443') ? 'https://' : 'http://';
    if ($omitHost) {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    return $protocol . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
}

function check_for_update()
{
    if (isset($GLOBALS['settings']['update_checked_at']) && $GLOBALS['settings']['update_checked_at'] + 3600 > $GLOBALS['time']) {
        return;
    }

    $referrer = get_main_url();
    $reward = get_rewards();
    $reward = (int) $reward['average_reward'];

    $result = file_get_contents('http://www.freebitcoinfaucet.org/api/v1/update?referrer=' . rawurlencode($referrer) . '&timer=' . rawurlencode($GLOBALS['settings']['timer']) . '&reward=' . rawurlencode($reward) . '&enabled=' . rawurlencode(is_enabled()));

    $escapedValue = mysqli_real_escape_string($GLOBALS['db'], $GLOBALS['time']);
    $query = "insert into microfaucet_settings (name, value) values('update_checked_at', '$escapedValue') on duplicate key update value = values(value)";
    mysqli_query($GLOBALS['db'], $query);

    if (!$result) {
        return;
    }
    $result = json_decode($result);
    if (!$result || !$result->latest_version || !$result->secure_version) {
        return;
    }

    $escapedValue = mysqli_real_escape_string($GLOBALS['db'], $result->latest_version);
    $query = "insert into microfaucet_settings (name, value) values('latest_version', '$escapedValue') on duplicate key update value = values(value)";
    mysqli_query($GLOBALS['db'], $query);
    $escapedValue = mysqli_real_escape_string($GLOBALS['db'], $result->secure_version);
    $query = "insert into microfaucet_settings (name, value) values('secure_version', '$escapedValue') on duplicate key update value = values(value)";
    mysqli_query($GLOBALS['db'], $query);

    if ((int) VERSION < (int) $result->secure_version) {
        header('Location: ' . get_main_url());
        exit;
    }
}

function get_ip()
{
    if (!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        echo 'Invalid IP.<br /><a href="' . htmlspecialchars(get_main_url()) . '">Main page</a>';
        exit;
    }

    if ($GLOBALS['settings']['behind_proxy']) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $_SERVER['HTTP_X_FORWARDED_FOR'] = array_pop($ipList);
        } else {
            $_SERVER['HTTP_X_FORWARDED_FOR'] = false;
        }

        if (filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $query = "insert into microfaucet_settings (name, value) values('behind_proxy', '0') on duplicate key update value = values(value)";
            mysqli_query($GLOBALS['db'], $query);
            $GLOBALS['settings']['behind_proxy'] = '0';

            return $_SERVER['REMOTE_ADDR'];
        }
    }

    return $_SERVER['REMOTE_ADDR'];
}

function is_enabled()
{
    if (VERSION >= $GLOBALS['settings']['secure_version'] && $GLOBALS['settings']['enabled'] && $GLOBALS['settings']['microwallet_api_key'] && (($GLOBALS['settings']['recaptcha_public_key'] && $GLOBALS['settings']['recaptcha_private_key']) || ($GLOBALS['settings']['solvemedia_challenge_key'] && $GLOBALS['settings']['solvemedia_verification_key']))) {
        return 1;
    } else {
        return 0;
    }
}

function get_rewards($rewards = null)
{
    if (is_null($rewards)) {
        $rewards = $GLOBALS['settings']['rewards'];
    }
    $rewards = explode(',', $rewards);
    $rewardList = array();
    $totalMultiplier = 0;
    foreach ($rewards as $reward) {
        @list($reward, $multiplier) = explode('*', $reward);
        $reward = (int) trim(abs($reward));
        $multiplier = (int) trim(abs($multiplier));
        if (!$multiplier) $multiplier = 1;
        if (!$reward) continue;
        if (!isset($rewardList[$reward])) {
            $rewardList[$reward] = 0;
        }
        $rewardList[$reward] += $multiplier;
        $totalMultiplier += $multiplier;
    }
    krsort($rewardList);
    $result['reward_list'] = array();
    $result['reward_list_html'] = '';
    $result['full_reward_list'] = array();
    $totalAmount = 0;
    foreach ($rewardList as $reward => $multiplier) {
        $odds = round($totalMultiplier / $multiplier);
        if ($odds > 10000)  {
            $percentage = '<0.01%';
        } else {
            $percentage = rtrim(rtrim(number_format($multiplier / $totalMultiplier * 100, 2), '0'), '.') . '%';
        }
        $result['reward_list'][] = array(
            'reward' => $reward,
            'percentage' => $percentage,
            'odds' => '1:' . $odds,
        );
        $result['reward_list_html'] .= '<li>' . htmlspecialchars($reward) . ' satoshi <span class="percentage">(' . htmlspecialchars($percentage) . ')</span></li>';
        $result['full_reward_list'] = array_merge($result['full_reward_list'], array_fill(0, $multiplier, $reward));
        $totalAmount += $reward;
    }
    $result['reward_list_html'] = '<ul id="reward-list">' . $result['reward_list_html'] . '</ul>';

    if (!$result['reward_list']) {
        return false;
    }

    $result['random_reward'] = $result['full_reward_list'][mt_rand(0, count($result['full_reward_list']) - 1)];
    $result['average_reward'] = (int) ($totalAmount / $totalMultiplier);

    return $result;
}

function format_timer($totalSeconds)
{
    $totalSeconds = (int) abs($totalSeconds);
    $hours = (int) floor($totalSeconds / 3600);
    $minutes = (int) floor(($totalSeconds - ($hours * 3600)) / 60);
    $seconds = $totalSeconds % 60;

    $result = '';
    if ($hours === 1) {
        $result .= '1 hour';
    } elseif ($hours > 1) {
        $result .= $hours . ' hours';
    }
    if ($hours && $minutes) {
        $result .= ', ';
    }
    if ($minutes === 1) {
        $result .= '1 min';
    } elseif ($minutes > 1) {
        $result .= $minutes . ' mins';
    }
    if (($hours || $minutes) && $seconds) {
        $result .= ', ';
    }
    if ($seconds === 1) {
        $result .= '1 sec';
    } elseif ($seconds > 1) {
        $result .= $seconds . ' secs';
    }

    return $result;
}

function update_balance()
{
    if ($GLOBALS['settings']['balance_checked_at'] + 10 * 60 < $GLOBALS['time']) {
        if ($GLOBALS['settings']['microwallet_api_key']) {
            $balance = @file_get_contents('https://www.microwallet.org/api/v1/balance?api_key=' . rawurlencode($GLOBALS['settings']['microwallet_api_key']));
            $balance = json_decode($balance);
            if ($balance && isset($balance->balance)) {
                $GLOBALS['settings']['balance'] = (int) $balance->balance;
            } else {
                $GLOBALS['settings']['balance'] = 'N/A';
            }
        } else {
            $GLOBALS['settings']['balance'] = 'N/A';
        }

        $GLOBALS['settings']['balance_checked_at'] = $GLOBALS['time'];
        $escapedBalanceCheckedAt = mysqli_real_escape_string($GLOBALS['db'], $GLOBALS['settings']['balance_checked_at']);
        $query = "update microfaucet_settings set value = '$escapedBalanceCheckedAt' where name = 'balance_checked_at'";
        mysqli_query($GLOBALS['db'], $query);

        $escapedBalance = mysqli_real_escape_string($GLOBALS['db'], $GLOBALS['settings']['balance']);
        $query = "update microfaucet_settings set value = '$escapedBalance' where name = 'balance'";
        mysqli_query($GLOBALS['db'], $query);
    }
}

function set_view_variables()
{
    $GLOBALS['view']['main']['login_link_html'] = '<a href="?page=login">Admin login</a>';
    if ((int) VERSION < (int) $GLOBALS['settings']['latest_version']) {
        $GLOBALS['view']['main']['login_link_html'] .= ' <strong class="text-warning" title="New version available. If you are the admin, log in to upgrade.">!!!</strong>';
    } elseif (!$GLOBALS['settings']['behind_proxy'] && isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
        $GLOBALS['view']['main']['login_link_html'] .= ' <strong class="text-warning" title="Issue detected. If you are the admin, log in to solve it.">!!!</strong>';
    }

    $GLOBALS['view']['main']['name'] = htmlspecialchars($GLOBALS['settings']['name']);
    $GLOBALS['view']['main']['slogan'] = htmlspecialchars($GLOBALS['settings']['slogan']);
    $GLOBALS['view']['main']['theme'] = htmlspecialchars($GLOBALS['settings']['theme']);
    $GLOBALS['view']['main']['recaptcha_public_key'] = htmlspecialchars($GLOBALS['settings']['recaptcha_public_key']);
    $GLOBALS['view']['main']['recaptcha_private_key'] = htmlspecialchars($GLOBALS['settings']['recaptcha_private_key']);
    $GLOBALS['view']['main']['solvemedia_challenge_key'] = htmlspecialchars($GLOBALS['settings']['solvemedia_challenge_key']);
    $GLOBALS['view']['main']['solvemedia_verification_key'] = htmlspecialchars($GLOBALS['settings']['solvemedia_verification_key']);
    $GLOBALS['rewards'] = get_rewards();
    $GLOBALS['view']['main']['reward_list'] = $GLOBALS['rewards']['reward_list'];
    $GLOBALS['view']['main']['reward_list_html'] = $GLOBALS['rewards']['reward_list_html'];
    $GLOBALS['view']['main']['timer'] = htmlspecialchars(format_timer($GLOBALS['settings']['timer'] * 60));
    $GLOBALS['view']['main']['balance'] = $GLOBALS['settings']['balance'] === 'N/A' ? 'N/A' : htmlspecialchars(number_format($GLOBALS['settings']['balance']) . ' satoshi');
    $GLOBALS['view']['main']['balance_bitcoin'] = $GLOBALS['settings']['balance'] === 'N/A' ? 'N/A' : htmlspecialchars(number_format($GLOBALS['settings']['balance'] / 100000000, 8) . ' BTC');
    $GLOBALS['view']['main']['username'] = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : (isset($_GET['u']) ? htmlspecialchars(trim($_GET['u'])) : '');
    $GLOBALS['view']['main']['referral_percentage'] = htmlspecialchars($GLOBALS['settings']['referral_percentage']);
    $GLOBALS['view']['main']['referral_url'] = htmlspecialchars(get_main_url() . '?r=') . (preg_match('/^[A-Za-z0-9\.\+\-\_\@]{3,50}$/', $GLOBALS['view']['main']['username']) ? $GLOBALS['view']['main']['username'] : htmlspecialchars('Bitcoin address'));
    if ($GLOBALS['settings']['referral_percentage']) {
        $GLOBALS['view']['main']['referral_program_html'] = '<div class="well well-sm text-center">' . htmlspecialchars('Earn ') . $GLOBALS['view']['main']['referral_percentage'] . htmlspecialchars('% referral bonus! Share your referral URL:') . '<br />' .$GLOBALS['view']['main']['referral_url'] . '</div>';
    } else {
        $GLOBALS['view']['main']['referral_program_html'] = '';
    }
    $GLOBALS['view']['main']['faucet_input_html'] = <<<END
<form action="" method="POST">
    <div class="text-center" id="faucet-label">Enter your Bitcoin address</div>
    <div class="form-group" id="faucet-input">
        <input class="form-control input-lg" type="text" name="username" id="username" value="{$GLOBALS['view']['main']['username']}" placeholder="Bitcoin address" />
    </div>
END;
    if ($GLOBALS['view']['main']['recaptcha_public_key'] && $GLOBALS['view']['main']['recaptcha_private_key']) {
        $GLOBALS['view']['main']['faucet_captcha_html'] = <<<END
    <div class="form-group" id="faucet-captcha">
        <center><script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k={$GLOBALS['view']['main']['recaptcha_public_key']}"></script></center>
    </div>
END;
    } else {
        $GLOBALS['view']['main']['faucet_captcha_html'] = <<<END
    <div class="form-group" id="faucet-captcha">
        <center><script type="text/javascript" src="http://api.solvemedia.com/papi/challenge.script?k={$GLOBALS['view']['main']['solvemedia_challenge_key']}"></script></center>
    </div>
END;
    }
    $GLOBALS['view']['main']['faucet_button_html'] = <<<END
    <div class="form-group" id="faucet-button">
        <button class="form-control input-lg btn-success">Get a reward!</button>
    </div>
</form>
END;
    $GLOBALS['view']['main']['faucet_html'] = $GLOBALS['view']['main']['faucet_input_html'] . $GLOBALS['view']['main']['faucet_captcha_html'] . $GLOBALS['view']['main']['faucet_button_html'];

    $content = file_get_contents('themes/' . $GLOBALS['settings']['theme'] . '/index.php');
    preg_match_all('/(block\_.+?\_html)/', $content, $matches);
    foreach ($matches[1] as $block) {
        if (isset($_GET['show_blocks']) && $_GET['show_blocks']) {
            session_start();
            if ($_SESSION['logged_in']) {
                $GLOBALS['view']['main'][$block] = '<div style="padding: 10px; width: 100%; border: 2px solid #333; background: #EEE;"><strong>' . strtoupper($block) . '</strong> will be inserted here.</div>';
                continue;
            }
        }
        $GLOBALS['view']['main'][$block] = $GLOBALS['settings'][$block];
    }

    $GLOBALS['view']['main']['claimed'] = false;
    $GLOBALS['view']['main']['claimed_amount'] = false;
}

function parse_paid_theme($content = null)
{
    if (is_null($content)) {
        $content = file_get_contents('themes/' . $GLOBALS['settings']['theme'] . '/style.css');
    }

    if (!preg_match('/Type\: (paid.*)/i', $content, $matches)) {
        return false;
    }

    $data = explode(',', $matches[1]);
    if (!isset($data[1])) {
        return false;
    }
    $percentage = (int) trim(trim($data[1]), '%');
    if ($percentage > 20 || $percentage < 1) {
        return false;
    }
    if (!isset($data[2])) {
        return false;
    }
    $username = trim($data[2]);
    if (!preg_match('/^[A-Za-z0-9\.\+\-\_\@]{3,50}$/', $username)) {
        return false;
    }

    return array(
        'username' => $username,
        'percentage' => $percentage,
    );
}

function fix_magic_quotes()
{
    if (get_magic_quotes_gpc()) {
        $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
        while (list($key, $val) = each($process)) {
            foreach ($val as $k => $v) {
                unset($process[$key][$k]);
                if (is_array($v)) {
                    $process[$key][stripslashes($k)] = $v;
                    $process[] = &$process[$key][stripslashes($k)];
                } else {
                    $process[$key][stripslashes($k)] = stripslashes($v);
                }
            }
        }
        unset($process);
    }
}

class Microwallet
{
    protected $apiKey;

    public function __construct($apiKey = null) {
        if (is_null($apiKey)) {
            throw new Exception('API key missing.');
        }
        $this->apiKey = $apiKey;
    }

    public function send($to = null, $amount = null, $note = '')
    {
        if (is_null($to) || is_null($amount)) {
            return array(
                'success' => false,
                'message' => 'Recipient and/or amount missing.',
                'html' => '<div class="alert alert-danger">Recipient and/or amount missing.</div>',
                'response' => null,
            );
        }

        $noteFragment = '';
        if ($note) $noteFragment = '&note=' . rawurlencode($note);

        $postData = 'api_key=' . rawurlencode($this->apiKey) . '&to=' . rawurlencode($to) . '&amount=' . rawurlencode($amount) . $noteFragment;

        $request = '';
        $request .= "POST /api/v1/send HTTP/1.0\r\n";
        $request .= "Host: www.microwallet.org\r\n";
        $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $request .= "Content-Length: " . strlen($postData) . "\r\n";
        $request .= "Connection: close\r\n\r\n";
        $request .= $postData . "\r\n";

        $fp = @fsockopen('ssl://www.microwallet.org', 443);
        if (!$fp) {
            return array(
                'success' => false,
                'message' => 'Failed to send.',
                'html' => '<div class="alert alert-danger">Failed to send.</div>',
                'response' => null,
            );
        }
        @fputs($fp, $request);
        $response = '';
        while (!@feof($fp)) {
            $response .= @fgets($fp, 1024);
        }
        @fclose($fp);

        list($header, $response) = explode("\r\n\r\n", $response);
        $responseJson = json_decode($response);

        if (isset($responseJson->status) && $responseJson->status === 200) {
            if ($note === 'Referral earnings.') {
                $html = '<div class="alert alert-info">' . htmlspecialchars($amount) . ' satoshi (referral earnings) was sent to <a target="_blank" href="https://www.microwallet.org/?u=' . rawurlencode($to) . '">your Microwallet.org account</a>.</div>';
            } else {
                $html = '<div class="alert alert-success">' . htmlspecialchars($amount) . ' satoshi was sent to <a target="_blank" href="https://www.microwallet.org/?u=' . rawurlencode($to) . '">your Microwallet.org account</a>.</div>';
            }
            return array(
                'success' => true,
                'message' => 'Payment sent to your Microwallet.org account.',
                'html' => $html,
                'response' => $response,
            );
        }

        if (isset($responseJson->message)) {
            return array(
                'success' => false,
                'message' => $responseJson->message,
                'html' => '<div class="alert alert-danger">' . htmlspecialchars($responseJson->message) . '</div>',
                'response' => $response,
            );
        }

        return array(
            'success' => false,
            'message' => 'Unknown error.',
            'html' => '<div class="alert alert-danger">Unknown error.</div>',
            'response' => $response,
        );
    }

    public function sendReferralEarnings($to = null, $amount = null)
    {
        return $this->send($to, $amount, 'Referral earnings.');
    }
}
