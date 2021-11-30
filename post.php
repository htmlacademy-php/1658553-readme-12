<?php

require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('src/db.php');
require_once('model/models.php');
/* @var mysqli $mysql */

$isAuth = rand(0, 1);
$userName = 'Владик';
/**
 * Входящие данные
 */
$postId = retriveGetInt('post-id', 0);
$IsPostIdExist = isPostExist($mysql, $postId);


if ($IsPostIdExist) {
    $postMainContent = getPost($mysql, $postId);
    $authorPostsCount = authorPostsCount($mysql, $postMainContent['user_id']);
    $commentList = commentList($mysql, $postId, 0, 2);
    $commentAllList = commentList($mysql, $postId, 0, 200);


    /**
     * Отображение данных
     */


    $header = includeTemplate(
        'block/header.php',
        [
            'isAuth' => $isAuth,
            'userName' => $userName,
        ]
    );
    $postContents = includeTemplate(
        'post.php',
        [
            'postMainContent' => $postMainContent,
            'authorPostsCount' => $authorPostsCount,
            'commentList' => $commentList,
            'commentAllList' => $commentAllList,
        ]
    );
    $layoutContent = includeTemplate(
        'layout.php',
        [
            'header' => $header,
            'content' => $postContents,
            'title' => 'readme: публикация',

        ]
    );


    print ($layoutContent);
} else {
    header("Location: error.php");
}





