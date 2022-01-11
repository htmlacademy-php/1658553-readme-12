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
    'email'    => [
        'validation' => function ($mysql, $key) {
            return singUpEmail($mysql, $key);
        },
    ],
    'password' => [
        'validation' => function ($mysql, $key, $email) {
            return singUpPassword($mysql, $key, $email);
        },
    ],
];


if ($isPost) {
    foreach ($_POST as $key => $value) {
        $ruleValid = $fields[$key]['validation'];
        $errors[$key] = $ruleValid($mysql, $value, $_POST['email']);
    }
    if (!findErrors($errors)) {
        $userInfo = searchDuplicate($mysql, $_POST['email']);
        $_SESSION['user'] = $userInfo;
        header("Location: index.php");
    }
}

$layoutContent = includeTemplate(
    'layout-for-sing-in.php',
    [
        'isPost' => $isPost,
        'errors' => $errors,
        'title'  => 'readme: блог, каким он должен быть',
    ]
);
print($layoutContent);
