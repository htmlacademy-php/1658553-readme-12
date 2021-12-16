<?php

const TYPE_TEXT = 'text';
const TYPE_QUOTE = 'quote';
const TYPE_PHOTO = 'photo';
const TYPE_VIDEO = 'video';
const TYPE_LINK = 'link';

require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/add-query.php');
require_once('model/models.php');


/* @var mysqli $mysql */
/* @var bool $isAuth */


if ($isAuth) {
    header('location: index.php');
} else {
    $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
    $errors = [];
    $fields = [
        'heading' => [
            'validation' => function ($key) {
                return validateFilled($key);
            },
            'add' => function ($mysql, $lastPostId, $authorId) {
                return addHeading($mysql, $lastPostId, $authorId);
            },
        ],
        'tags' => [
            'validation' => function ($key) {
                return validateSharp($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addSharp($mysql, $lastPostId);
            },
        ],
        'photo-url' => [
            'validation' => function ($key) {
                return validateImg($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addPhotoUrl($mysql, $lastPostId);
            },
        ],
        'video-url' => [
            'validation' => function ($key) {
                return validateVideoUrl($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addVideoUrl($mysql, $lastPostId);
            },
        ],
        'text-content' => [
            'validation' => function ($key) {
                return validateFilled($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addText($mysql, $lastPostId);
            },
        ],
        'cite-text' => [
            'validation' => function ($key) {
                return validateCite($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addCite($mysql, $lastPostId);
            },
        ],
        'quote-author' => [
            'validation' => function ($key) {
                return validateFilled($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addQuoteAuthor($mysql, $lastPostId);
            },
        ],
        'link-ref' => [
            'validation' => function ($key) {
                return validateLink($key);
            },
            'add' => function ($mysql, $lastPostId) {
                return addLink($mysql, $lastPostId);
            },
        ],
    ];

    if ($isPost) {
        mysqli_begin_transaction($mysql);
        foreach ($_POST as $key => $value) {
            $ruleValid = $fields[$key]['validation'];
            $errors[$key] = $ruleValid($key);
            $ruleAdd = $fields[$key]['add'];
            $lastPostId = $ruleAdd($mysql, $lastPostId, $_SESSION['user']['id']);
        }
        if (!findErrors($errors)) {
            mysqli_commit($mysql);
            rebaseImg();
            header("Location: post.php?post-id=$lastPostId");
        } else {
            mysqli_rollback($mysql);
            deleteImg();
        }
    }


    $contentType = retriveGetInt('content-type', null);
    $blockName = getBlockName($contentType);
    $className = getClassNameAddForm($contentType);

    $blockContent = includeTemplate(
        "block/$blockName",
        [
            'isPost' => $isPost,
            'errors' => $errors,
        ]
    );

    $header = includeTemplate(
        'block/header.php',
        [
            'avatar' => $_SESSION['user']['avatar'],
            'userName' => $_SESSION['user']['login'],
        ]
    );
    $postAdd = includeTemplate(
        'post-add.php',
        [
            'blockContent' => $blockContent,
            'className' => $className,
            'contentTypes' => getContentTypes($mysql, 'type_name'),
            'contentType' => $contentType,
        ]
    );
    $layout_content = includeTemplate(
        'layout.php',
        [
            'header' => $header,
            'content' => $postAdd,
            'title' => 'Добавить публикацию',

        ]
    );
    print $layout_content;
}


