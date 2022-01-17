<?php



require_once('config/config.php');
require_once('config/mailer.php');
require_once('src/helpers.php');
require_once('src/add-query.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('model/models.php');

const PAGE_MESSAGES = '/messages.php';

/* @var bool $isAuth */
/* @var object $transport */
/* @var mysqli $mysql */

if ($isAuth) {
    header('location: index.php');
}
$messagesList =[];
$errors = [];
$mainUser = $_SESSION['user']['id'];
$userTabsActive = $_GET['dialog'];
$countMassage = getCountedUnreadMessages($mysql,$mainUser);
$mainUserInfo = getInfoProfileUser($mysql, $mainUser);
$conversation = getConversations($mysql, $mainUser);
$isUserExist = isUserExist($mysql, (int)$userTabsActive);
$isUserConversation = false;

if (!(int)$isUserExist && !is_null($userTabsActive)) {
    header('location: index.php');
}

foreach ($conversation as $user => $id) {
    if ((int)$userTabsActive === $id['id']) {
        $isUserConversation = true;
        break;
    }
}
if (!$isUserConversation && $isUserExist) {
    $addConversation = addConversation($mysql, $mainUser,
        $userTabsActive);
}

foreach ($conversation as $user => $info) {
    $allMessage = getMessages($mysql, $mainUser, $info['id']);
    $preview = $allMessage;
    $conversation[$user]['preview'] = array_pop($preview);
};

foreach ($conversation as $user => $info) {
    if ((int)$userTabsActive === $info['id']) {
        $messagesList = getMessages($mysql, $mainUser, $info['id']);
        $unreadMessages = getUnreadMessagesFromUser($mysql,$mainUser,$info['id']);
        if ($unreadMessages['nonViewed']){
            getViewed($mysql,$mainUser, $info['id']);
            header("Refresh: 0");
        }
    }
}


if ($_SESSION['errors']) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

$header = includeTemplate(
    'block/header.php',
    [
        'avatar'   => $_SESSION['user']['avatar'],
        'userName' => $_SESSION['user']['login'],
        'userId'   => $_SESSION['user']['id'],
        'countMassages' => $countMassage,
    ]
);
$pageContent = includeTemplate(
    'messages.php',
    [
        'conversation'   => $conversation,
        'userTabsActive' => $userTabsActive,
        'messagesList'   => $messagesList,
        'errors' => $errors,
        'mainUserInfo' => $mainUserInfo,
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'header'  => $header,
        'content' => $pageContent,
        'title'   => 'readme: сообщения',
    ]
);
print($layoutContent);

