<?php


/**
 * Функция для сохранения форм, если валидация провалилась
 * @param $name
 * @return mixed|string
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * прогон строки для всевозможной валидации
 * @param string $data строка которую прогоняем
 * @return string чистая строка
 */
function validate_input(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);

    return htmlspecialchars($data);
}

/**
 * Валидация на пустоту
 * @param string $key
 * @return string Если пустой-вернет текст с ошибкой валидации
 */
function validateFilled(string $key)
{
    if (empty($_POST[$key])) {
        return 'Это поле должно быть заполнено.';
    }
}

;


/**
 * Валидация на корректный url
 * @param string $val имя плейсхолдера из массива POST
 * @param array $error Массив с ошибками
 * @return string|void Возвращаем описание ошибки в массив с ошибками
 */
function validateURL(string $val)
{
    if (!filter_input(INPUT_POST, $val, FILTER_VALIDATE_URL)) {
        return 'Введите корректный url адрес';
    }
}

;
/**
 * Проверяем возможность загрузить картинку по ссылке
 * @param string $val имя плейсхолдера из массива POST
 * @param array $error Массив с ошибками
 * @return string|void Возвращаем описание ошибки в массив с ошибками
 */
function validateUpload(string $val)
{
    if (file_get_contents($_POST[$val]) == false or file_get_contents($_POST[$val]) == '') {
        return 'Не удалось загрузить изображение';
    }
}


/**
 * Проверяем на наличие # перед каждым хештегом
 * @param string $val имя плейсхолдера из массива POST
 * @param array $error Массив с ошибками
 * @return string|void Возвращаем описание ошибки в массив с ошибками
 */
function validateSharp(string $val)
{
    if (empty($_POST[$val]) == false) {
        $words = explode(' ', $_POST[$val]);
        foreach ($words as $w) {
            $hashtag = substr($w, 0, 1);
            if ($hashtag != '#') {
                return 'Хештег должен начинаться с символа # !';
            }
        }
    }
}

/**
 * Проверяем на наличие загруженной картинки, а так же валидируем ее
 * @param string $val Id в инпуте (имя массива $_FILES с картинкой)
 * @return string|void описание ошибки
 */
function validateImgFromUser(string $val)
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_name = $_FILES[$val]['tmp_name'];
    $file_type = finfo_file($finfo, $file_name);
    if ($file_type !== 'image/gif' && $file_type !== 'image/png' && $file_type !== 'image/jpeg') {
        return 'Загрузите картинку в формате png, jpeg, gif.';
    }
}

function validateYouTubeLink(string $val)
{
    $result = check_youtube_url($_POST[$val]);
    if ($result == false) {
        return 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
    }
}

function validateQuotelength(string $val)
{
    $length = iconv_strlen($_POST[$val]);
    if ($length > 70) {
        $error = 'Он не должна превышать 70 знаков.';

        return $error;
    }
}


/**
 * Итоговая валидация массива фото
 * @param array $array Валидируемый массив
 * @return array Массив с ошибками(если есть)
 */
function validatePhoto(array $array)
{
    $error = [];
    foreach ($array['photo'] as $key => $val) {
        if ($key == 'photo-heading') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key == 'photo-url') {
            if (empty($_FILES['userpic-file-photo']['name']) == false or $_FILES['userpic-file-photo']['size'] > 0) {
                $error[$key] = validateImgFromUser('userpic-file-photo');
                if (is_null($error[$key])) {
                    array_pop($error);
                }
            } else {
                $error[$key] = validateFilled($key);
                if (is_null($error[$key])) {
                    $error[$key] = validateURL($key);
                    if (is_null($error[$key])) {
                        $error[$key] = validateUpload($key);
                        if (is_null($error[$key])) {
                            array_pop($error);
                        }
                    }
                }
            }
        } elseif ($key == 'photo-tags') {
            $error[$key] = validateSharp($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        }
    }

    return $error;
}

/**
 * Итоговая валидация массива видео
 * @param array $array валидируемый массив
 * @return array массив с ошибками (Если есть)
 */
function validateVideo(array $array)
{
    $error = [];
    foreach ($array['video'] as $key => $val) {
        if ($key == 'video-heading') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key == 'video-url') {
            $error[$key] = validateFilled($key);
            $error[$key] = validateURL($key);
            if (is_null($error[$key])) {
                $error[$key] = validateYouTubeLink($key);
            }
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key == 'video-tags') {
            $error[$key] = validateSharp($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        }
    }

    return $error;
}

/**
 * Итоговая валидация массива Текст
 * @param array $array валидируемый массив
 * @return array массив с ошибками (Если есть)
 */
function validateText(array $array)
{
    $error = [];
    foreach ($array['text'] as $key => $val) {
        if ($key == 'text-heading') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key == 'text-content') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key == 'text-tags') {
            $error[$key] = validateSharp($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        }
    }

    return $error;
}

/**
 * Итоговая валидация массива цитата
 * @param array $array Валидируемый массив
 * @return array массив с ошибками (Если есть)
 */
