<?php

session_start();

if (empty($_SESSION)) {
    header('location: index.php');
} else {
    require_once('src/helpers.php');
    require_once('src/function.php');
    require_once('src/request.php');
    require_once('src/db.php');
    require_once('model/models.php');
    /* @var mysqli $mysql */


    /**
     * Входящие данные
     */
    $postId = retriveGetInt('post-id', 0);
    $isPostIdExist = isPostExist($mysql, $postId);


    if ($isPostIdExist) {
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
                'avatar' => $_SESSION['user']['avatar'],
                'userName' => $_SESSION['user']['login'],
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
}


