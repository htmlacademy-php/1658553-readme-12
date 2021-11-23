<?php

const SORT_VIEWS = 'views_number';
const SORT_DATE = 'create_date';
const SORT_LIKES = 'count_likes';

const TYPE_TEXT = 'text';
const TYPE_QUOTE = 'quote';
const TYPE_PHOTO = 'photo';
const TYPE_VIDEO = 'video';
const TYPE_LINK = 'link';

require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('src/db.php');
require_once('model/models.php');


/* @var mysqli $mysql */
/**
 * Входящие данные
 */
$isAuth = rand(0, 1);
$userName = 'Владик';
/**
 * Контроллер
 */
$contentType = retriveGetInt('content_type', null);

$sortId = getSortId();
$postsContent = GetPosts($mysql, $sortId, $contentType);
$contentTypes = getContentTypes($mysql, 'type_name');


/**
 * Отображение данных
 */

$postContent = includeTemplate(
    'block/block-posts.php',
    [
        'postListRows' => $postsContent
    ]
);

$pageContent = includeTemplate(
    'main.php',
    [
        'sort' => $sortId,
        'currentType' => $contentType,
        'postContent' => $postContent,
        'contentTypes' => $contentTypes
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'content' => $pageContent,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'title' => 'readme: популярное',
    ]
);

print($layoutContent);





