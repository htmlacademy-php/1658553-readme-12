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
    $userId = $_SESSION['user']['id'];
    $postId = $_GET['id'];

    $isPostIdExist = isPostExist($mysql, $postId);

    if ($isPostIdExist) {
        if (isUserLike($mysql, $postId, $userId)) {
            addLike($mysql, $postId, $userId);
        } else {
            removeLike($mysql, $postId, $userId);
        }
        header('location:  '.$_SERVER['HTTP_REFERER'].' ');
    }
}
