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
$is_auth = rand(0, 1);
$user_name = 'Владик';
/**
 * Контроллер
 */
$content_type = request_retriveGetInt('content_type', null);
$sort_id = getSortId();
$postsContent = findPosts($mysql, $sort_id, $content_type);
$content_types = getContentTypes($mysql, 'type_name');

/**
 * Отображение данных
 */

$post_content = include_template(
    'block/block_post.php',
    [
        'postListRows' => $postsContent
    ]
);

$page_content = include_template(
    'main.php',
    [
        'sort' => $sort_id,
        'current_type' => $content_type,
        'post_content' => $post_content,
        'content_types' => $content_types
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => 'readme: популярное',
    ]
);

print($layout_content);





