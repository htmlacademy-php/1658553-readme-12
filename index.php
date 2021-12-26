<?php

require_once('config/config.php');

/* @var bool $isAuth */

if ($isAuth) {
    header('location: main.php');
} else {
    header('location: feed.php');
}
