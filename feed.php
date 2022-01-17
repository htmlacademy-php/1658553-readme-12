<?php


const SORT_VIEWS = 'views_number';
const SORT_DATE = 'create_date';
const SORT_LIKES = 'count_likes';

const TYPE_TEXT = 'text';
const TYPE_QUOTE = 'quote';
const TYPE_PHOTO = 'photo';
const TYPE_VIDEO = 'video';
const TYPE_LINK = 'link';

const PAGE_FEED = '/feed.php';



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
    $mainUser = $_SESSION['user']['id'];
    $countMassage = getCountedUnreadMessages($mysql,$mainUser);

    $contentType = retriveGetInt('content_type', null);
    $sortId = getSortId();
    $feedPosts = getFeedPosts(
        $mysql,
        $sortId,
        $contentType,
        $_SESSION['user']['id']
    );
    $contentTypes = getContentTypes($mysql, 'type_name');

    foreach ($feedPosts as $arr => $val) {
        $repostCount = repostCount($mysql, $val['post_num']);
        $feedPosts[$arr]['repost_count'] = $repostCount['repost_count'];
    }

    $header = includeTemplate(
        'block/header.php',
        [
            'avatar'   => $_SESSION['user']['avatar'],
            'userName' => $_SESSION['user']['login'],
            'userId'   => $_SESSION['user']['id'],
            'countMassages' => $countMassage,
        ]
    );
    $feed = includeTemplate(
        'block/block-feed.php',
        [
            'feedPosts' => $feedPosts,

        ]
    );

    $pageContent = includeTemplate(
        'feed.php',
        [
            'currentType'  => $contentType,
            'pageContent'  => $feed,
            'contentTypes' => $contentTypes,


        ]
    );

    $layout_content = includeTemplate(
        'layout.php',
        [
            'content' => $pageContent,
            'header'  => $header,
            'title'   => 'readme: моя лента',

        ]
    );
    print $layout_content;
}
