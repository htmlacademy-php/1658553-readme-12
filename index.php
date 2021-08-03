<?php
$is_auth = rand(0, 1);
$user_name = 'Андрей';
$popularCarts = [
    [
        'heading' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'user-name' => 'Лариса',
        'avatar' => 'img/userpic-larisa-small.jpg'
    ],
    [
        'heading' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'user-name' => 'Владик',
        'avatar' => 'img/userpic.jpg'
    ],
    [
        'heading' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'img/rock-medium.jpg',
        'user-name' => 'Виктор',
        'avatar' => 'img/userpic-mark.jpg'
    ],
    [
        'heading' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'img/coast-medium.jpg',
        'user-name' => 'Лариса',
        'avatar' => 'img/userpic-larisa-small.jpg'
    ],
    [
        'heading' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'user-name' => 'Владик',
        'avatar' => 'img/userpic.jpg'
    ],
];
/**
 * Ограничение кол-ва символов в посте (не больше лимита)
 *
 * @param string $text Оригинальный текст
 * @param int $limit кол-во символов для ограничения
 * @param string $url ссылка (пока не используется)
 *
 * @return string возвращаем текст с кол-вом символов не больше лимита
 */
function cutText(string $text, int $limit = 300, string $url = '#'): string
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
        $link = '<a class="post-text__more-link" href="#">Читать далее</a>';
        //если слов меньше лимита
    } else {
        $result = $words;
    }
    return '<p>' . implode(' ', $result) . '</p>' . $link;
}

require_once ('helpers.php');
$page_content= include_template('main.php', ['popularCarts' => $popularCarts]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth ,
    'user_name' => $user_name ,
    'title' => 'readme: популярное'
]);
print($layout_content);
?>

