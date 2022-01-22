<?php


require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('model/models.php');
require_once('src/add-query.php');

/* @var bool $isAuth */
/* @var mysqli $mysql */


if ($isAuth) {
    header('location: index.php');
} else {
    /**
     * Входящие данные
     */
    $postId = retriveGetInt('post-id', 0);
    $isPostIdExist = isPostExist($mysql, $postId);
    $errors = [];


    if (!$isPostIdExist) {
        header("Location: error.php");
    } else {
        $isCommentShowALl = $_GET['comment'] ?? null;
        $postMainContent = getPost($mysql, $postId);
        $authorPostsCount = authorPostsCount(
            $mysql,
            $postMainContent['user_id']
        );
        $repostCount = repostCount($mysql, $postId);
        $hashtags = GetHashtag($mysql, $postId);
        addView($mysql, $postId, $postMainContent['views']);
        $isUserSubscribe = isUserSubscribe(
            $mysql,
            $postMainContent['user_id'],
            $_SESSION['user']['id']
        );
        if (!$isCommentShowALl) {
            $commentList = array_slice(
                getCommentsForPost($mysql, $postId),
                0,
                2
            );
        } else {
            $commentList = getCommentsForPost($mysql, $postId);
        }


        if (!empty($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        }


        /**
         * Отображение данных
         */


        $header = includeTemplate(
            'block/header.php',
            [
                'avatar'   => $_SESSION['user']['avatar'],
                'userName' => $_SESSION['user']['login'],
                'userId'   => $_SESSION['user']['id'],
            ]
        );
        $postContents = includeTemplate(
            'post.php',
            [
                'postMainContent'  => $postMainContent,
                'authorPostsCount' => $authorPostsCount,
                'commentList'      => $commentList,
                'isCommentShowAll' => $isCommentShowALl,
                'isUserSubscribe'  => $isUserSubscribe,
                'hashtags'         => $hashtags,
                'userAvatar'       => $_SESSION['user']['avatar'],
                'errors'           => $errors,
                'repostCount'      => $repostCount['repost_count'],
            ]
        );
        $layoutContent = includeTemplate(
            'layout.php',
            [
                'header'  => $header,
                'content' => $postContents,
                'title'   => 'readme: публикация',

            ]
        );


        print ($layoutContent);
    }
}


