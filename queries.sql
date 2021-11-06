USE readme;
/*
 Список типов контента для поста
 */
INSERT INTO `content_type` (type_name, icon_name) VALUES ('text', 'post-text'), ('quote', 'post-quote'), ('photo', 'post-photo'), ('video', 'post-video'), ('link', 'post-link');
/*
 Пара пользователей
 */
INSERT INTO `user` (reg_date, email, login, password, name , avatar) VALUES ('2015.05.05', 'firstemail@mail.ru', 'Вася', 'qwerty', 'Вася', 'img/some-man.jpg'), ('2015.05.06', 'secondemail@mail.ru', 'Петр', 'somehardpass', 'Петр', 'img/some-old-man.jpg'), ('2015.05.05', 'larisa@mail.ru', 'Лариса', 'qwerty', 'Лариса', 'img/userpic-larisa-small.jpg'), ('2015.05.05', 'Vladikemail@mail.ru', 'Владик', 'qwerty', 'Владик', 'img/userpic.jpg'), ('2015.05.05', 'Victoremail@mail.ru', 'Виктор', 'qwerty', 'Виктор', 'img/userpic-mark.jpg');
/*
 Все посты
 */
INSERT INTO `post` (create_date, header, text_content, author_copy_right, media, views_number, user_id, content_type_id) VALUES ('2020.08.23', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих','Сергей Есенин','somelink.com', '5','3', '2'), ('2020.07.23', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', '', '', '10', '4', '1'), ('2020.07.28', 'Наконец, обработал фотки!', '','','img/rock-medium.jpg', '15','5','3'), ('2020.08.20', 'Моя мечта', '','','img/coast-medium.jpg', '25','3','3'), ('2020.08.23', 'Лучшие курсы', 'Лучшие курсы','','www.htmlacademy.ru','25','3','2');
/*
 Пара комментариев
 */
INSERT INTO `comment` (create_date, content, user_id, post_id) VALUES ('2020.08.23', 'Первый!', '1', '2'), ('2020.08.23', 'Как же скучно', '2', '1');
/*
 получаем список постов с сортировкой по популярности и вместе с именами авторов и типом контента
 */
SELECT `views_number`, `name`,`content_type_id` FROM `post` LEFT JOIN `user` ON post.user_id = user.id ORDER BY `views_number` DESC;
/*
 получить список постов для конкретного пользователя
 */
SELECT `id` FROM `post`  WHERE `user_id` = 3;
/*
 получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
 */
SELECT `login`, `content` FROM `comment` INNER JOIN `user` ON comment.user_id = user.id WHERE post_id = 2;
/*
 добавить лайк к посту;
 */
INSERT INTO `like_count`  (user_id, post_id) VALUES ('1', '2');
/*
 подписаться на пользователя
 */
INSERT INTO `subscribe` (user_subscribe_id, user_author_id) VALUES ('1', '3');
/*
 Добавлю пару хештегов
 */
INSERT INTO `hashtag` (hashtag_name) VALUES ('#Первый'), ('#Красиво'), ('#Лучший'), ('#php'), ('#Пыха'), ('#photo');
INSERT INTO `hashtag_post` (hashtag, post) VALUES ('1','5'), ('4','5'), ('5','5'), ('2','3'),('6','3'), ('1','1');
INSERT INTO `comment` (create_date, content, user_id, post_id) VALUES ('2020.08.24', 'второй!', '5', '2'), ('2020.08.25', 'третий!', '2', '1'),('2020.08.26', 'четвертый!!', '3', '2'), ('2020.08.25', 'пятый!!', '4', '2');
INSERT INTO `comment` (create_date, content, user_id, post_id) VALUES ('2020.08.24', 'еще один!', '5', '2');
INSERT INTO `like_count`  (user_id, post_id) VALUES ('2', '2'), ('3', '3'), ('4', '3'), ('2','1');




