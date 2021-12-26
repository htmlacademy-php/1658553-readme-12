<?php

require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/add-query.php');
require_once('model/models.php');


/* @var mysqli $mysql */

$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

$errors = [];

$fields = [
    'email' => [
        'validation' => function ($mysql, $key) {
            return validateEmail($mysql, $key);
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserEmail($mysql, $lastUserId);
        },
    ],
    'login' => [
        'validation' => function ($mysql,$key) {
            return validateFilled($key);
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserLogin($mysql, $lastUserId);
        },
    ],
    'password' => [
        'validation' => function ($mysql, $key) {
            return validatePassword($key);
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserPass($mysql, $lastUserId);
        },
    ],
    'password-repeat' => [
        'validation' => function ($mysql, $key) {
            return validatePasswordRepeat($key);
        },
        'add' => function ($mysql, $lastUserId) {
            return $lastUserId;
        },
    ],
    'avatar' => [
        'validation' => function () {
            return validateAvatarFromUser();
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserAvatar($mysql, $lastUserId);
        },
    ],
];

if ($isPost) {
    mysqli_begin_transaction($mysql);
    $avatar['avatar'] = '';
    $_POST += $avatar;
    foreach ($_POST as $key => $value) {
        $ruleValid = $fields[$key]['validation'];
        $errors[$key] = $ruleValid($mysql,$key);
        $ruleAdd = $fields[$key]['add'];
        $lastUserId = $ruleAdd($mysql, $lastUserId);
    }
    if (!findErrors($errors)) {
        mysqli_commit($mysql);
        rebaseImg();
        header("Location: index.php");
    } else {
        mysqli_rollback($mysql);
        deleteImg();
    }
}



$header = includeTemplate(
    'block/header-no-log.php',
    [

    ]
);

$blockRegistration = includeTemplate(
    'block/block-registration.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
    ]
);
$registration = includeTemplate(
    'reg.php',
    [
        'blockRegistration' => $blockRegistration,
    ]
);

$layout_content = includeTemplate(
    'layout.php',
    [
        'header' => $header,
        'content' => $registration,
        'title' => 'Регистрация',

    ]
);
print $layout_content;
