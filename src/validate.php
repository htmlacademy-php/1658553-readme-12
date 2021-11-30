<?php


/**
 * Функция для сохранения формы если форма не прошла валидацию
 * @param string $key Ключ массива пост, в котором валидация провалилась
 * @return mixed|string Значения, которые были введены до провалившейся валидации
 */
function getPostVal(string $key)
{
    return $_POST[$key] ?? "";
}

/**
 * Прогон строки для всевозможной валидации перед добавлением в БД
 * @param string $string Строка для валидации
 * @return string Отвалидированная строка
 */
function validateInput(string $string): string
{
    $string = trim($string);

    return stripslashes($string);
}


/**
 * Функция валидации поля на пустоту
 * @param string $key Ключ из массива $_POST
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateFilled(string $key)
{
    if (empty($_POST[$key])) {
        return 'Это поле должно быть заполнено.';
    }

    return true;
}


/**
 * Функция валидации URL адреса
 * @param string $val Адрес который мы получили из $_POST
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateURL(string $val)
{
    if (!filter_input(INPUT_POST, $val, FILTER_VALIDATE_URL)) {
        return 'Введите корректный url адрес';
    }

    return true;
}

;
/**
 * Проверяем возможность загрузки изображения по ссылке
 * @param string $key Ссылка на изображение из $_POST
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateUpload(string $key)
{
    if (!file_get_contents($_POST[$key]) or file_get_contents($_POST[$key]) == '') {
        return 'Не удалось загрузить изображение';
    }

    return true;
}


/**
 * Проверка на наличие # перед каждым хештегом
 * @param string $key Хештег, который мы получили из массива $_POST
 * @return bool|string  Возвращает true если валидация успешна либо текст ошибки.
 */
function validateSharp(string $key)
{
    if (!empty($_POST[$key])) {
        $words = explode(' ', $_POST[$key]);
        foreach ($words as $w) {
            $hashtag = substr($w, 0, 1);
            if ($hashtag != '#') {
                return 'Хештег должен начинаться с символа # !';
            }

            return true;
        }
    }

    return true;
}

/**
 * Валидация загруженного пользователем изображения
 * @param string $key Имя массива в котором хранится изображение
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateImgFromUser(string $key)
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileName = $_FILES[$key]['tmp_name'];
    $fileType = finfo_file($finfo, $fileName);
    if ($fileType !== 'image/gif' && $fileType !== 'image/png' && $fileType !== 'image/jpeg') {
        return 'Загрузите картинку в формате png, jpeg, gif.';
    }

    return true;
}

/**
 * Валидация на корректную ссылку youtube
 * @param string $key Ссылка youtube из массива $_POST
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateYouTubeLink(string $key)
{
    $result = checkYoutubeUrl($_POST[$key]);
    if (is_string($result)) {
        return $result;
    }

    return true;
}

/**
 * Валидация на длину вводимой пользователем цитаты
 * @param string $val Цитата из массива $_POST
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateQuoteLength(string $val)
{
    $length = iconv_strlen($_POST[$val]);
    if ($length > 70) {
        $error = 'Он не должна превышать 70 знаков.';

        return $error;
    }

    return true;
}

/**
 * Общая валидация изображения
 * @param string $key Ключ массива $_POST который мы передаем в функции
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateImg(string $key)
{
    if (existAddFiles('userpic-file-photo')) {
        $error = validateImgFromUser('userpic-file-photo');
    } else {
        $error = validateFilled($key);
        if (is_bool($error)) {
            $error = validateURL($key);
            if (is_bool($error)) {
                $error = validateUpload($key);
            }
        }
    }

    return $error;
}

/**
 * Общая валидация видео
 * @param string $key Ключ массива $_POST который мы передаем в функции
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateVideoUrl(string $key)
{
    $error = validateFilled($key);
    if (is_bool($error)) {
        $error = validateURL($key);
        if (is_bool($error)) {
            $error = validateYouTubeLink($key);
        }
    }

    return $error;
}

/**
 * Общая валидация цитаты
 * @param string $key Ключ массива $_POST который мы передаем в функции
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateCite(string $key)
{
    $error = validateFilled($key);
    if (is_bool($error)) {
        $error = validateQuotelength($key);
    }

    return $error;
}

/**
 * общая валидация ссылки
 * @param string $key Ключ массива $_POST который мы передаем в функции
 * @return bool|string Возвращает true если валидация успешна либо текст ошибки.
 */
function validateLink(string $key)
{
    $error = validateFilled($key);
    if (is_bool($error)) {
        $error = validateURL($key);
    }

    return $error;
}

