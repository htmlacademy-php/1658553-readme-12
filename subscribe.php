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
        sendMessage($userEmail['email'], 'Readme@mail.ru', 'У вас новый подписчик',
            'Здравствуйте, '.$userEmail['login'].' . На вас подписался новый пользователь '.$_SESSION['user']['login'].'.
         Вот ссылка на его профиль: http://localhost/1658553-readme-12/profile.php?user='.$_SESSION['user']['id'].'');
    } else {
        removeSubscribe($mysql, $authorId, $userId);
    }
    header('location:  '.$_SERVER['HTTP_REFERER'].' ');
}
