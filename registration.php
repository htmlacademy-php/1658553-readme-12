<?php

require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/db.php');
require_once('src/add-query.php');
require_once('model/models.php');
/* @var mysqli $mysql */

$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

$errors = [];

$fields = [
    'email' => [
        'validation' => function ($key, $mysql) {
            return validateEmail($key, $mysql);
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserEmail($mysql, $lastUserId);
        },
    ],
    'login' => [
        'validation' => function ($key) {
            return validateFilled($key);
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserLogin($mysql, $lastUserId);
        },
    ],
    'password' => [
        'validation' => function ($key) {
            return validatePassword($key);
        },
        'add' => function ($mysql, $lastUserId) {
            return addUserPass($mysql, $lastUserId);
        },
    ],
    'password-repeat' => [
        'validation' => function ($key) {
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
    if (existAddFiles('userpic-file')) {
        $avatar['avatar'] = '';
        $_POST += $avatar;
    }
    foreach ($_POST as $key => $value) {
        $ruleValid = $fields[$key]['validation'];
        $errors[$key] = $ruleValid($key, $mysql);
        $ruleAdd = $fields[$key]['add'];
        $lastUserId = $ruleAdd($mysql, $lastUserId);
    }
    if (!findErrors($errors)) {
        mysqli_commit($mysql);
        header("Location: index.php");
    } else {
        mysqli_rollback($mysql);
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
