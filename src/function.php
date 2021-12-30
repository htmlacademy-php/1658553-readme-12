<?php

/**
 * Ограничение кол-ва символов в посте (не больше лимита)
 *
 * @param string $text Оригинальный текст
 * @param int $limit кол-во символов для ограничения
 * @param string $url ссылка (пока не используется)
 *
 * @return string возвращаем текст с кол-вом символов не больше лимита
 */
function cutText(string $text, string $url, int $limit = 300): string
{
// превращаем исходный текст в массив слов
    $words = explode(' ', $text);
// результат работы выводим в новый массив
    $result = [];
//изначально ссылка будет пустой
    $link = '';
// если длина текста больше лимита
    if (mb_strlen($text) > $limit) {
        $symbols = 0;
        foreach ($words as $word) {
//подсчитываем сколько слов в каждом элементе массива + пробел
            $symbols += mb_strlen($word) + 1;
//многоточие тоже символ, берем расчет и на них
            if ($symbols + 3 < $limit) {
                $result[] = $word;
            } else {
                break;
            }
        }
//добавляем многоточия в массив
        $ellipsis = ('...');
        $result[] = $ellipsis;
//добавляем ссылку
        $link = '<a class="post-text__more-link"  href="post.php?post-id='.$url.'">Читать далее</a>';
//если слов меньше лимита
    } else {
        $result = $words;
    }

    return '<p>'.implode(' ', $result).'</p>'.$link;
}

/**
 * отображение даты на странице
 *
 * @param string $date дата из бд
 * @return string дата в формате d-m-Y H:i
 * @throws Exception преобразование строки в дататайм
 *
 */
function cutDate($date)
{
    $postDate = new DateTimeImmutable($date);
    $cutDate = $postDate->format('d-m-Y H:i');

    return $cutDate;
}

/**
 * отображение даты на странице
 *
 * @param string $date дата из бд
 * @return string дата в формате d-m-Y H:i:s
 * @throws Exception преобразование строки в дататайм
 *
 */
function fullDate($date)
{
    $postDate = new DateTimeImmutable($date);
    $fullDate = $postDate->format('d-m-Y H:i:s');

    return $fullDate;
}

/**
 * отображение даты на странице
 *
 * @param string $date дата из бд
 * @return string Дата в формате был * назад
 * @throws Exception преобразование строки в дататайм
 *
 */
function smallDate($date, $ending)
{
    $postDate = new DateTimeImmutable($date);
//вычисляем разницу между серверным временем и датой поста
    $nowDate = new DateTimeImmutable();
    $difference = $nowDate->diff($postDate);

    if ($difference->m > 0) {
        $resultForPost = getNounPluralForm($difference->m, 'месяц', 'месяца', 'месяцев');
        $smallDate = "$difference->m $resultForPost $ending";
    } elseif (intdiv($difference->d, 7) > 0) {
        $weeks = intdiv($difference->d, 7);
        $resultForPost = getNounPluralForm($weeks, 'неделя', 'недели', 'недель');
        $smallDate = "$weeks $resultForPost $ending";
    } elseif ($difference->d > 0) {
        $resultForPost = getNounPluralForm($difference->d, 'день', 'дня', 'дней');
        $smallDate = "$difference->d $resultForPost $ending";
    } elseif ($difference->h > 0) {
        $resultForPost = getNounPluralForm($difference->h, 'час', 'часа', 'часов');
        $smallDate = "$difference->h $resultForPost $ending";
    } else {
        $resultForPost = getNounPluralForm($difference->i, 'минута', 'минуты', 'минут');
        $smallDate = "$difference->i $resultForPost $ending";
    }

    return $smallDate;
}


/**
 * Из-за джойнов селектов, теперь там где в бд 0 возвращается null из-за чего летит верстка.
 * Этой функцией мы фиксим подобное поведение
 * @param string|null $string значение где может быть null
 * @return int возвращаем строго число, если null то возвращается 0
 */
function zeroForPostInfo(?string $string): int
{
    if (is_null($string)) {
        $string = 0;
    }

    return $string;
}

