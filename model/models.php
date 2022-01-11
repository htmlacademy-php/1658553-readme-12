<?php

/**
 * Получаем массив данных исходя из критериев запроса
 *
 * @param mysqli   $mysql  соединение с бд
 * @param string   $sortId данные сортировки
 * @param int|null $typeId данные типа
 * @param int      $offset С какого поста начинать показывать
 * @param int      $limit  По какой пост показывать
 *
 * @return array массив данных из бд
 */
function getPosts(
    mysqli $mysql,
    string $sortId,
    ?int $typeId,
    int $offset,
    int $limit
): array {
    $where = '';
    $data = [];
    // Считаем сколько знаков ? Необходимо для sql запроса
    if (!is_null($typeId)) {
        $where = "WHERE content_type.id = ?";
        $data[] = $typeId;
    }
    // подключили таблицу постов из бд
    $postList = "
SELECT post.id                AS `post_num`,
       post.text_content      AS `text_content`,
       post.header            AS `header`,
       post.create_date       AS `create_date`,
       post.author_copy_right,
       post.media             AS `media`,
       user.avatar            AS `avatar`,
       user.login             AS `name`,
       user.id                AS `author_id`,
       content_type.icon_name AS `icon_name`,
       count_comments,
       count_likes
FROM post
       LEFT JOIN
     user ON user.id = post.user_id
       LEFT JOIN
     content_type ON content_type.id = post.content_type_id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_comments
  FROM comment
  GROUP BY post_id
) AS c ON c.post_id = post.id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_likes
  FROM like_count
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
 *
 * @param mysqli      $mysql Соединение с бд
 * @param string|null $index Типы контента
 *
 * @return array Массив с типами контента
 */
function getContentTypes(mysqli $mysql, string $index = null): array
{
    $query = '
SELECT id, type_name, icon_name
FROM content_type
    ';
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
 *
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
 *
 * @param mysqli $mysql  параметры соединения с sql
 * @param int    $postId отвалидированное значение int
 *
 * @return bool True если пост существует и false если нет
 */
function isPostExist(mysqli $mysql, int $postId): bool
{
    $truePost = "
SELECT post.id
FROM post
WHERE post.id = $postId
    ";
    $truePostResult = mysqli_query($mysql, $truePost);
    $postId = mysqli_fetch_all($truePostResult, MYSQLI_ASSOC);

    if (empty($postId) == true) {
        return false;
    }

    return true;
}

/**
 * Проверяем существует ли пользователь
 *
 * @param mysqli   $mysql параметры соединения с sql
 * @param int|null $userId
 *
 * @return bool True если юзер существует и false если нет
 */
function isUserExist(mysqli $mysql, ?int $userId): bool
{
    if (!is_null($userId)) {
        $trueUser = "
SELECT user.id
FROM user
WHERE user.id = $userId
    ";
        $trueUserResult = mysqli_query($mysql, $trueUser);
        $userResultId = mysqli_fetch_all($trueUserResult, MYSQLI_ASSOC);

        if (empty($userResultId) == true) {
            return false;
        }

        return true;
    }

    return false;
}


/**
 * Запрос на вывод основной части контента
 *
 * @param mysqli $mysql
 * @param int    $postId
 *
 * @return array
 */
function getPost(mysqli $mysql, int $postId): array
{
    $data[] = $postId;

    $query = "
SELECT post.id                AS `post_num`,
       post.user_id,
       post.text_content      AS `text`,
       post.header            AS `header`,
       post.author_copy_right AS `author_copy_right`,
       post.create_date       AS `create_date`,
       post.media             AS `media`,
       post.views_number      AS `views`,
       user.avatar            AS `avatar`,
       user.login             AS `name`,
       user.reg_date          AS `reg_date`,
       content_type.icon_name AS `icon_name`,
       count_comments,
       count_likes,
       `subscribe_count`,
       post.content_type_id   AS `icon-name`

FROM post
       LEFT JOIN
     user ON user.id = post.user_id
       LEFT JOIN
     content_type ON content_type.id = post.content_type_id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_comments
  FROM comment
  GROUP BY post_id
) AS c ON c.post_id = post.id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_likes
  FROM like_count
  GROUP BY post_id
) AS l ON l.post_id = post.id
       LEFT JOIN (
  SELECT user_author_id,
         count(user_subscribe_id) AS `subscribe_count`
  FROM subscribe
  GROUP BY user_author_id
) AS subscribe ON subscribe.user_author_id = post.user_id


