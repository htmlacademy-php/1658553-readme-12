<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
require_once('config/config.php');
require_once('config/mailer.php');
require_once('src/helpers.php');
require_once('src/add-query.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('model/models.php');
require_once('src/validate.php');

/* @var bool $isAuth */
/* @var mysqli $mysql */
/* @var mysqli $transport */
if ($isAuth) {
    header('location: index.php');
}

$mainUser = $_SESSION['user']['id'];
$interlocutor = $_POST['interlocutor'];
$message = $_POST['message'];
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';


$errors = [];
$fields = [
    'message' => [
        'validation' => function ($key) {
            return validateFilled($key);
        },
        'add'        => function ($mysql, $message, $mainUser, $interlocutor) {
            return addMessage($mysql, $message, $mainUser, $interlocutor);
        },
    ],
];

if ($isPost) {
    mysqli_begin_transaction($mysql);
    foreach ($_POST as $key => $value) {
        if ($key === 'message') {
            $ruleValid = $fields[$key]['validation'];
            $errors[$key] = $ruleValid($key);
            $ruleAdd = $fields[$key]['add'];
            $ruleAdd($mysql, $message, $mainUser, $interlocutor);
        }
    }
    if (!findErrors($errors)) {
        mysqli_commit($mysql);
        $userEmail = getUserEmail($mysql, $interlocutor);
        $message = new Email();
        $message->to($userEmail['email']);
        $message->from("Readme@mail.ru");
        $message->subject("У вас новый подписчик");
        $message->text('Здравствуйте, '.$userEmail['login'].' . Вам отправил сообщение пользователь '.$_SESSION['user']['login'].'.
         Прочитать его можно здесь: http://localhost/1658553-readme-12/messages.php');
        $mailer = new Mailer($transport);
        $mailer->send($message);
    } else {
        mysqli_rollback($mysql);
        $_SESSION['errors'] = $errors;
    }
    header('location:  '.$_SERVER['HTTP_REFERER'].' ');
}



