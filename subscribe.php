<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once('config/config.php');
require_once('config/mailer.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/validate.php');
require_once('src/request.php');
require_once('src/add-query.php');
require_once('model/models.php');


/* @var bool $isAuth */
/* @var mysqli $mysql */
/* @var mysqli $transport */


if ($isAuth) {
    header('location: index.php');
} else {
    $authorId = $_GET['authorId'];
    $userId = $_SESSION['user']['id'];
    $isUserSubscribe = isUserSubscribe($mysql, $authorId, $userId);
    if ($isUserSubscribe) {
        addSubscribe($mysql, $authorId, $userId);
        $userEmail = getUserEmail($mysql, $authorId);
        $message = new Email();
        $message->to($userEmail['email']);
        $message->from("Readme@mail.ru");
        $message->subject("У вас новый подписчик");
        $message->text('Здравствуйте, '.$userEmail['login'].' . На вас подписался новый пользователь '.$_SESSION['user']['login'].'.
         Вот ссылка на его профиль: http://localhost/1658553-readme-12/profile.php?user='.$_SESSION['user']['id'].'');
        $mailer = new Mailer($transport);
        $mailer->send($message);
    } else {
        removeSubscribe($mysql, $authorId, $userId);
    }
    header('location:  '.$_SERVER['HTTP_REFERER'].' ');
}
