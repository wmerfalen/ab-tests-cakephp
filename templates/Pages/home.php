<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

$checkConnection = function (string $name) {
    $error = null;
    $connected = false;
    try {
        $connection = ConnectionManager::get($name);
        $connected = $connection->connect();
    } catch (Exception $connectionError) {
        $error = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
            $attributes = $connectionError->getAttributes();
            if (isset($attributes['message'])) {
                $error .= '<br />' . $attributes['message'];
            }
        }
    }

    return compact('connected', 'error');
};

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        CakePHP: the rapid development PHP framework:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'home']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <div class="container text-center">
            <a href="https://cakephp.org/" target="_blank" rel="noopener">
                <img alt="CakePHP" src="https://cakephp.org/v2/img/logos/CakePHP_Logo.svg" width="350" />
            </a>
            <h1>
                Welcome to CakePHP <?= h(Configure::version()) ?> Strawberry (🍓)
            </h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="column">
                        <div class="message default text-center">
                            <small>Please be aware that this page will not be shown if you turn off debug mode unless you replace templates/Pages/home.php with your own version.</small>
                        </div>
                        <div id="url-rewriting-warning" style="padding: 1rem; background: #fcebea; color: #cc1f1a; border-color: #ef5753;">
                            <ul>
                                <li class="bullet problem">
                                    URL rewriting is not properly configured on your server.<br />
                                    1) <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/en/installation.html#url-rewriting">Help me configure it</a><br />
                                    2) <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/en/development/configuration.html#general-configuration">I don't / can't use URL rewriting</a>
                                </li>
                            </ul>
                        </div>
                        <?php Debugger::checkSecurityKeys(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <h4>Offer ends soon!</h4>
                        <ul>
                        <?php if (version_compare(PHP_VERSION, '7.2.0', '>=')) : ?>
                            <li class="bullet success">Your version of PHP is 7.2.0 or higher (detected <?= PHP_VERSION ?>).</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP is too low. You need PHP 7.2.0 or higher to use CakePHP (detected <?= PHP_VERSION ?>).</li>
                        <?php endif; ?>

                        <?php if (extension_loaded('mbstring')) : ?>
                            <li class="bullet success">Your version of PHP has the mbstring extension loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP does NOT have the mbstring extension loaded.</li>
                        <?php endif; ?>

                        <?php if (extension_loaded('openssl')) : ?>
                            <li class="bullet success">Your version of PHP has the openssl extension loaded.</li>
                        <?php elseif (extension_loaded('mcrypt')) : ?>
                            <li class="bullet success">Your version of PHP has the mcrypt extension loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP does NOT have the openssl or mcrypt extension loaded.</li>
                        <?php endif; ?>

                        <?php if (extension_loaded('intl')) : ?>
                            <li class="bullet success">Your version of PHP has the intl extension loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP does NOT have the intl extension loaded.</li>
                        <?php endif; ?>
                        </ul>
                        <button style="background-color: <?php echo h($color);?>;">Sign me up!</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
