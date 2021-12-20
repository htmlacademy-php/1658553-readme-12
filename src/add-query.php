<?php

/**
 * Добавление заголовка в БД
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId В данной функции не используется
 * @return int|string Возвращаем последний ID записи, куда добавили заголовок
 */
function addHeading(mysqli $mysql, $key, $lastPostId, $authorId): int
{
    $date = date('Y-m-d H:i:s');
    $header = validateInput($_POST[$key]);
    $queryPost = "INSERT INTO post  (create_date,header,user_id) VALUES ('$date', '$header','$authorId')";
    mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);

    return $lastPostId;
}

/**
 * Функция добавления хештега(если он есть)
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи куда добавляем хештеги
 * @return int Возвращаем Id записи в которую добавили хештеги
 */
function addSharp(mysqli $mysql, $key, int $lastPostId): int
{
    if (!empty($_POST[$key])) {
        $hashtag = validateInput($_POST[$key]);
        $queryHashtag = "INSERT INTO hashtag (hashtag_name) VALUES ('$hashtag')";
        mysqli_query($mysql, $queryHashtag);
        $lastHashtagID = mysqli_insert_id($mysql);
        $hashtagPost = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
        mysqli_query($mysql, $hashtagPost);

        return $lastPostId;
    }

    return $lastPostId;
}

/**
 * Функция добавления фото
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи, в которую мы добавляем фото
 * @return int Возвращаем Id записи в которую добавили фото
 */
function addPhotoUrl(mysqli $mysql, $key, int $lastPostId): int
{
    $date = date('YmdHis');
    $contentType = 3;
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
        $media = validateInput($_POST['photo-url']);
    }
    $queryPost = "UPDATE post SET media = '$media', content_type_id = '$contentType' WHERE id = '$lastPostId'";
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления видео
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи, в которую мы добавляем видео
 * @return int Возвращаем Id записи в которую добавили видео
 */
