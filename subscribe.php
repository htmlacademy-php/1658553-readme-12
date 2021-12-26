<?php

require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/add-query.php');
require_once('model/models.php');

/* @var mysqli $mysql */
/* @var bool $isAuth */

if ($isAuth) {
    header('location: index.php');
} else {
    $authorId = $_GET['authorId'];
    $userId = $_SESSION['user']['id'];
    $isUserSubscribe = isUserSubscribe($mysql, $authorId, $userId );
    if ($isUserSubscribe){
        addSubscribe($mysql, $authorId, $userId);
    } else{
        removeSubscribe($mysql, $authorId, $userId);
    }
    header('location:  '.$_SERVER['HTTP_REFERER'].' ');
var_dump($isUserSubscribe);

}
