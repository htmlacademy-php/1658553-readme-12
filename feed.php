<?php

session_start();
const SORT_VIEWS = 'views_number';
const SORT_DATE = 'create_date';
const SORT_LIKES = 'count_likes';

const TYPE_TEXT = 'text';
const TYPE_QUOTE = 'quote';
const TYPE_PHOTO = 'photo';
const TYPE_VIDEO = 'video';
const TYPE_LINK = 'link';


const PAGE_FEED = '/1658553-readme-12/feed.php';



if (empty($_SESSION)) {
    header('location: index.php');
} else {
    require_once('src/helpers.php');
    require_once('src/function.php');
    require_once('src/validate.php');
    require_once('src/request.php');
    require_once('src/db.php');
    require_once('src/add-query.php');
    require_once('model/models.php');



    $header = includeTemplate(
        'block/header.php',
        [
            'avatar' => $_SESSION['user']['avatar'],
            'userName' => $_SESSION['user']['login'],
        ]
    );


    $feed = includeTemplate(
        'feed.php',
        [

        ]
    );

    $layout_content = includeTemplate(
        'layout.php',
        [
            'content' => $feed,
            'header' => $header,
            'title' => 'readme: моя лента',

        ]
    );
    print $layout_content;
}
