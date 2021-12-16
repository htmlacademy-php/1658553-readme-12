<?php

/**
 * Получаем массив данных исходя из критериев запроса
 * @param mysqli $mysql соединение с бд
 * @param string $sortId данные сортировки
 * @param int|null $typeId данные типа
 * @param int $offset С какого поста начинать показывать
 * @param int $limit По какой пост показывать
 * @return array массив данных из бд
 */
function getPosts(mysqli $mysql, string $sortId, ?int $typeId, int $offset, int $limit): array
{
    $where = '';
    $data = [];
    // Считаем сколько знаков ? Необходимо для sql запроса
    if (!is_null($typeId)) {
        $where = "WHERE content_type.id = ?";
        $data[] = $typeId;
    }
    // подключили таблицу постов из бд
    $postList = "
SELECT
  post.id AS `post_num`, post.text_content AS `text_content`,post.header AS `header`, post.create_date AS `create_date`,
  post.media AS `media`,user.avatar AS `avatar`,user.login AS `name`, content_type.icon_name AS `icon_name`, count_comments, count_likes
FROM
  post
LEFT JOIN
  user ON user.id = post.user_id
LEFT JOIN
  content_type ON content_type.id = post.content_type_id
LEFT JOIN (
    SELECT
      post_id, count(post_id) AS count_comments
    FROM
      comment
    GROUP BY post_id
    ) AS c ON c.post_id = post.id
    LEFT JOIN (
    SELECT
      post_id, count(post_id) AS count_likes
    FROM
      like_count
    GROUP BY post_id
  ) AS l ON l.post_id = post.id
$where
ORDER BY $sortId DESC
LIMIT $offset, $limit
";

    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $postList,
        $data
    );

    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_all($postListPrepareRes, MYSQLI_ASSOC);
}


/**
 * Получаем массив с информацией о типах контента
 * @param mysqli $mysql Соединение с бд
 * @param string|null $index Типы контента
 * @return array Массив с типами контента
 */
function getContentTypes(mysqli $mysql, string $index = null): array
{
    $query = 'SELECT id, type_name, icon_name FROM content_type';
    $postListPrepare = dbGetPrepareStmt($mysql, $query);

    $rows = mysqli_stmt_get_result($postListPrepare);
    $result = [];

    if (!is_null($index)) {
        foreach ($rows as $item) {
            $result[$item[$index]] = $item;
        }
    } else {
        $result = $rows;
    }

    return $result;
}

/**
 * Выражение для вставки Сортировки в sql запрос
 * @return string Сортировка для вставки в sql запрос
 */
function getSortId(): string
{
    $sort_id = retriveGetString('sort', SORT_VIEWS);

    if (!in_array($sort_id, [SORT_VIEWS, SORT_DATE])) {
        $sort_id = SORT_LIKES;
    }

    return $sort_id;
}

/**
 * для страницы поста
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
 * Запрос на вывод основной части контента
 * @param mysqli $mysql
 * @param int $postId
 * @return array
 */
function getPost(mysqli $mysql, int $postId): array
{
    $data[] = $postId;

    $query = "
SELECT
    post.id AS `post_num`,post.user_id, post.text_content AS `text`,post.header AS `header`,post.author_copy_right AS `author_copy_right`,
    post.create_date AS `create_date`, post.media AS `media`, post.views_number AS `views`,user.avatar AS `avatar`,user.login AS `name`,
    user.reg_date AS `reg_date`, content_type.icon_name AS `icon_name`, count_comments, count_likes,`subscribe_count`
    ,post.content_type_id AS `icon-name`

FROM
  post
    LEFT JOIN
  user ON user.id = post.user_id
    LEFT JOIN
  content_type ON content_type.id = post.content_type_id
    LEFT JOIN (
    SELECT
      post_id, count(post_id) AS count_comments
    FROM
      comment
    GROUP BY post_id
  ) AS c ON c.post_id = post.id
    LEFT JOIN (
    SELECT
      post_id, count(post_id) AS count_likes
    FROM
      like_count
    GROUP BY post_id
  ) AS l ON l.post_id = post.id
    LEFT JOIN (
    SELECT
      user_author_id, count(user_subscribe_id) AS `subscribe_count`
    FROM
      subscribe
      GROUP BY user_author_id
  ) AS subscribe ON subscribe.user_author_id = post.user_id
WHERE  post.id = ?

    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}


