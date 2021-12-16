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

if ($isAuth) {
    header('location: index.php');
} else {
    $search = $_GET['q'] ?? '';
    if ($search){
        $searchContent = getSearchContent($mysql, $search);

    }



    $header = includeTemplate(
        'block/header.php',
        [
            'avatar' => $_SESSION['user']['avatar'],
            'userName' => $_SESSION['user']['login'],
        ]
    );

    $searchBLock = includeTemplate(
        'block/search-block.php',
        [

            'searchContent' => $searchContent,
        ]
    );
    $searchPage = includeTemplate(
        'search.php',
        [
            'search' =>$search,
            'block'=>$searchBLock,
        ]
    );

    $layout_content = includeTemplate(
        'layout.php',
        [
            'content' => $searchPage,
            'header' => $header,
            'title' => 'readme: моя лента',

        ]
    );
    print $layout_content;
}
