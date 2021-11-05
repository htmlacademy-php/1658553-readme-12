<?php
/**
 * для страницы с постами
 */


/**
 * Получаем массив данных исходя из критериев запроса
 * @param mysqli $mysql соединение с бд
 * @param string $sortId данные сортировки
 * @param int|null $typeId данные типа
 * @param int $offset С какого поста начинать показывать
 * @param int $limit По какой пост показывать
 * @return array массив данных из бд
 */
function findPosts(mysqli $mysql, string $sortId, ?int $typeId, int $offset = 0, int $limit = 9): array
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
  post.media AS `media`,user.avatar AS `avatar`,user.name AS `name`, content_type.icon_name AS `icon_name`, count_comments, count_likes
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

    $postListPrepare = db_get_prepare_stmt(
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
    $postListPrepare = db_get_prepare_stmt($mysql, $query);

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
    $sort_id = request_retriveGetString('sort', SORT_VIEWS);

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
 * Подключаем бд для страницы поста, главный контент
 * @param mysqli $mysql Соединение с бд
 * @param int $postId Номер поста
 * @return array Массив с данными из бд
 */
function postContent(mysqli $mysql, int $postId): array
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
function commentsViewsCount(mysqli $mysql, int $postId): array
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
function findAuthorId(mysqli $mysql, ?int $postId): ?int
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
function findHashtags(mysqli $mysql, int $postId): array
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
function commentCount(mysqli $mysql, int $postId): array
{
    $data[] = $postId;
    $query = "
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
function commentAllList(mysqli $mysql, int $postId): array
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
