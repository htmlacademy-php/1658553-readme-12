<?php

/**
 *  Добавление в бд публикации типа 'фото'
 * @param array $arr Массив из $_POST
 * @param mysqli $mysql Соединение с бд
 * @return int id поста
 */
function addPostPhoto(array $arr, mysqli $mysql): int
{
    $date = date('Y-m-d H:i:s');
    $header = validate_input($arr['photo']['photo-heading']);
    $hashtag = validate_input($arr['photo']['photo-tags']);
    $authorID = '1';
    if (empty($_FILES['userpic-file-photo']['name']) == false or $_FILES['userpic-file-photo']['size'] > 0) {
        $uploads_dir = 'content';
        if ($_FILES["userpic-file-photo"]['error'] == 0) {
            $tmp_name = $_FILES["userpic-file-photo"]["tmp_name"];
            $name = basename($_FILES["userpic-file-photo"]["name"]);
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
            $media = validate_input('content/'.$name);
        }
    } else {
        $media = validate_input($arr['photo']['photo-url']);
    }
    $queryPost = "INSERT INTO post (create_date,header,media,user_id,content_type_id) VALUES ('$date', '$header', '$media','$authorID','3')";
    mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);
    $queryHashtag = "INSERT INTO hashtag (hashtag_name) VALUES ('$hashtag')";
    mysqli_query($mysql, $queryHashtag);
    $lastHashtagID = mysqli_insert_id($mysql);
    $hashtagPost = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
    mysqli_query($mysql, $hashtagPost);

    return $lastPostId;
}

/**
 *  Добавление в бд публикации типа 'Видео'
 * @param array $arr Массив из $_POST
 * @param mysqli $mysql Соединение с бд
 * @return int id поста
 */
function addPostVideo(array $arr, mysqli $mysql): int
{
    $date = date('Y-m-d H:i:s');
    $header = validate_input($arr['video']['video-heading']);
    $media = validate_input($arr['video']['video-url']);
    $hashtag = validate_input($arr['video']['video-tags']);
    $authorID = '1';
    $queryPost = "INSERT INTO post (create_date,header,media,user_id,content_type_id) VALUES ('$date', '$header', '$media','$authorID','4')";
    $successPost = mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);
    $queryHashtag = "INSERT INTO hashtag (hashtag_name) VALUES ('$hashtag')";
    $successHashtag = mysqli_query($mysql, $queryHashtag);
    $lastHashtagID = mysqli_insert_id($mysql);
    $hashtagPost = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
    $successHashtagPost = mysqli_query($mysql, $hashtagPost);

    return $lastPostId;
}

/**
 *  Добавление в бд публикации типа 'Текст'
 * @param array $arr Массив из $_POST
 * @param mysqli $mysql Соединение с бд
 * @return int id поста
 */
function addPostText(array $arr, mysqli $mysql): int
{
    $date = date('Y-m-d H:i:s');
    $header = validate_input($arr['text']['text-heading']);
    $content = validate_input($arr['text']['text-content']);
    $hashtag = validate_input($arr['text']['text-tags']);
    $authorID = '1';
    $queryPost = "INSERT INTO post (create_date,header,text_content,user_id,content_type_id) VALUES ('$date', '$header', '$content','$authorID','1')";
    $successPost = mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);
    $queryHashtag = "INSERT INTO hashtag (hashtag_name) VALUES ('$hashtag')";
    $successHashtag = mysqli_query($mysql, $queryHashtag);
    $lastHashtagID = mysqli_insert_id($mysql);
    $hashtagPost = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
    $successHashtagPost = mysqli_query($mysql, $hashtagPost);

    return $lastPostId;
}

/**
 *  Добавление в бд публикации типа 'Цитата'
 * @param array $arr Массив из $_POST
 * @param mysqli $mysql Соединение с бд
 * @return int id поста
 */
