<?php


require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('model/models.php');
require_once('src/add-query.php');


/* @var mysqli $mysql */
/* @var bool $isAuth */

if ($isAuth) {
    header('location: index.php');
} else {
    /**
     * Входящие данные
     */
    $postId = retriveGetInt('post-id', 0);

    $isPostIdExist = isPostExist($mysql, $postId);



    if ($isPostIdExist) {
        $postMainContent = getPost($mysql, $postId);
        $authorPostsCount = authorPostsCount($mysql, $postMainContent['user_id']);
        $commentList = getCommentsForPost($mysql, $postId, 0, 2);
        $commentAllList = getCommentsForPost($mysql, $postId, 0, 200);
        addView($mysql,$postId,$postMainContent['views']);




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


