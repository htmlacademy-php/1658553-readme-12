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
    $search = $_GET['q'] ?? '';
    $searchContent = null;
    if ($search) {
        $searchContent = getSearchContent($mysql, $search);
    }

    if ($searchContent) {
        $searchBLock = includeTemplate(
            'block/search-block.php',
            [
                'searchContent' => $searchContent,
            ]
        );
    } else {
        $searchBLock = includeTemplate(
            'block/no-results.php',
            [
                'searchContent' => $searchContent,
            ]
        );
    }

    $header = includeTemplate(
        'block/header.php',
        [
            'avatar' => $_SESSION['user']['avatar'],
            'userName' => $_SESSION['user']['login'],
        ]
    );


    $searchPage = includeTemplate(
        'search.php',
        [
            'search' => $search,
            'block' => $searchBLock,
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
