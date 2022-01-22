<?php

require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/add-query.php');
require_once('model/models.php');


/* @var bool $isAuth */
/* @var mysqli $mysql */


if ($isAuth) {
    header('location: index.php');
} else {
    $postId = $_GET['postId'];
    $isPost = isPostExist($mysql, $postId);
    if ($isPost) {
        $postInfo = getInfoForRepost($mysql, $postId);
        $repostInfo = $postInfo;
        $userId = $_SESSION['user']['id'];
        $date = date('Y-m-d H:i:s');
        unset($repostInfo['id']);
        $repostInfo['create_date'] = $date;
        $repostInfo['views_number'] = 0;
        $repostInfo['user_id'] = $userId;
        $repostInfo['repost'] = true;
        $repostInfo['originalPostId'] = $postInfo['id'];
        mysqli_begin_transaction($mysql);
        $isSuccess = addRepost($mysql, $repostInfo);
        if ($isSuccess) {
            mysqli_commit($mysql);
            header('location: profile.php?user='.$userId.'');
        } else {
            header('location: index.php');
        }
    } else {
        header('location: index.php');
    }
}