function addVideoUrl(mysqli $mysql, $key, int $lastPostId): int
{
    $contentType = 4;
    $media = validateInput($_POST[$key]);
    $queryPost = "UPDATE post SET media = '$media',content_type_id = '$contentType' WHERE id = '$lastPostId'";
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления текста
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи, в которую мы добавляем текст
 * @return int Возвращаем Id записи в которую добавили текст
 */
function addText(mysqli $mysql, $key, int $lastPostId): int
{
    $contentType = 1;
    $content = validateInput($_POST[$key]);
    $queryPost = "UPDATE post SET text_content = '$content', content_type_id = '$contentType' WHERE id = '$lastPostId'";
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления цитаты
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи, в которую мы добавляем цитату
 * @return int Возвращаем Id записи в которую добавили цитату
 */
function addCite(mysqli $mysql, $key, int $lastPostId): int
{
    $contentType = 2;
    $content = validateInput($_POST[$key]);
    $queryPost = "UPDATE post SET text_content = '$content', content_type_id = '$contentType' WHERE id = '$lastPostId'";
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления автора цитаты
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи, в которую мы добавляем автора цитаты
 * @return int Возвращаем Id записи в которую добавили автора цитаты
 */
function addQuoteAuthor(mysqli $mysql, $key, int $lastPostId): int
{
    $content = validateInput($_POST[$key]);
    $queryPost = "UPDATE post SET author_copy_right = '$content' WHERE id = '$lastPostId'";
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}

/**
 * Функция добавления автора ссылки
 * @param mysqli $mysql Соединение с БД
 * @param int $lastPostId Id записи, в которую мы добавляем ссылку
 * @return int Возвращаем Id записи в которую добавили ссылку
 */
function addLink(mysqli $mysql, $key, int $lastPostId): int
{
    $contentType = 5;
    $content = validateInput($_POST[$key]);
    $queryPost = "UPDATE post SET media = '$content', content_type_id = '$contentType' WHERE id = '$lastPostId'";
    mysqli_query($mysql, $queryPost);

    return $lastPostId;
}


/**
 * Функция добавления почты пользователя при регистрации
 * @param mysqli $mysql Соединение с бд
 * @param $lastUserId id Записи регистрации(не используется тут)
 * @return int Возвращаем id записи при регистрации
 */
function addUserEmail(mysqli $mysql, $lastUserId): int
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
 * @param mysqli $mysql Соединение с бд
 * @param int $lastUserId Id записи регистрации
 * @return int  Возвращаем id записи при регистрации
 */
function addUserLogin(mysqli $mysql, int $lastUserId): int
{
    $login = validateInput($_POST['login']);
    $queryPost = "UPDATE user SET login = '$login' WHERE id = '$lastUserId'";
    mysqli_query($mysql, $queryPost);

    return $lastUserId;
}

/**
 * Функция добавления пароля пользователя при регистрации
 * @param mysqli $mysql Соединение с бд
 * @param int $lastUserId Id записи регистрации
 * @return int Возвращаем id записи при регистрации
 */
function addUserPass(mysqli $mysql, int $lastUserId): int
{
    $password = validateInput($_POST['password']);
    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    $queryPost = "UPDATE user SET password = '$hashPass' WHERE id = '$lastUserId'";
    mysqli_query($mysql, $queryPost);

    return $lastUserId;
}

/**
 * Функция добавления аватара пользователя при регистрации
 * @param mysqli $mysql Соединение с бд
 * @param int $lastUserId Id записи регистрации
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
        $queryPost = "UPDATE user SET avatar = '$avatar' WHERE id = '$lastUserId'";
        mysqli_query($mysql, $queryPost);
    } else {
        $queryPost = "UPDATE user SET avatar = 'img/Anon.jpg' WHERE id = '$lastUserId'";
        mysqli_query($mysql, $queryPost);
    }


    return $lastUserId;
}

/**
 * добавляет кол-во просмотров при посещении страницы поста
 * @param mysqli $mysql соединение с бд
 * @param string $thisPostId посещаемый пост
 * @param string $views кол-во просмотров до посещения
 */
function addView(mysqli $mysql, int $thisPostId, ?int $views)
{
    $queryPost = "UPDATE post SET views_number = $views+1 WHERE id = '$thisPostId'";
    mysqli_query($mysql, $queryPost);
}


/**
 * добавляет лайк к посту
 * @param mysqli $mysql соединение с бд
 * @param int $thisPostId лайкаемый пост
 * @param int $userId пользователь который лайкнул
 */
function addLike(mysqli $mysql, int $thisPostId, int $userId)
{
    $queryPost = "INSERT INTO like_count (user_id, post_id) VALUES ('$userId','$thisPostId')";
    mysqli_query($mysql, $queryPost);
}

/**
 * снимает лайк с поста
 * @param mysqli $mysql соединение с бд
 * @param int $thisPostId ид поста
 * @param int $userId ид пользователя
 */
function removeLike(mysqli $mysql, int $thisPostId, int $userId)
{
    $queryPost = "DELETE FROM like_count WHERE user_id = $userId AND post_id = $thisPostId";
    mysqli_query($mysql, $queryPost);
}

/**
 * Добавляет подписку на пользователя
 * @param mysqli $mysql соединение с бд
 * @param int $authorId
 * @param int $userId пользователь который лайкнул
 */
function addSubscribe(mysqli $mysql, int $authorId, int $userId)
{
    $queryPost = "INSERT INTO subscribe (user_subscribe_id, user_author_id) VALUES ('$userId','$authorId')";
    mysqli_query($mysql, $queryPost);
}

/**
 * снимает подписку на пользователя
 * @param mysqli $mysql соединение с бд
 * @param int $authorId id автора
 * @param int $userId ид пользователя
 */
function removeSubscribe(mysqli $mysql, int $authorId, int $userId)
{
    $queryPost = "DELETE FROM subscribe WHERE user_subscribe_id = $userId AND user_author_id = $authorId";
    mysqli_query($mysql, $queryPost);
}

/**
 * Добавление комментария в бд
 * @param mysqli $mysql Соединение с БД
 * @param string $key ключ массива $_POST
 * @param int $postId номер поста
 * @param int $userId автор комментария
 * @return bool если успешно возвращаем true
 */
function addComment(mysqli $mysql, string $key, int $postId, int $userId): bool
{
    $date = date('Y-m-d H:i:s');
    $comment = validateInput($_POST[$key]);
    $cleanComment = trim($comment);
    $queryComment = "INSERT INTO comment  (create_date,content,user_id,post_id) VALUES ('$date', '$cleanComment','$userId','$postId')";
    mysqli_query($mysql, $queryComment);
    $lastPostId = mysqli_insert_id($mysql);

    return $lastPostId;
//    if (is_int($lastPostId)){
//        return true;
//    } else{
//        return false;
//    }

}
