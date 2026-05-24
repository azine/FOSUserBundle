<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!($loader = @include __DIR__.'/../vendor/autoload.php')) {
    echo <<<'EOT'
You need to install the project dependencies using Composer:
$ wget http://getcomposer.org/composer.phar
OR
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install --dev
$ phpunit
EOT;
    exit(1);
}

if (!class_exists('Twig_Environment') && class_exists('Twig\\Environment')) {
    class_alias('Twig\\Environment', 'Twig_Environment');
}
if (!class_exists('Twig_Loader_Array') && class_exists('Twig\\Loader\\ArrayLoader')) {
    class_alias('Twig\\Loader\\ArrayLoader', 'Twig_Loader_Array');
}
