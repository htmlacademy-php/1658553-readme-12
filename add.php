<?php


require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/add-query.php');
require_once('model/models.php');


/* @var mysqli $mysql */
/* @var bool $isAuth */
/* @var mysqli $transport */


if ($isAuth) {
    header('location: index.php');
} else {
    $mainUser = $_SESSION['user']['id'];
    $countMassage = getCountedUnreadMessages($mysql, $mainUser);
    $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
    $contentTypes = getContentTypes($mysql, 'type_name');
    $errors = [];
    $fields = [
        'heading'     => [
            'validation' => function ($key) {
                return validateFilled($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $authorId
            ) {
                return addHeading($mysql, $key, $authorId);
            },
        ],
        'tags'        => [
            'validation' => function ($key) {
                return validateSharp($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
            ) {
                return addSharp($mysql, $key, $lastPostId);
            },
        ],
        'photoUrl'    => [
            'validation' => function ($key) {
                return validateImg($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
                $authorId,
                $contentTypes
            ) {
                return addPhotoUrl(
                    $mysql,
                    $lastPostId,
                    $contentTypes['photo']['id']
                );
            },
        ],
        'videoUrl'    => [
            'validation' => function ($key) {
                return validateVideoUrl($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
                $authorId,
                $contentTypes
            ) {
                return addVideoUrl(
                    $mysql,
                    $key,
                    $lastPostId,
                    $contentTypes['video']['id']
                );
            },
        ],
        'textContent' => [
            'validation' => function ($key) {
                return validateFilled($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
                $authorId,
                $contentTypes
            ) {
                return addText(
                    $mysql,
                    $key,
                    $lastPostId,
                    $contentTypes['text']['id']
                );
            },
        ],
        'citeText'    => [
            'validation' => function ($key) {
                return validateCite($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
                $authorId,
                $contentTypes
            ) {
                return addCite(
                    $mysql,
                    $key,
                    $lastPostId,
                    $contentTypes['quote']['id']
                );
            },
        ],
        'quoteAuthor' => [
            'validation' => function ($key) {
                return validateFilled($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
            ) {
                return addQuoteAuthor($mysql, $key, $lastPostId);
            },
        ],
        'linkRef'     => [
            'validation' => function ($key) {
                return validateLink($key);
            },
            'add'        => function (
                $mysql,
                $key,
                $lastPostId,
                $authorId,
                $contentTypes
            ) {
                return addLink(
                    $mysql,
                    $key,
                    $lastPostId,
                    $contentTypes['link']['id']
                );
            },
        ],
    ];

    if ($isPost) {
        mysqli_begin_transaction($mysql);
        $validHeader = $fields['heading']['validation'];
        $errors['heading'] = $validHeader('heading');
        $addHeader = $fields['heading']['add'];
        $lastPostId = $addHeader(
            $mysql,
            'heading',
            $_SESSION['user']['id']
        );
        $postHeader = $_POST['heading'];
        array_shift($_POST);
        foreach ($_POST as $key => $value) {
            $ruleValid = $fields[$key]['validation'];
            $errors[$key] = $ruleValid($key);
            $ruleAdd = $fields[$key]['add'];
            $lastPostId = $ruleAdd(
                $mysql,
                $key,
                $lastPostId,
                $_SESSION['user']['id'],
                $contentTypes
            );
        }
         $_POST['heading'] = $postHeader;
        if (!findErrors($errors)) {
            mysqli_commit($mysql);
            rebaseImg();
            $userSubscribeList = getProfileSubscribe($mysql,
                $_SESSION['user']['id']);
            foreach ($userSubscribeList as $user => $info) {
                sendMessage($info['email'], 'Readme@mail.ru', 'Новая публикация от пользователя '
                    .$_SESSION['user']['login'].'', 'Здравствуйте, '.$info['login']
                    .' . Пользователь '.$_SESSION['user']['login'].' только что опубликовал новую запись
                „'.$_POST['heading']
                    .'“. Посмотрите её на странице пользователя: http://localhost/1658553-readme-12/profile.php?user='
                    .$_SESSION['user']['id'].' ');
            }
            header("Location: post.php?post-id=$lastPostId");
        } else {
            mysqli_rollback($mysql);
            deleteImg();
        }
    }


    $contentType = retriveGetInt('content-type', null);
    $blockName = getBlockName($contentType, $contentTypes);
    $className = getClassNameAddForm($contentType, $contentTypes);


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
            'avatar'        => $_SESSION['user']['avatar'],
            'userName'      => $_SESSION['user']['login'],
            'userId'        => $_SESSION['user']['id'],
            'countMassages' => $countMassage,
        ]
    );
    $postAdd = includeTemplate(
        'post-add.php',
        [
            'blockContent' => $blockContent,
            'className'    => $className,
            'contentTypes' => getContentTypes($mysql, 'type_name'),
            'contentType'  => $contentType,
        ]
    );
    $layout_content = includeTemplate(
        'layout.php',
        [
            'header'  => $header,
            'content' => $postAdd,
            'title'   => 'Добавить публикацию',

        ]
    );

    print $layout_content;
}


