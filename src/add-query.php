<?php

/**
 * Добавление заголовка в БД
 *
 * @param mysqli $mysql   Соединение с БД
 * @param        $heading string Ключ массива POST с заголовком
 *
 * @return int|string Возвращаем последний ID записи, куда добавили заголовок
 */
function addHeading(mysqli $mysql, string $heading, $authorId): int
{
    $date = date('Y-m-d H:i:s');
    $header = validateInput($_POST[$heading]);
    $queryPost = sprintf(
        "INSERT INTO post  (create_date,header,user_id,repost) VALUES ('$date', '%s','$authorId',false)",
        mysqli_real_escape_string($mysql, $header)
    );
    mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);

    return $lastPostId;
}


/**
 * Функция добавления хештега(если он есть)
 *
 * @param mysqli $mysql      Соединение с БД
 * @param        $sharp      string Ключ массива POST с хештегом
 * @param int    $lastPostId Id записи куда добавляем хештеги
 *
 * @return int Возвращаем Id записи в которую добавили хештеги
 */
function addSharp(mysqli $mysql, string $sharp, int $lastPostId): int
{
    if (!empty($_POST[$sharp])) {
        $separate = explode(' ', $_POST[$sharp]);
        foreach ($separate as $keys => $hash) {
            $hashtag = validateInput($hash);
            $queryHashtag = sprintf(
                "INSERT INTO hashtag (hashtag_name) VALUES ('%s')",
                mysqli_real_escape_string($mysql, $hashtag)
            );
            mysqli_query($mysql, $queryHashtag);
            $lastHashtagID = mysqli_insert_id($mysql);
            $hashtagPost
                = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
            mysqli_query($mysql, $hashtagPost);
        }

        return $lastPostId;
    }

    return $lastPostId;
}

/**
 * Функция добавления фото
 *
 * @param mysqli $mysql      Соединение с БД
 * @param int    $lastPostId Id записи, в которую мы добавляем фото
 *
 * @return int Возвращаем Id записи в которую добавили фото
 */
