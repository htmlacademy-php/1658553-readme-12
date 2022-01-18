<?php



require_once('config/config.php');
require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('model/models.php');


/* @var bool $isAuth */
/* @var object $transport */
/* @var mysqli $mysql */

if ($isAuth) {
    header('location: index.php');
} else {
    $mainUser = $_SESSION['user']['id'];
    $countMassage = getCountedUnreadMessages($mysql,$mainUser);
    $curPage = $_GET['page'] ?? 1;
    $pageItems = 9;

    $contentType = retriveGetInt('content_type', null);
    $itemsCount = getCountedPages($mysql, $contentType);
    $pagesCount = ceil($itemsCount / $pageItems);
    if ((int)$curPage < 1) {
        header('location: popular.php');
    } else {
        $offset = ($curPage - 1) * $pageItems;
        $pages = range(1, $pagesCount);
        $sortId = getSortId();
        $postsContent = getPosts($mysql, $sortId, $contentType, $offset,
            $pageItems);
        $contentTypes = getContentTypes($mysql, 'type_name');


        $header = includeTemplate(
            'block/header.php',
            [
                'avatar'   => $_SESSION['user']['avatar'],
                'userName' => $_SESSION['user']['login'],
                'userId'   => $_SESSION['user']['id'],
                'countMassages' => $countMassage,
            ]
        );

        $postContent = includeTemplate(
            'block/block-posts.php',
            [
                'postListRows' => $postsContent,
            ]
        );
        $pagination = includeTemplate(
            'block/pagination.php',
            [
                'pages'      => $pages,
                'pagesCount' => $pagesCount,
                'curPage'    => $curPage,
            ]
        );
        $pageContent = includeTemplate(
            'popular.php',
            [
                'pagination'   => $pagination,
                'sort'         => $sortId,
                'currentType'  => $contentType,
                'postContent'  => $postContent,
                'contentTypes' => $contentTypes,
            ]
        );

        $layoutContent = includeTemplate(
            'layout.php',
            [
                'header'  => $header,
                'content' => $pageContent,
                'title'   => 'readme: популярное',
            ]
        );

        print($layoutContent);
    }

}