/**
 * Узнаем кол-во постов у автора
 * @param mysqli $mysql Соединение с бд
 * @param int $authorID ID автора поста
 * @return array Массив с данными из бд
 */
function authorPostsCount(mysqli $mysql, int $authorID): array
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
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
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
function getCommentsForPost(mysqli $mysql, int $postId, int $offset, int $limit): array
{
    $data[] = $postId;
    $query = "
SELECT
     post.id AS `post_num`, comment.create_date AS `date`, comment.content AS `comment`, user.login AS `name`, user.avatar AS `avatar`
,hashtag.hashtag_name AS `hs-name`
FROM
    post

LEFT JOIN
        comment ON comment.post_id = post.id

LEFT JOIN
        user ON user.id = comment.user_id
LEFT JOIN
  hashtag_post ON hashtag_post.post = post.id
    LEFT JOIN
  hashtag ON hashtag.id = hashtag_post.hashtag

WHERE  post.id = ?
ORDER BY comment.create_date ASC
LIMIT $offset, $limit
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Пагинация для стр популярное
 * @param mysqli $mysql соединение с бд
 * @param int|null $typeId тип контента
 * @return int число постов
 */
function getCountedPages(mysqli $mysql, ?int $typeId)
{
    $where = '';
    $data = [];
    if (!is_null($typeId)) {
        $where = "WHERE post.content_type_id = ?";
        $data[] = $typeId;
    }

    $result = "SELECT COUNT(*) as cnt FROM post $where";

    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $result,
        $data
    );

    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_assoc($postListPrepareRes)['cnt'];
}

function getSearchContent(mysqli $mysql, $search): array
{
    $where = '';
    $data = [];
    // Считаем сколько знаков ? Необходимо для sql запроса
    if (!is_null($search)) {
        if ($search[0] === '#') {
            $where = "LEFT JOIN
            hashtag_post ON hashtag_post.post = post.id
            LEFT JOIN
            hashtag ON hashtag.id = hashtag_post.hashtag
            WHERE MATCH( hashtag.hashtag_name) AGAINST(?)";
        } else {
            $where = "WHERE MATCH(header, text_content) AGAINST(?)";
        }
        $data[] = $search;
    }
    // подключили таблицу постов из бд
    $postList = "
SELECT
  post.id AS `post_num`, post.text_content AS `text_content`,post.header AS `header`, post.create_date AS `create_date`,
  post.media AS `media`,user.avatar AS `avatar`,user.login AS `name`, content_type.icon_name AS `icon_name`,
       count_comments, count_likes,post.content_type_id AS `icon-name`
FROM
  post
LEFT JOIN
  user ON user.id = post.user_id
LEFT JOIN
  content_type ON content_type.id = post.content_type_id
LEFT JOIN (
    SELECT
      post_id, count(post_id) AS count_comments
    FROM
      comment
    GROUP BY post_id
    ) AS c ON c.post_id = post.id
    LEFT JOIN (
    SELECT
      post_id, count(post_id) AS count_likes
    FROM
      like_count
    GROUP BY post_id
  ) AS l ON l.post_id = post.id

$where

";

    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $postList,
        $data
    );

    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_all($postListPrepareRes, MYSQLI_ASSOC);
}

function checkUserLike(mysqli $mysql, string $thisPostId, string $userId){
    $data = [$userId, $thisPostId];
    $postList = "SELECT * FROM like_count WHERE user_id = ? AND post_id = ?";
    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $postList,
        $data
    );
    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);
    return mysqli_fetch_array($postListPrepareRes, MYSQLI_ASSOC);
}