/**
 * Функция для поиска ошибок в массиве $errors
 * @param array $errors Массив который мы валидируем
 * @return bool Возвращает true если ошибки есть и false если нет
 */
function findErrors(array $errors): bool
{
    foreach ($errors as $key => $val) {
        if (is_bool($val)) {
            $answer = false;
        } else {
            $answer = true;
            break;
        }
    }

    return $answer;
}

;
/**
 * Поиск дубликата почты в бд
 * @param mysqli $mysql соединение с бд
 * @param string $email почта
 * @return array Массив с даннымы, если такого mail нет в базе, то будет пустой массив
 */
function searchDuplicate(mysqli $mysql, string $email)
{
    $data[] = $email;
    $query = "
SELECT * FROM user
WHERE email = ?
";
    $postListPrepare = dbGetPrepareStmt(
        $mysql,
        $query,
        $data
    );
    $postListPrepareRes = mysqli_stmt_get_result($postListPrepare);

    return mysqli_fetch_array($postListPrepareRes, MYSQLI_ASSOC);
}

/**
 * функция  вывода ошибки валидации для пароля (может быть массив/строка)
 * @param $arr Массив из $errors
 */
function outputArrOrString($arr)
{
    if (is_string($arr)) {
        print $arr;
    } else {
        foreach ($arr as $key) {
            print $key;
        }
    }
}

/**
 * Проверяем отправлял ли пользователь файл через форму
 * @param $input string Ключ из массива $_FILES в инпут форме
 * @return bool Возвращаем true если отправлял и false если нет
 */
function existAddFiles($input): bool
{
    if (!empty($_FILES[$input]['name']) or $_FILES[$input]['size'] > 0) {
        return true;
    }

    return false;
}

/**
 * Определяем класс html отображения основного контента (feed or popular) для разметки лейаута
 * @return string Класс для html разметки
 */
function layoutContentDefine()
{
    $content = strrchr($_SERVER['PHP_SELF'], '/');
    if ($content === '/popular.php') {
        return 'page__main--popular';
    } elseif ($content === '/feed.php') {
        return 'page__main--feed';
    } elseif ($content === '/search.php') {
        return 'page__main--search-results';
    } elseif ($content === '/post.php') {
        return 'page__main--publication';
    } elseif ($content === '/add.php') {
        return 'page__main--adding-post';
    } elseif ($content === '/profile.php') {
        return 'page__main--profile';
    }
}


/**
 * Перемещает из созданной по скрипту папки файл в папку контент, если валидация успешана и удаляет папку
 */
function rebaseImg()
{
    $oldfolder = 'valid';
    $newfolder = 'content';

    $files = glob($oldfolder.'/*');
    if ($_FILES) {
        foreach ($files as $file) {
            $filename = basename($file);
            copy($file, $newfolder.'/'.$filename);
            unlink($file);
        }
        rmdir($oldfolder);
    }
}

/**
 * Удаляет файл и папку созданные по скрипту если валидация провалилась
 */
function deleteImg()
{
    $oldfolder = 'valid';
    $files = glob($oldfolder.'/*');
    if ($_FILES) {
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($oldfolder);
    }
}

/**
 * Определяем название блока для подключения исходя из номера типа контента
 * @param int|null $type Тип контента который отображается сейчас
 * @param array $types Все типы контента
 * @return string Название контента
 */
function getBlockName(?int $type, array $types)
{
    foreach ($types as $key => $contentTypes) {
        if ($contentTypes['id'] === $type) {
            return 'block-'.$contentTypes['type_name'].'.php';
        }
    }

    return 'block-photo.php';
}

/**
 * Определяем название класса для верстки исходя из типа контента
 * @param int|null $type Тип контента который отображается сейчас
 * @param array $types Все типы контента
 * @return string Имя класса для верстки
 */
function getClassNameAddForm(?int $type, array $types)
{
    foreach ($types as $key => $contentTypes) {
        if ($contentTypes['id'] === $type) {
            return 'adding-post__'.$contentTypes['type_name'].' tabs__content--active';
        }
    }

    return 'adding-post__photo tabs__content--active';
}