WHERE post.id = ?

    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * получаем хештеги поста
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $postId ид поста
 *
 * @return array|false|null
 */
function GetHashtag(mysqli $mysql, int $postId)
{
    $data[] = $postId;
    $query = "
    SELECT hashtag.hashtag_name   AS `hs-name`
FROM post
       LEFT JOIN
     hashtag_post ON hashtag_post.post = post.id
       LEFT JOIN
     hashtag ON hashtag.id = hashtag_post.hashtag
WHERE post.id = ?
";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Узнаем кол-во постов у автора
 *
 * @param mysqli $mysql    Соединение с бд
 * @param int    $authorID ID автора поста
 *
 * @return array Массив с данными из бд
 */
function authorPostsCount(mysqli $mysql, int $authorID): array
{
    $data[] = $authorID;
    $query = "
SELECT count(post.id) AS `publication_count`
FROM post
       LEFT JOIN
     user ON user.id = post.user_id

WHERE user_id = ?
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}

function repostCount(mysqli $mysql, int $postId)
{
    $data[] = $postId;
    $query = "
SELECT count(id) AS repost_count
FROM post
WHERE originalPostId = ?;
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * запрос на список комментариев
 */
/**
 * @param mysqli $mysql  Соединение с бд
 * @param int    $postId Номер поста
 *
 * @return array Массив с данными из бд
 */
function getCommentsForPost(mysqli $mysql, int $postId): array
{
    $data[] = $postId;
    $query = "
SELECT
       comment.create_date  AS `date`,
       comment.content      AS `comment`,
       user.login           AS `name`,
       user.avatar          AS `avatar`,
        user.id             AS `user_comment_id`

FROM post

       LEFT JOIN
     comment ON comment.post_id = post.id

       LEFT JOIN
     user ON user.id = comment.user_id


WHERE post.id = ?
ORDER BY comment.create_date ASC
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Пагинация для стр популярное
 *
 * @param mysqli   $mysql  соединение с бд
 * @param int|null $typeId тип контента
 *
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

    $result = "
SELECT COUNT(*) as cnt
FROM post
$where";

    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $result,
        $data
    );

    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_assoc($postListPrepareRes)['cnt'];
}

/**
 * Функция вывода постов из бд при поиске
 *
 * @param mysqli      $mysql  соединение с бд
 * @param string|null $search поисковый запрос
 *
 * @return array посты из бд
 */
function getSearchContent(mysqli $mysql, ?string $search): array
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
SELECT post.id                AS `post_num`,
       post.text_content      AS `text_content`,
       post.header            AS `header`,
       post.create_date       AS `create_date`,
       post.media             AS `media`,
       user.avatar            AS `avatar`,
       user.login             AS `name`,
       content_type.icon_name AS `icon_name`,
       count_comments,
       count_likes,
       post.content_type_id   AS `icon-name`
FROM post
       LEFT JOIN
     user ON user.id = post.user_id
       LEFT JOIN
     content_type ON content_type.id = post.content_type_id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_comments
  FROM comment
  GROUP BY post_id
) AS c ON c.post_id = post.id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_likes
  FROM like_count
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

/**
 * Проверяем, лайкал ли пользователь уже этот пост
 *
 * @param mysqli $mysql      соединение с бд
 * @param string $thisPostId ид поста
 * @param string $userId     ид юзера
 *
 * @return bool|false возвращает массив если не лайкал
 */
function isUserLike(mysqli $mysql, string $thisPostId, string $userId): bool
{
    $data = [$userId, $thisPostId];
    $postList = "SELECT *
FROM like_count
WHERE user_id = ?
    AND post_id = ?";
    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $postList,
        $data
    );
    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);
    $result = mysqli_fetch_array($postListPrepareRes, MYSQLI_ASSOC);
    if ($result) {
        return false;
    }

    return true;
}

/**
 * Проверяем существует ли пост
 *
 * @param mysqli $mysql    параметры соединения с sql
 * @param int    $userId   id пользователя
 * @param        $authorID int id автора
 *
 * @return bool True если пост существует и false если нет
 */
