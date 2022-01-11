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
    $profileUser = $_GET['user'];
    $isComment = $_GET['comment'];
    $isCommentShowALl = $_GET['view'];
    $profileInfo = getInfoProfileUser($mysql, $profileUser);
    $isUserSubscribe = isUserSubscribe($mysql, $profileInfo['user_id'], $_SESSION['user']['id']);
    $profilePosts = profilePosts($mysql, $profileUser);

    foreach ($profilePosts as $arr => $val) {
        $repostCount = repostCount($mysql, $val['post_num']);
        $profilePosts[$arr]['repost_count'] = $repostCount['repost_count'];
        $hashtags = GetHashtag($mysql, $val['post_num']);
        $profilePosts[$arr]['hashtags'] = $hashtags;
        if ($val['repost']) {
            $repostUserInfo = repostUserInfo($mysql, $val['originalPostId']);
            $profilePosts[$arr]['original'] = $repostUserInfo;
        }
        $commentList = getCommentsForPost($mysql, $val['post_num']);
        if (!$isCommentShowALl) {
            $profilePosts[$arr]['comment_list'] = array_slice($commentList, 0, 2);
        } else {
            $profilePosts[$arr]['comment_list'] = $commentList;
        }
    }

    if ($_SESSION['errors']){
        $errors = [];
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);

    }

    $header = includeTemplate(
        'block/header.php',
        [
            'avatar' => $_SESSION['user']['avatar'],
            'userName' => $_SESSION['user']['login'],
            'userId' => $_SESSION['user']['id'],
        ]
    );

    $showContent = includeTemplate(
        'block/block-profile-posts.php',
        [
            'userId' => $_SESSION['user']['id'],
            'profilePosts' => $profilePosts,
            'isComment' => $isComment,
            'isCommentShowAll' => $isCommentShowALl,
            'errors'=> $errors,

        ]
    );
    $pageContent = includeTemplate(
        'profile.php',
        [
            'profileInfo' => $profileInfo,
            'postContent' => $showContent,
            'isUserSubscribe' => $isUserSubscribe,

        ]
    );

    $layout_content = includeTemplate(
        'layout.php',
        [
            'content' => $pageContent,
            'header' => $header,
            'title' => 'readme: Профиль пользователя',

        ]
    );
    print $layout_content;
}

