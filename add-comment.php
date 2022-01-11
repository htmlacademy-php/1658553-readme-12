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
    $postId = $_POST['postId'];
    $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
    $errors = [];
    $fields = [
        'commentText' => [
            'validation' => function ($key) {
                return validateComment($key);
            },
            'add'        => function ($mysql, $key, $postId, $userId) {
                return addComment($mysql, $key, $postId, $userId);
            },
        ],
    ];

    if ($isPost) {
        mysqli_begin_transaction($mysql);
        foreach ($_POST as $key => $value) {
            if ($key === 'commentText') {
                $ruleValid = $fields[$key]['validation'];
                $errors[$key] = $ruleValid($key);
                $ruleAdd = $fields[$key]['add'];
                $ruleAdd($mysql, $key, $postId, $_SESSION['user']['id']);
            }
        }
        if (!findErrors($errors)) {
            mysqli_commit($mysql);
        } else {
            mysqli_rollback($mysql);
            $_SESSION['errors'] = $errors;
        }
        header('location:  '.$_SERVER['HTTP_REFERER'].' ');
    }
}