function isUserSubscribe(mysqli $mysql, int $authorID, int $userId): bool
{
    $trueSubscribe = "
SELECT user_subscribe_id,
       user_author_id
FROM subscribe
WHERE user_subscribe_id = $userId
    AND user_author_id = $authorID
    ";
    $trueSubscribeResult = mysqli_query($mysql, $trueSubscribe);
    $subscribe = mysqli_fetch_all($trueSubscribeResult, MYSQLI_ASSOC);

    if ($subscribe) {
        return false;
    }

    return true;
}

function getFeedPosts(
    mysqli $mysql,
    string $sortId,
    ?int $typeId,
    int $userId
): array {
    $where = 'WHERE user_subscribe_id = ?';
    $data[] = $userId;
    // Считаем сколько знаков ? Необходимо для sql запроса
    if (!is_null($typeId)) {
        $where = "WHERE user_subscribe_id = ? AND  content_type.id = ?";
        $data = [
            0 => $userId,
            1 => $typeId,
        ];
    }
    $postsList = "
SELECT post.id                AS `post_num`,
       post.text_content      AS `text_content`,
       post.header            AS `header`,
       post.create_date       AS `create_date`,
       post.author_copy_right,
       post.media             AS `media`,
       user.avatar            AS `avatar`,
       user.login             AS `name`,
       content_type.icon_name AS `icon_name`,
       count_comments,
       count_likes,
       sub.user_subscribe_id,
       sub.user_author_id

FROM post
       LEFT JOIN
     user ON user.id = post.user_id
       LEFT JOIN
     content_type ON content_type.id = post.content_type_id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_comments
  FROM comment
  GROUP BY post_id
) AS c ON c.post_id = post.id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_likes
  FROM like_count
  GROUP BY post_id
) AS l ON l.post_id = post.id
       LEFT JOIN (
  SELECT user_subscribe_id, user_author_id
  FROM subscribe
) AS sub ON sub.user_author_id = user.id

$where
ORDER BY $sortId DESC
    ";
    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $postsList,
        $data
    );

    $postsListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_all($postsListPrepareRes, MYSQLI_ASSOC);
}

/**
 * Получаем информацию о пользователе для профиля
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $userId id пользователя
 *
 * @return array информация
 */
function getInfoProfileUser(mysqli $mysql, int $userId): array
{
    $where = 'WHERE user.id = ?;';
    $data[] = $userId;
    $profileInfo = "
SELECT user.avatar AS `avatar`,
       user.login  AS `name`,
       user.id     AS `user_id`,
       user.reg_date AS `reg_date`,
       `publication_count`,
       `subscribe_count`

FROM user
       LEFT JOIN (
  SELECT count(post.id) AS publication_count,
         post.user_id
  FROM post
  GROUP BY post.user_id
) AS post ON post.user_id = user.id
       LEFT JOIN (
  SELECT count(user_subscribe_id) AS subscribe_count,
         user_author_id
  FROM subscribe
  GROUP BY user_author_id
) AS subscribe ON subscribe.user_author_id = user.id


$where
    ";
    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $profileInfo,
        $data
    );

    $postsListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_array($postsListPrepareRes, MYSQLI_ASSOC);
}

/**
 * Вытягиваем всю информацию из таблицы пост для копирования в репост
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $postId id поста
 *
 * @return array|false|null
 */
function getInfoForRepost(mysqli $mysql, int $postId)
{
    $where = 'WHERE post.id = ?;';
    $data[] = $postId;
    $postInfo = "
SELECT * FROM post
$where
    ";
    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $postInfo,
        $data
    );
    $postsListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_array($postsListPrepareRes, MYSQLI_ASSOC);
}

/**
 * Отображение списка постов для страницы с профилем
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $userId id пользователя, хозяина профиля
 *
 * @return array массив с постами
 */