function addPhotoUrl(mysqli $mysql, int $lastPostId, $contentType): int
{
    $date = date('YmdHis');
    $contentType = $contentType['photo']['id'];
    mkdir('valid');
    if (existAddFiles('userpic-file-photo')) {
        $uploads_dir = 'valid';
        if ($_FILES['userpic-file-photo']['error'] == 0) {
            $tmp_name = $_FILES['userpic-file-photo']['tmp_name'];
            $name = basename($_FILES['userpic-file-photo']['name']);
            $name = $date.$name;
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
            $media = validateInput('content/'.$name);
        }
    } else {
        $media = validateInput($_POST['photoUrl']);
    }
    $queryPost = sprintf(
        "UPDATE post SET media = '%s', content_type_id = '$contentType' WHERE id = '$lastPostId'",
        mysqli_real_escape_string($mysql, $media)
    );
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления видео
 *
 * @param mysqli $mysql      Соединение с БД
 * @param        $video      string Ключ массива POST с видео
 * @param int    $lastPostId Id записи, в которую мы добавляем видео
 *
 * @return int Возвращаем Id записи в которую добавили видео
 */
function addVideoUrl(
    mysqli $mysql,
    string $video,
    int $lastPostId,
    $contentType
): int {
    $media = validateInput($_POST[$video]);
    $queryPost = sprintf(
        "UPDATE post SET media = '%s',content_type_id = '$contentType' WHERE id = '$lastPostId'",
        mysqli_real_escape_string($mysql, $media)
    );
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления текста
 *
 * @param mysqli $mysql      Соединение с БД
 * @param        $text       string Ключ массива POST с текстом
 * @param int    $lastPostId Id записи, в которую мы добавляем текст
 *
 * @return int Возвращаем Id записи в которую добавили текст
 */
function addText(
    mysqli $mysql,
    string $text,
    int $lastPostId,
    $contentType
): int {
    $content = validateInput($_POST[$text]);
    $queryPost = sprintf(
        "UPDATE post SET text_content = '%s', content_type_id = '$contentType' WHERE id = '$lastPostId'",
        mysqli_real_escape_string($mysql, $content)
    );
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}


/**
 * Функция добавления цитаты
 *
 * @param mysqli $mysql      Соединение с БД
 * @param        $cite       string Массива POST с цитатой
 * @param int    $lastPostId Id записи, в которую мы добавляем цитату
 *
 * @return int Возвращаем Id записи в которую добавили цитату
 */
function addCite(
    mysqli $mysql,
    string $cite,
    int $lastPostId,
    $contentType
): int {
    $content = validateInput($_POST[$cite]);
    $queryPost = sprintf(
        "UPDATE post SET text_content = '%s', content_type_id = '$contentType' WHERE id = '$lastPostId'",
        mysqli_real_escape_string($mysql, $content)
    );
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления автора цитаты
 *
 * @param mysqli $mysql      Соединение с БД
 * @param        $author     string Ключ массива POST с автором цитаты
 * @param int    $lastPostId Id записи, в которую мы добавляем автора цитаты
 *
 * @return int Возвращаем Id записи в которую добавили автора цитаты
 */
function addQuoteAuthor(mysqli $mysql, string $author, int $lastPostId): int
{
    $content = validateInput($_POST[$author]);
    $queryPost = sprintf(
        "UPDATE post SET author_copy_right = '%s' WHERE id = '$lastPostId'",
        mysqli_real_escape_string($mysql, $content)
    );
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления автора ссылки
 *
 * @param mysqli $mysql      Соединение с БД
 * @param        $link       string Ключ массива POST с сылкой
 * @param int    $lastPostId Id записи, в которую мы добавляем ссылку
 *
 * @return int Возвращаем Id записи в которую добавили ссылку
 */
function addLink(
    mysqli $mysql,
    string $link,
    int $lastPostId,
    $contentType
): int {
    $content = validateInput($_POST[$link]);
    $queryPost = sprintf(
        "UPDATE post SET media = '%s', content_type_id = '$contentType' WHERE id = '$lastPostId'",
        mysqli_real_escape_string($mysql, $content)
    );
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}


/**
 * Функция добавления почты пользователя при регистрации
 *
 * @param mysqli $mysql Соединение с бд
 *
 * @return int Возвращаем id записи при регистрации
 */
function addUserEmail(mysqli $mysql): int
{
    $regDate = date('Y-m-d H:i:s');
    $email = validateInput($_POST['email']);
    $query = "INSERT INTO user (reg_date, email) VALUES ('$regDate','$email')";
    mysqli_query($mysql, $query);
    $lastUserID = mysqli_insert_id($mysql);

    return $lastUserID;
}

/**
 * Функция добавления логина пользователя при регистрации
 *
 * @param mysqli $mysql      Соединение с бд
 * @param int    $lastUserId Id записи регистрации
 *
 * @return int  Возвращаем id записи при регистрации
 */
function addUserLogin(mysqli $mysql, int $lastUserId): int
{
    $login = validateInput($_POST['login']);
    $queryPost = sprintf(
        "UPDATE user SET login = '%s' WHERE id = '$lastUserId'",
        mysqli_real_escape_string($mysql, $login)
    );
    mysqli_query($mysql, $queryPost);

    return $lastUserId;
}

/**
 * Функция добавления пароля пользователя при регистрации
 *
 * @param mysqli $mysql      Соединение с бд
 * @param int    $lastUserId Id записи регистрации
 *
 * @return int Возвращаем id записи при регистрации
 */
function addUserPass(mysqli $mysql, int $lastUserId): int
{
    $password = validateInput($_POST['password']);
    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    $queryPost
        = "UPDATE user SET password = '$hashPass' WHERE id = '$lastUserId'";
    mysqli_query($mysql, $queryPost);

    return $lastUserId;
}

/**
 * Функция добавления аватара пользователя при регистрации
 *
 * @param mysqli $mysql      Соединение с бд
 * @param int    $lastUserId Id записи регистрации
 *
 * @return int Возвращаем Id записи при регистрации
 */
function addUserAvatar(mysqli $mysql, int $lastUserId): int
{
    mkdir('valid');
    $date = date('YmdHis');
    $uploads_dir = 'valid';
    $name = basename($_FILES['userpic-file']['name']);
    $name = $date.$name;
    if ($_FILES['userpic-file']['error'] === 0) {
        $tmp_name = $_FILES['userpic-file']['tmp_name'];
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
        $avatar = validateInput('content/'.$name);
        $queryPost = sprintf(
            "UPDATE user SET avatar = '%s' WHERE id = '$lastUserId'",
            mysqli_real_escape_string($mysql, $avatar)
        );
        mysqli_query($mysql, $queryPost);
    } else {
        $queryPost
            = "UPDATE user SET avatar = 'img/Anon.jpg' WHERE id = '$lastUserId'";
        mysqli_query($mysql, $queryPost);
    }


    return $lastUserId;
}

/**
 * добавляет кол-во просмотров при посещении страницы поста
 *
 * @param mysqli   $mysql      соединение с бд
 * @param int      $thisPostId посещаемый пост
 * @param int|null $views      кол-во просмотров до посещения
 */
function addView(mysqli $mysql, int $thisPostId, ?int $views)
{
    $queryPost
        = "UPDATE post SET views_number = $views+1 WHERE id = '$thisPostId'";
    mysqli_query($mysql, $queryPost);
}


/**
 * добавляет лайк к посту
 *
 * @param mysqli $mysql      соединение с бд
 * @param int    $thisPostId лайкаемый пост
 * @param int    $userId     пользователь который лайкнул
 */
function addLike(mysqli $mysql, int $thisPostId, int $userId)
{
    $date = date('Y-m-d H:i:s');
    $queryPost
        = "INSERT INTO like_count (user_id, post_id, like_date) VALUES ('$userId','$thisPostId','$date')";
    mysqli_query($mysql, $queryPost);
}

/**
 * снимает лайк с поста
 *
 * @param mysqli $mysql      соединение с бд
 * @param int    $thisPostId ид поста
 * @param int    $userId     ид пользователя
 */
function removeLike(mysqli $mysql, int $thisPostId, int $userId)
{
    $queryPost
        = "DELETE FROM like_count WHERE user_id = $userId AND post_id = $thisPostId";
    mysqli_query($mysql, $queryPost);
}

/**
 * Добавляет подписку на пользователя
 *
 * @param mysqli $mysql    соединение с бд
 * @param int    $authorId пользователь на которого подписываются
 * @param int    $userId   пользователь который подписывается
 */
function addSubscribe(mysqli $mysql, int $authorId, int $userId)
{
    $queryPost
        = "INSERT INTO subscribe (user_subscribe_id, user_author_id) VALUES ('$userId','$authorId')";
    mysqli_query($mysql, $queryPost);
}

/**
 * снимает подписку на пользователя
 *
 * @param mysqli $mysql    соединение с бд
 * @param int    $authorId id автора
 * @param int    $userId   ид пользователя
 */
function removeSubscribe(mysqli $mysql, int $authorId, int $userId)
{
    $queryPost
        = "DELETE FROM subscribe WHERE user_subscribe_id = $userId AND user_author_id = $authorId";
    mysqli_query($mysql, $queryPost);
}

/**
 * Добавление комментария в бд
 *
 * @param mysqli $mysql  Соединение с БД
 * @param string $key    ключ массива $_POST
 * @param int    $postId номер поста
 * @param int    $userId автор комментария
 *
 * @return bool если успешно возвращаем true
 */
function addComment(mysqli $mysql, string $key, int $postId, int $userId): bool
{
    $date = date('Y-m-d H:i:s');
    $comment = validateInput($_POST[$key]);
    $queryComment = sprintf(
        "INSERT INTO comment  (create_date,content,user_id,post_id) VALUES ('$date', '%s','$userId','$postId')",
        mysqli_real_escape_string($mysql, $comment)
    );
    mysqli_query($mysql, $queryComment);
    $lastPostId = mysqli_insert_id($mysql);

    return is_int($lastPostId);
}

/**
 * Создание записи репоста
 *
 * @param mysqli $mysql      соединение с бд
 * @param array  $repostInfo информация о посте который репостим
 *
 * @return bool true если добавление успешно
 */
function addRepost(mysqli $mysql, array $repostInfo)
{
    $date = date('Y-m-d H:i:s');
    $query = "INSERT INTO post  (create_date) VALUES ('$date')";
    mysqli_query($mysql, $query);
    $lastPostId = mysqli_insert_id($mysql);

    foreach ($repostInfo as $columnName => $value) {
        $queryRepost
            = "UPDATE post SET $columnName = '$value' WHERE id = '$lastPostId'";
        mysqli_query($mysql, $queryRepost);
    }

    return is_int($lastPostId);
}

/**
 * добавляет пользователей в диалог если до этого диалога не было
 *
 * @param mysqli $mysql        соединение с бд
 * @param int    $profileUser  id залогиненого пользователя
 * @param int    $interlocutor id собеседника
 */
function addConversation(mysqli $mysql, int $profileUser, int $interlocutor)
{
    $query
        = "INSERT INTO conversation  (first, second) VALUES ('$profileUser', '$interlocutor')";

    return mysqli_query($mysql, $query);
}

/**
 * @param mysqli $mysql
 * @param string $content
 * @param int    $profileUser
 * @param int    $interlocutor
 *
 * @return bool|mysqli_result
 */
function addMessage(
    mysqli $mysql,
    string $content,
    int $profileUser,
    int $interlocutor
) {
    $date = date('Y-m-d H:i:s');
    $message = validateInput($content);

    $query
        = sprintf("INSERT INTO message  (create_date, content, user_sender_id, user_receiver_id, viewed)
    VALUES ('$date','%s','$profileUser','$interlocutor',false)",
        mysqli_real_escape_string($mysql, $message));

    return mysqli_query($mysql, $query);
}
