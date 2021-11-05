<?php

require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('src/db.php');
require_once('model/models.php');
/* @var mysqli $mysql */

$is_auth = rand(0, 1);
$user_name = 'Владик';
/**
 * Входящие данные
 */
$postId = request_retriveGetInt('post-id', 0);
$authorID = findAuthorId($mysql, $postId);
$postIdExist = isPostExist($mysql, $postId);






/**
 * Отображение данных
 */

if ($postIdExist == false) {
    header("Location: Not-found.php");
} else {
    $post_content = include_template(
        'post.php',
        [
            'post_content' => postContent($mysql, $postId),
            'author_info' => authorInfo($mysql, $postId),
            'like_count' => likeCount($mysql, $postId),
            'comments_views_count' => commentsViewsCount($mysql, $postId),
            'authorPosts_count' => authorPostsCount($mysql, $authorID),
            'hashtags' => findHashtags($mysql, $postId),
            'comment_list' => commentList($mysql, $postId),
            'comment_count' => commentCount($mysql, $postId),
            'comment_all_list' => commentAllList($mysql, $postId),
        ]
    );
    $layout_content = include_template(
        'layout.php',
        [

            'content' => $post_content,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'title' => 'readme: публикация',

        ]
    );


    print ($layout_content);
}