function getProfilePosts(mysqli $mysql, int $userId)
{
    $where = 'WHERE user.id = ?';
    $data[] = $userId;
    $profilePosts = "
SELECT post.id                AS `post_num`,
       post.user_id,
       post.text_content      AS `text_content`,
       post.header            AS `header`,
       post.author_copy_right AS `author_copy_right`,
       post.create_date       AS `create_date`,
       post.media             AS `media`,
       post.views_number      AS `views`,
       user.avatar            AS `avatar`,
       user.login             AS `name`,
       user.reg_date          AS `reg_date`,
       content_type.icon_name AS `icon_name`,
       count_comments,
       count_likes,
       `subscribe_count`,
       post.content_type_id   AS `icon-name`,
       post.repost,
       post.originalPostId


FROM post
       LEFT JOIN
     user ON user.id = post.user_id
       LEFT JOIN
     content_type ON content_type.id = post.content_type_id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_comments
  FROM comment
  GROUP BY post_id
) AS c ON c.post_id = post.id
       LEFT JOIN (
  SELECT post_id,
         count(post_id) AS count_likes
  FROM like_count
  GROUP BY post_id
) AS l ON l.post_id = post.id
       LEFT JOIN (
  SELECT user_author_id,
         count(user_subscribe_id) AS `subscribe_count`
  FROM subscribe
  GROUP BY user_author_id
) AS subscribe ON subscribe.user_author_id = post.user_id


$where
ORDER BY create_date DESC;
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $profilePosts, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * информация об истинном авторе-пользователе, если пост на странице профиля пользователя является репостом
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $postId id поста
 *
 * @return array|false|null информация о пользователе-авторе поста оригинала
 */
function repostUserInfo(mysqli $mysql, int $postId)
{
    $data[] = $postId;
    $query = "
SELECT user.login, user.avatar,user.id
FROM post
LEFT JOIN
    user ON user.id = post.user_id
WHERE post.id = ?
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Отображение списка лайков для страницы с профилем
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $userId id пользователя, хозяина профиля
 *
 * @return array массив с данными лайков
 */
function getProfileLikes(mysqli $mysql, int $userId)
{
    $data[] = $userId;
    $query = "
SELECT media, content_type_id, lc.user_id AS user_who_likes, lc.like_date, user.avatar, user.login,post.id AS post_id
FROM post
       LEFT JOIN like_count lc ON post.id = lc.post_id
        LEFT JOIN user ON user.id = lc.user_id
WHERE post.user_id = ?
  AND lc.user_id IS NOT NULL
ORDER BY lc.like_date DESC
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * список id пользователей подписанных на хозяина профиля
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $userId id хозяина профиля
 *
 * @return array массив с id пользователей, которые подписаны на хозяина профиля
 */
function getProfileSubscribe(mysqli $mysql, int $userId)
{
    $data[] = $userId;
    $query = "
SELECT user_subscribe_id,user.login,user.email
FROM subscribe
       LEFT JOIN user on user_subscribe_id = user.id
WHERE user_author_id = ?
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * Узнаем email и имя пользователя исходя из данных об его id
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $userId id пользователя
 *
 * @return array email пользователя
 */
function getUserEmail(mysqli $mysql, int $userId)
{
    $data[] = $userId;
    $query = "
SELECT email, login
FROM user
WHERE user.id = ?
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_array($postPrepareRes, MYSQLI_ASSOC);
}

/**
 * выводим список всех диалогов пользователя
 *
 * @param mysqli $mysql  соединение с бд
 * @param int    $userId id пользователя
 *
 * @return array список диалогов
 */
function getConversations(mysqli $mysql, int $userId)
{
    $data[] = $userId;
    $query = "
SELECT user.login, user.id,user.avatar
FROM conversation
LEFT JOIN user on user.id = conversation.second
WHERE first = ?;
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}

function getMessages(mysqli $mysql, int $userId, int $interlocutor)
{
    $data = [$userId, $interlocutor, $interlocutor, $userId];
    $query = "
SELECT message.create_date, message.content,message.user_receiver_id, message.user_sender_id, user.avatar,user.login
FROM message
LEFT JOIN user ON user.id = message.user_sender_id
WHERE user_sender_id = ? AND user_receiver_id = ?
   OR user_sender_id = ? AND user_receiver_id = ?
    ";
    $postPrepare = dbGetPrepareStmt($mysql, $query, $data);
    $postPrepareRes = mysqli_stmt_get_result($postPrepare);

    return mysqli_fetch_all($postPrepareRes, MYSQLI_ASSOC);
}