function validateQuote(array $array)
{
    $error = [];
    foreach ($array['quote'] as $key => $val) {
        if ($key === 'quote-heading') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key === 'cite-text') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                $error[$key] = validateQuotelength($key);
            }
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key === 'quote-author') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key === 'cite-tags') {
            $error[$key] = validateSharp($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        }
    }

    return $error;
}

/**
 * Итоговая валидация массива ссылка
 * @param array $array Валидируемый массив
 * @return array массив с ошибками (Если есть)
 */
function validateLink(array $array)
{
    $error = [];
    foreach ($array['link'] as $key => $val) {
        if ($key === 'link-heading') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key === 'link-ref') {
            $error[$key] = validateFilled($key);
            if (is_null($error[$key])) {
                $error[$key] = validateURL($key);
            }
            if (is_null($error[$key])) {
                array_pop($error);
            }
        } elseif ($key === 'link-tags') {
            $error[$key] = validateSharp($key);
            if (is_null($error[$key])) {
                array_pop($error);
            }
        }
    }

    return $error;
}
/**
 * валидация для след. задания
 */
//
///**
// * Валидируем почту на корректность введенной почты
// * @param string $val Строка с почтой
// * @param array $error массив с ошабками
// */
//function validateEmail(string $val, array &$error)
//{
//    if (empty($error[$val]) == true) {
//        $result = filter_var($_POST[$val], FILTER_VALIDATE_EMAIL);
//        if ($result == false) {
//            $error[$val] = 'Введите корректную электронную почту';
//        }
//    }
//}
//
///**
// * валидируем почту на наличие дубликата
// * @param string $key ключ массива из пост
// * @param mysqli $mysql соединение с бд
// * @param array $error массив с ошибками
// */
//function validateDuplicate(string $key, mysqli $mysql, array &$error)
//{
//    if (empty($error[$key]) == true) {
//        $answer = searchDuplicate($mysql, $_POST[$key]);
//        if (is_null($answer[0]) == false) {
//            $error[$key] = 'эта почта уже используется';
//        }
//    }
//}
//
///**
// * Функция проверяет одинаковые ли пароли ввел пользователь при регистрации
// * @param string $key ключ массива из пост
// * @param array $error массив с ошабками
// */
//function passIsEqual(string $key, array &$error)
//{
//    if ((empty($error[$key]) == true) && (empty($error['password'] == true))) {
//        if ($_POST[$key] != $_POST['password']) {
//            $error[$key] = 'Введенные пароли не совпадают';
//        }
//    }
//}
//
///**
// * Функция проверяет сложность пароля и возвращает в массив с ошибками ошибки при создании пароля
// * @param string $key ключ из массива POST с паролем
// * @param array $errors Массив с ошибками
// */
//function validPass(string $key, array &$errors)
//{
//    if (empty($error[$key]) == true) {
//        $password = $_POST[$key];
//        $r1 = '/[A-Z]/';  //Uppercase
//        $r2 = '/[a-z]/';  //lowercase
//        $r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
//        $r4 = '/[0-9]/';  //numbers
//
//
//        if (preg_match_all($r1, $password, $o) < 1) {
//            $errors[$key] = 'В пароле должна быть хотя бы одна заглавная буква';
//        } elseif (preg_match_all($r2, $password, $o) < 1) {
//            $errors[$key] = 'В пароле должна быть хотя бы одна строчная буква';
//        } elseif (preg_match_all($r3, $password, $o) < 1) {
//            $errors[$key] = 'В пароле должен быть хотя бы один спец символ ?@# и т.д.';
//        } elseif (preg_match_all($r4, $password, $o) < 1) {
//            $errors[$key] = 'В пароле должна быть хотя бы одна цифра';
//        } elseif (strlen($password) < 8) {
//            $errors[$key] = 'Пароль должен содержать не менее 8 символов';
//        }
//    }
//}
//
///**
// * Валидируем загруженный аватар пользователя, что бы он был в нужном нам формате
// * @param string $val Ключ массива, в котором лежит имя файла
// * @param string $key Ключ массива, в который мы положим ошибку
// * @param array $error Массив с ошибками
// * @return string|void
// */
//function validateAvatarFromUser(string $val, string $key, array &$errors)
//{
//    $finfo = finfo_open(FILEINFO_MIME_TYPE);
//    $file_name = $_FILES[$val]['tmp_name'];
//    $file_type = finfo_file($finfo, $file_name);
//    if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
//        $errors[$key] = 'Загрузите картинку в формате png, jpeg';
//    }
//}
//
//function validateReg(array $array, array &$errors, mysqli $mysql)
//{
//    foreach ($array['registration'] as $key => $val) {
//        if ($key == 'email') {
//            validateFilled($key, $errors);
//            validateEmail($key, $errors);
//            validateDuplicate($key, $mysql, $errors);
//        } elseif ($key == 'login') {
//            validateFilled($key, $errors);
//        } elseif ($key == 'password') {
//            validateFilled($key, $errors);
//            validPass($key, $errors);
//        } elseif ($key == 'password-repeat') {
//            validateFilled($key, $errors);
//            passIsEqual($key, $errors);
//        } elseif (empty($_FILES['userpic-file']['name']) == false or $_FILES['userpic-file']['size'] > 0) {
//            validateAvatarFromUser('userpic-file', 'avatar', $errors);
//        }
//    }
//}
