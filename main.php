<?php

session_start();

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
            return singUpEmail($key, $mysql);
        },
    ],
    'password' => [
        'validation' => function ($key, $mysql) {
            return singUpPassword($key, $mysql);
        },
    ],
];


if ($isPost) {
    foreach ($_POST as $key => $value) {
        $ruleValid = $fields[$key]['validation'];
        $errors[$key] = $ruleValid($value, $mysql);
    }
    if (!findErrors($errors)) {
        $userInfo = searchDuplicate($mysql, $_POST['email']);
        $_SESSION['user'] = $userInfo[0];
        header("Location: index.php");
    }
}


$layoutContent = includeTemplate(
    'layout-for-sing-in.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
        'title' => 'readme: блог, каким он должен быть',
    ]
);
print($layoutContent);
