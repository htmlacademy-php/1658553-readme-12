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
    $errors = [];
    $blockName = $_GET['show'] ?? null;
    $profileUser = $_GET['user'] ?? null;
    $isComment = $_GET['comment'] ?? null;
    $isCommentShowALl = $_GET['view'] ?? null;


    if (!$profileUser) {
        header("Location: error.php");
    } else {
        $profileInfo = getInfoProfileUser($mysql, $profileUser);
        $isUserSubscribe = isUserSubscribe(
            $mysql,
            $profileInfo['user_id'],
            $_SESSION['user']['id']
        );

        if ($blockName === SHOW_POSTS or is_null($blockName)) {
            $profilePosts = getProfilePosts($mysql, $profileUser);
            foreach ($profilePosts as $arr => $val) {
                $repostCount = repostCount($mysql, $val['post_num']);
                $profilePosts[$arr]['repost_count']
                    = $repostCount['repost_count'];
                $hashtags = GetHashtag($mysql, $val['post_num']);
                $profilePosts[$arr]['hashtags'] = $hashtags;
                if ($val['repost']) {
                    $repostUserInfo = repostUserInfo(
                        $mysql,
                        $val['originalPostId']
                    );
                    $profilePosts[$arr]['original'] = $repostUserInfo;
                }
                $commentList = getCommentsForPost($mysql, $val['post_num']);
                if (!$isCommentShowALl) {
                    $profilePosts[$arr]['comment_list'] = array_slice(
                        $commentList,
                        0,
                        2
                    );
                } else {
                    $profilePosts[$arr]['comment_list'] = $commentList;
                }
            }

            if (!empty($_SESSION['errors'])) {
                $errors = [];
                $errors = $_SESSION['errors'];
                unset($_SESSION['errors']);
            }
            $showContent = includeTemplate(
                'block/block-profile-posts.php',
                [
                    'userId'           => $profileUser,
                    'profilePosts'     => $profilePosts,
                    'isComment'        => $isComment,
                    'isCommentShowAll' => $isCommentShowALl,
                    'errors'           => $errors,

                ]
            );
        } elseif ($blockName === SHOW_LIKES) {
            $profileLikes = getProfileLikes($mysql, $profileUser);
            $contentTypes = getContentTypes($mysql, 'type_name');

            $showContent = includeTemplate(
                'block/block-profile-likes.php',
                [
                    'profileLikes' => $profileLikes,
                    'contentTypes' => $contentTypes,

                ]
            );
        } elseif ($blockName === SHOW_SUBSCRIBE) {
            $profileSubscribeId = getProfileSubscribe($mysql, $profileUser);
            $profileSubscribe = [];
            if (!empty($profileSubscribeId)) {
                foreach ($profileSubscribeId as $user => $id) {
                    $profileSubscribeList = getInfoProfileUser(
                        $mysql,
                        $id['user_subscribe_id']
                    );
                    $profileSubscribe[$user]['info'] = $profileSubscribeList;
                    $isUserSubscribed = isUserSubscribe(
                        $mysql,
                        $id['user_subscribe_id'],
                        $_SESSION['user']['id']
                    );
                    $profileSubscribe[$user]['isSubscribe'] = $isUserSubscribed;
                }
            }
            $showContent = includeTemplate(
                'block/block-profile-subscribe.php',
                [
                    'profileSubscribe' => $profileSubscribe,

                ]
            );
        }

        $header = includeTemplate(
            'block/header.php',
            [
                'avatar'   => $_SESSION['user']['avatar'],
                'userName' => $_SESSION['user']['login'],
                'userId'   => $_SESSION['user']['id'],
            ]
        );


        $pageContent = includeTemplate(
            'profile.php',
            [
                'profileInfo'     => $profileInfo,
                'postContent'     => $showContent,
                'isUserSubscribe' => $isUserSubscribe,
                'profileBlock'    => $blockName,

            ]
        );

        $layout_content = includeTemplate(
            'layout.php',
            [
                'content' => $pageContent,
                'header'  => $header,
                'title'   => 'readme: Профиль пользователя',

            ]
        );
        print $layout_content;
    }
}


