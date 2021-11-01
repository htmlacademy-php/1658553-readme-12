<?php

require_once('src/helpers.php');
require_once('src/function.php');
require_once('src/request.php');
require_once('src/db.php');
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
            'main_content' => mainContent($mysql, $postId),
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
/**
 * Обработка данных
 */

/**
 * Проверяем существует ли пост
 * @param mysqli $mysql параметры соединения с sql
 * @param int $postId отвалидированное значение int
 * @return bool True если пост существует и false если нет
 */
function isPostExist(mysqli $mysql, int $postId): bool
{
    $truePost = "
SELECT
    post.id
FROM
    post
WHERE
    post.id = $postId
    ";
    $truePostResult = mysqli_query($mysql, $truePost);
    $postId = mysqli_fetch_all($truePostResult, MYSQLI_ASSOC);

    if (empty($postId) == true) {
        return false;
    } else {
        return true;
    }
}

/**
 * Подключаем бд для страницы поста, главный контент
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function mainContent(mysqli $mysql, int $postId): array
{
    $data[] = $postId;

    $query = "
SELECT
    post.id AS `post_num`, post.text_content as `text`,post.header AS `header`,post.media AS `media`,
       post.author_copy_right AS `author_copy_right`, content_type.icon_name AS `icon_name`


FROM
    post
        LEFT JOIN
        user ON user.id = post.user_id
        LEFT JOIN
        content_type ON content_type.id = post.content_type_id
WHERE  post.id = ?
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}


/**
 * Запрос на информацию об авторе
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function authorInfo(mysqli $mysql, int $postId): array
{
    $data[] = $postId;
    $query = "
SELECT
     user.reg_date AS `reg_date`, user.name AS `name`, user.avatar AS `avatar`, count(subscribe.user_subscribe_id) AS `subscribe-count`,
       count(like_count.post_id) AS `like-count`

FROM
    post
        LEFT JOIN
        user ON user.id = post.user_id
        LEFT JOIN
        content_type ON content_type.id = post.content_type_id
        LEFT JOIN
        subscribe ON user_author_id = post.user_id
        LEFT JOIN
          like_count ON like_count.post_id = post.id
WHERE  post.id = ?
GROUP BY post.id
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}



/**
 * Запрос на кол-во лайков
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function likeCount(mysqli $mysql, int $postId): array
{
    $data[] = $postId;
    $query = "
SELECT
   count(like_count.post_id) AS `like-count`

FROM
  post

    LEFT JOIN
  like_count ON like_count.post_id = post.id

WHERE  post.id = ?
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}



/**
 * Запрос на кол-во комментариев и просмотров
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function commentsViewsCount(mysqli $mysql, int $postId):array
{
    $data[] = $postId;
    $query = "
SELECT
    count(comment.id) AS `comment-count`, post.views_number AS `views`

FROM
  post
LEFT JOIN
      comment ON comment.post_id = post.id

WHERE  post.id = ?
GROUP BY post.id
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}



/**
 *
 */
/**
 * Узнаем id автора для проверки кол-ва постов
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return int ID автора поста
 */
function findAuthorId(mysqli $mysql, ?int $postId):?int
{
    $data[] = $postId;
    $query = "
SELECT
     post.user_id
FROM
    post

WHERE  post.id = ?
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);
    $postPrepareResRows = mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
    $authorArray = array_pop($postPrepareResRows);
    return array_pop($authorArray);
}





/**
 * Узнаем кол-во постов у автора
 * @param mysqli $mysql Соединение с бд
 * @param int $authorID ID автора поста
 * @return array Массив с данными из бд
 */
function authorPostsCount(mysqli $mysql, int $authorID):array
{
    $data[] = $authorID;
    $query = "
SELECT
       count(post.id) AS `publication_count`
FROM
  post
    LEFT JOIN
  user ON user.id = post.user_id
WHERE user_id = ?
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}



/**
 * Запрос на хештеги
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function findHashtags(mysqli $mysql, int $postId):array
{
    $data[] = $postId;
    $query = "
    SELECT
     post.id AS `post_num`, hashtag.hashtag_name AS `hs-name`

FROM
    post
LEFT JOIN
        hashtag_post ON hashtag_post.post = post.id
LEFT JOIN
        hashtag ON hashtag.id = hashtag_post.hashtag
WHERE  post.id = ?
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * запрос на список комментариев
 */
/**
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @param int $offset С какого начинать показывать
 * @param int $limit По какой показывать
 * @return array Массив с данными из бд
 */
function commentList(mysqli $mysql, int $postId, int $offset = 0, int $limit = 2): array
{
    $data[] = $postId;
    $query = "
SELECT
     post.id AS `post_num`, comment.create_date AS `date`, comment.content AS `comment`, user.name AS `name`, user.avatar AS `avatar`

FROM
    post

LEFT JOIN
        comment ON comment.post_id = post.id

LEFT JOIN
        user ON user.id = comment.user_id

WHERE  post.id = ?
ORDER BY comment.create_date ASC
LIMIT $offset, $limit
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Число комментариев под списком комментариев
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function commentCount(mysqli $mysql, int $postId):array
{
    $data[] = $postId;
    $query ="
SELECT
  post.id AS `post_num`, count(comment.id) AS `comment-count`

FROM
  post

    LEFT JOIN
  comment ON comment.post_id = post.id

    LEFT JOIN
  user ON user.id = comment.user_id

WHERE  post.id = ?
GROUP BY post.id
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Список комментариев после второго
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function commentAllList(mysqli $mysql, int $postId):array
{
    $data[] = $postId;
    $query = "
SELECT
     post.id AS `post_num`, comment.create_date AS `date`, comment.content AS `comment`, user.name AS `name`, user.avatar AS `avatar`

FROM
    post

LEFT JOIN
        comment ON comment.post_id = post.id

LEFT JOIN
        user ON user.id = comment.user_id

WHERE  post.id = ?
ORDER BY comment.create_date ASC
    ";
    $postPrepare = db_get_prepare_stmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}