/**
 * валидация для след. задания
 */

/**
 * Валидация почты на корректность
 * @param string $key Ключ массива $_POST
 * @return bool|string Возвращает текст ошибки или true если валидация прошла успешно
 */
function isEmail(string $key)
{
    $result = filter_var($_POST[$key], FILTER_VALIDATE_EMAIL);
    if ($result == false) {
        return 'Введите корректную электронную почту';
    }

    return true;
}

/**
 * Валидируем почту на наличие дубликата
 * @param string $key Ключ массива $_POST
 * @param mysqli $mysql Соединение с бд
 * @return bool|string Возвращает текст ошибки либо true если валидация успешна
 */
function validateDuplicate(string $key, mysqli $mysql)
{
    $answer = searchDuplicate($mysql, $_POST[$key]);
    if (!empty($answer)) {
        return 'эта почта уже используется';
    }

    return true;
}

/**
 * Валидиурем, одинаковые ли пароли ввел пользователь при регистрации
 * @param string $key Ключ массива $_POST с повторным паролем
 * @return bool|string Возвращает текст ошибки или true если валидация успешна
 */
function passIsEqual(string $key)
{
    if ($_POST[$key] != $_POST['password']) {
        return 'Введенные пароли не совпадают';
    }

    return true;
}

/**
 * Функция проверяет сложность пароля и возвращает в массив с ошибками ошибки при создании пароля
 * @param string $key Ключ массива с паролем
 * @return array|bool Возвращает массив с ошибками или true если валидация успешна
 */

function validPass(string $key)
{
    $password = $_POST[$key];
    $r1 = '/[A-Z]/';  //Uppercase
    $r2 = '/[a-z]/';  //lowercase
    $r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
    $r4 = '/[0-9]/';  //numbers
    $fields = [
        'В пароле должна быть хотя бы одна заглавная буква' => function () use ($r1, $password) {
            return (preg_match_all($r1, $password, $o) < 1);
        },
        'В пароле должна быть хотя бы одна строчная буква' => function () use ($r2, $password) {
            return (preg_match_all($r2, $password, $o) < 1);
        },
        'В пароле должен быть хотя бы один спец символ ?@# и т.д.' => function () use ($r3, $password) {
            return (preg_match_all($r3, $password, $o) < 1);
        },
        'В пароле должна быть хотя бы одна цифра' => function () use ($r4, $password) {
            return (preg_match_all($r4, $password, $o) < 1);
        },
        'Пароль должен содержать не менее 8 символов' => function () use ($password) {
            return (strlen($password) < 8);
        },
    ];

    $errors = [];
    foreach ($fields as $key => $value) {
        $rule = $fields[$key];
        if ($rule()) {
            $errors[$key] = $key;
        }
    }

    if (!empty($errors)) {
        return $errors;
    }

    return true;
}

/**
 * Валидируем загруженный аватар пользователя, что бы он был в правильном формате
 * @return bool|string Возвращает текст ошибки или true если валидация успешна
 */
function validateAvatarFromUser()
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileName = $_FILES['userpic-file']['tmp_name'];
    $fileType = finfo_file($finfo, $fileName);
    if ($fileType !== 'image/png' && $fileType !== 'image/jpeg') {
        return 'Загрузите картинку в формате png, jpeg';
    }

    return true;
}

/**
 * Функция валидации почты
 * @param string $key Ключ из массива $_POST
 * @param mysqli $mysql Соединение с бд
 * @return bool|string Возвращает массив с ошибками
 */
function validateEmail(string $key, mysqli $mysql)
{
    $error = validateFilled($key);
    if (is_bool($error)) {
        $error = isEmail($key);
        if (is_bool($error)) {
            $error = validateDuplicate($key, $mysql);
        }
    }

    return $error;
}

/**
 * Валидация пароля
 * @param string $key Ключ массива $_POST
 * @return array|bool|string Возвращаем строку, если поле не заполнено, массив если пароль не достаточно сложный либо true если валидация успешна
 */
function validatePassword(string $key)
{
    $error = validateFilled($key);
    if (is_bool($error)) {
        $error = validPass($key);
    }

    return $error;
}

/**
 * Валидация повтора пароля
 * @param string $key ключ из массива $_POST
 * @return bool|string возвращаем текст ошибки либо true если валидация успешна
 */
function validatePasswordRepeat(string $key)
{
    $error = validateFilled($key);
    if (is_bool($error)) {
        $error = passIsEqual($key);
    }

    return $error;
}
