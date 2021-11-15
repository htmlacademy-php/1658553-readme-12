<?php

const TYPE_TEXT = 'text';
const TYPE_QUOTE = 'quote';
const TYPE_PHOTO = 'photo';
const TYPE_VIDEO = 'video';
const TYPE_LINK = 'link';

require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/db.php');
require_once('src/addQuery.php');
require_once('model/models.php');



/* @var mysqli $mysql */

$isAuth = rand(0, 1);
$userName = 'Владик';

$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$content = [];
$content = stretchOutContent($content);
$errors = [];




if ($isPost) {
    if (array_key_first($content) === 'photo') {
        $errors = validatePhoto($content);
        if (empty($errors)) {
            $lastPostId = addPostPhoto($content, $mysql);
            header("Location: post.php?post-id=$lastPostId");
        }
    } elseif (array_key_first($content) === 'video') {
        $errors = validateVideo($content);
        if (empty($errors)) {
            $lastPostId = addPostVideo($content, $mysql);
            header("Location: post.php?post-id=$lastPostId");

        }
    } elseif (array_key_first($content) === 'text') {
        $errors = validateText($content);
        if (empty($errors)) {
            $lastPostId = addPosttext($content, $mysql);
            header("Location: post.php?post-id=$lastPostId");

        }
    } elseif (array_key_first($content) === 'quote') {
        $errors = validateQuote($content);
        if (empty($errors)) {
            $lastPostId = addPostQuote($content, $mysql);
            header("Location: post.php?post-id=$lastPostId");

        }
    } elseif (array_key_first($content) === 'link') {
        $errors = validateLink($content);
        if (empty($errors)) {
            $lastPostId = addPostLink($content, $mysql);
            header("Location: post.php?post-id=$lastPostId");

        }
    }
}



$blockLink = includeTemplate(
    'block/block-link.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
    ]
);
$blockPhoto = includeTemplate(
    'block/block-photo.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
    ]
);
$blockQuote = includeTemplate(
    'block/block-quote.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
    ]
);
$blockText = includeTemplate(
    'block/block-text.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
    ]
);
$blockVideo = includeTemplate(
    'block/block-video.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
    ]
);

$postAdd = includeTemplate(
    'post-add.php',
    [
        'block_link' => $blockLink,
        'block_photo' => $blockPhoto,
        'block_quote' => $blockQuote,
        'block_text' => $blockText,
        'block_video' => $blockVideo,
        'content_types' => getContentTypes($mysql, 'type_name'),
        'content_type' => retriveGetInt('content-type', null),
    ]
);
$layout_content = includeTemplate(
    'layout.php',
    [
        'isAuth' => $isAuth,
        'userName' => $userName,
        'content' => $postAdd,
        'title' => 'Добавить публикацию',

    ]
);
print $layout_content;

/**
 * Функция для пополнения массива содержимым из массива $_POST
 * @param array $array Массив в который мы будем вкладывать содержимое из $_POST
 * @return array массив с содержимым $_POST
 */


function stretchOutContent(array &$array): array
{
    foreach ($_POST as $key => $val) {
        if ($key === 'photo-heading') {
            $array['photo'] = $_POST;
        } elseif ($key === 'video-heading') {
            $array['video'] = $_POST;
        } elseif ($key === 'text-heading') {
            $array['text'] = $_POST;
        } elseif ($key === 'quote-heading') {
            $array['quote'] = $_POST;
        } elseif ($key === 'link-heading') {
            $array['link'] = $_POST;
        }
    }

    return $array;
}
