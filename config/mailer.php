<?php

use Symfony\Component\Mailer\Transport;

require_once('vendor/autoload.php');

// Конфигурация траспорта

$dsn
    = 'smtp://481428c63a84ee:5cefaca8023c32@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';

$transport = Transport::fromDsn($dsn);