function addPostQuote(array $arr, mysqli $mysql): int
{
    $date = date('Y-m-d H:i:s');
    $header = validate_input($arr['quote']['quote-heading']);
    $content = validate_input($arr['quote']['cite-text']);
    $quoteAuthor = validate_input($arr['quote']['quote-author']);
    $hashtag = validate_input($arr['quote']['cite-tags']);
    $authorID = '1';
    $queryPost = "INSERT INTO post (create_date,header,text_content,author_copy_right,user_id,content_type_id) VALUES ('$date', '$header', '$content','$quoteAuthor','$authorID','2')";
    $successPost = mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);
    $queryHashtag = "INSERT INTO hashtag (hashtag_name) VALUES ('$hashtag')";
    $successHashtag = mysqli_query($mysql, $queryHashtag);
    $lastHashtagID = mysqli_insert_id($mysql);
    $hashtagPost = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
    $successHashtagPost = mysqli_query($mysql, $hashtagPost);

    return $lastPostId;
}

/**
 *  Добавление в бд публикации типа 'Ссылка'
 * @param array $arr Массив из $_POST
 * @param mysqli $mysql Соединение с бд
 * @return int id поста
 */
function addPostLink(array $arr, mysqli $mysql): int
{
    $date = date('Y-m-d H:i:s');
    $header = validate_input($arr['link']['link-heading']);
    $media = validate_input($arr['link']['link-ref']);
    $hashtag = validate_input($arr['link']['link-tags']);
    $authorID = '1';
    $queryPost = "INSERT INTO post (create_date,header,media,user_id,content_type_id) VALUES ('$date', '$header', '$media','$authorID','5')";
    $successPost = mysqli_query($mysql, $queryPost);
    $lastPostId = mysqli_insert_id($mysql);
    $queryHashtag = "INSERT INTO hashtag (hashtag_name) VALUES ('$hashtag')";
    $successHashtag = mysqli_query($mysql, $queryHashtag);
    $lastHashtagID = mysqli_insert_id($mysql);
    $hashtagPost = "INSERT INTO hashtag_post (hashtag,post) VALUES ('$lastHashtagID','$lastPostId')";
    $successHashtagPost = mysqli_query($mysql, $hashtagPost);

    return $lastPostId;
}

/**
 * Функция добавления контента в бд
 * @param array $errors Массив с ошибками
 * @param array $content Контент
 * @param object $mysql Соединение с бд
 * @param string $contentType Тип контента для добавления
 */
function addPost( array $errors, array $content, object $mysql, string $contentType)
{
    if (empty($errors)) {
        switch ($contentType) {
            case 'text':
                $lastPostId = addPosttext($content, $mysql);
                header("Location: post.php?post-id=$lastPostId");
                break;
            case 'quote':
                $lastPostId = addPostQuote($content, $mysql);
                header("Location: post.php?post-id=$lastPostId");
                break;
            case 'photo':
                $lastPostId = addPostPhoto($content, $mysql);
                header("Location: post.php?post-id=$lastPostId");
                break;
            case 'video':
                $lastPostId = addPostVideo($content, $mysql);
                header("Location: post.php?post-id=$lastPostId");
                break;
            case 'link':
                $lastPostId = addPostLink($content, $mysql);
                header("Location: post.php?post-id=$lastPostId");
                break;
        }
    }
}


/**
 * В дальнейшем будет регистрация
 */
//function addUserReg(array $arr, mysqli $mysql, array &$errors, &$lastUserID)
//{
//    $date = date('Y-m-d H:i:s');
//    $email = validate_input($arr['registration']['email']);
//    $login = validate_input($arr['registration']['login']);
//    $pass = validate_input($arr['registration']['password']);
//    $hashPass = password_hash($pass, PASSWORD_DEFAULT);
//    if (empty($_FILES['userpic-file']['name']) == false or $_FILES['userpic-file']['size'] > 0) {
//        $uploads_dir = 'D:\PHP\server\domains\localhost\8\content';
//        if ($_FILES["userpic-file"]['error'] == 0) {
//            $tmp_name = $_FILES["userpic-file"]["tmp_name"];
//            $name = basename($_FILES["userpic-file"]["name"]);
//            move_uploaded_file($tmp_name, "$uploads_dir/$name");
//            $avatar = validate_input('content/'.$name);
//        }
//    }
//    $query = "INSERT INTO user (reg_date, email, login, password, avatar) VALUES ('$date','$email','$login','$hashPass','$avatar')";
//    $successUserAdd = mysqli_query($mysql, $query);
//    $lastUserID = mysqli_insert_id($mysql);
//    if ($successUserAdd == false) {
//        $errors['Server_error'] = 'ошибка сервера, повторите попытку';
//    }
//}
