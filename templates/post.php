<?php
/* @var array $post_content */
/* @var array $author_info */
/* @var array $like_count */
/* @var array $comments_views_count */
/* @var array $authorPosts_count */
/* @var array $hashtags */
/* @var array $comment_list */
/* @var array $comment_count */
/* @var array $comment_all_list */
foreach ($post_content

as  $val):
?>


<div class="container">
    <h1 class="page__title page__title--publication"><?= $post_content['header']; ?></h1>
    <section class="post-details">
        <h2 class="visually-hidden">Публикация</h2>
        <div class="post-details__wrapper post-photo">
            <div class="post-details__main-block post post--details">


                <?php
                if ($val['icon_name'] == "post-photo"): ?>


                    <!-- пост-изображение -->
                    <div class="post-details__image-wrapper post-photo__image-wrapper">
                        <img src="<?= $val['media']; ?>" alt="Фото от пользователя" width="760" height="507">
                    </div>


                <?php
                elseif ($val['icon_name'] == "post-quote"): ?>


                    <!-- пост-цитата -->
                    <div class="post-details__image-wrapper post-quote">
                        <div class="post__main">
                            <blockquote>
                                <p>
                                    <?= $val['text']; ?>
                                </p>
                                <cite><?= $val['author_copy_right']; ?></cite>
                            </blockquote>
                        </div>
                    </div>


                <?php
                elseif ($val['icon_name'] == "post-text"): ?>

                    <!-- пост-текст -->
                    <div class="post-details__image-wrapper post-text">
                        <div class="post__main">
                            <p>
                                <?= $val['text']; ?>
                            </p>
                        </div>
                    </div>


                <?php
                elseif ($val['icon_name'] == "post-link"): ?>


                    <div class="post__main">
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="http://<?= $val['media'] ?>" title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=<?= $val['text'] ?>"
                                             alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?= $val['header'] ?></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                <?php
                elseif ($val['icon_name'] == "post-video"): ?>

                    <!-- пост-видео -->
                    <div class="post-details__image-wrapper post-photo__image-wrapper">
                        <?= embed_youtube_cover($val['media']) ?>
                    </div>

                <?php
                endif ?>
                <?php
                endforeach; ?>

                <div class="post__indicators">
                    <div class="post__buttons">
                        <?php
                        foreach ($like_count as $key => $val): ?>
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                     height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>

                                <span><?= $val['like-count'] ?></span>

                                <span class="visually-hidden">количество лайков</span>
                            </a>
                        <?php
                        endforeach; ?>
                        <?php
                        foreach ($comments_views_count

                        as $key => $val): ?>
                        <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                            <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-comment"></use>
                            </svg>
                            <span><?= $val ['comment-count'] ?></span>
                            <span class="visually-hidden">количество комментариев</span>
                        </a>

                        <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                            <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-repost"></use>
                            </svg>
                            <span>5</span>
                            <span class="visually-hidden">количество репостов</span>
                        </a>
                    </div>
                    <span class="post__view"><?= $val['views'] ?></span>
                </div>
            <?php
            endforeach; ?>
                <ul class="post__tags">
                    <?php
                    foreach ($hashtags as $key => $val): ?>
                        <li><a href="#"><?= $val['hs-name'] ?></a></li>
                    <?php
                    endforeach; ?>
                </ul>
                <div class="comments">
                    <form class="comments__form form" action="#" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section form__input-section--error">
                            <textarea class="comments__textarea form__textarea form__input"
                                      placeholder="Ваш комментарий"></textarea>
                            <label class="visually-hidden">Ваш комментарий</label>
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка валидации</h3>
                                <p class="form__error-desc">Это поле обязательно к заполнению</p>
                            </div>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                    <?php
                    if (array_key_exists('comment', $_GET) == true): ?>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php
                                foreach ($comment_all_list

                                as $key => $val): ?>
                                <?php
                                if ($val['comment'] != null): ?>

                                <li class="comments__item user">
                                    <div class="comments__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="comments__picture" src="<?= $val['avatar'] ?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="comments__info">
                                        <div class="comments__name-wrapper">
                                            <a class="comments__user-name" href="#">
                                                <span><?= $val['name'] ?></span>
                                            </a>
                                            <time class="comments__time" datetime="<?= $val['date'] ?>"><?= smallDate(
                                                    $val['date']
                                                ) ?></time>
                                        </div>
                                        <p class="comments__text">
                                            <?= $val['comment'] ?>
                                        </p>
                                    </div>
                                    <?php
                                    endif; ?>
                                    <?php
                                    endforeach; ?>
                                </li>
                            </ul>
                        </div>
                    <?php
                    else: ?>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php
                                foreach ($comment_list

                                as $key => $val): ?>
                                <?php
                                if ($val['comment'] != null): ?>
                                <li class="comments__item user">
                                    <div class="comments__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="comments__picture" src="<?= $val['avatar'] ?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="comments__info">
                                        <div class="comments__name-wrapper">
                                            <a class="comments__user-name" href="#">
                                                <span><?= $val['name'] ?></span>
                                            </a>
                                            <time class="comments__time" datetime="<?= $val['date'] ?>"><?= smallDate(
                                                    $val['date']
                                                ) ?></time>
                                        </div>
                                        <p class="comments__text">
                                            <?= $val['comment'] ?>
                                        </p>
                                    </div>
                                    <?php
                                    endif; ?>
                                    <?php
                                    endforeach; ?>
                                </li>
                            </ul>
                            <?php
                            foreach ($comment_count as $key => $val): ?>
                                <?php
                                if (($val['comment-count'] - 2) > 2): ?>
                                    <a class="comments__more-link" href="?post-id=<?= $val['post_num'] ?>&comment=all">
                                        <span>Показать все комментарии</span>
                                        <sup class="comments__amount"><?= $val['comment-count'] - 2 ?></sup>
                                    </a>
                                <?php
                                endif ?>
                            <?php
                            endforeach; ?>
                        </div>
                    <?php
                    endif; ?>
                </div>
            </div>
            <?php
            foreach ($author_info

            as $key => $val): ?>

            <div class="post-details__user user">
                <div class="post-details__user-info user__info">
                    <div class="post-details__avatar user__avatar">
                        <a class="post-details__avatar-link user__avatar-link" href="#">
                            <img class="post-details__picture user__picture" src="<?= $val['avatar'] ?>"
                                 alt="Аватар пользователя">
                        </a>
                    </div>


                    <div class="post-details__name-wrapper user__name-wrapper">
                        <a class="post-details__name user__name" href="#">
                            <span><?= $val['name'] ?></span>
                        </a>
                        <time class="post-details__time user__time" datetime="2014-03-20"><?= smallUSerDate(
                                $val['reg_date']
                            ) ?></time>
                    </div>
                </div>


                <div class="post-details__rating user__rating">
                    <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                        <span
                            class="post-details__rating-amount user__rating-amount"> <?= $val['subscribe-count'] ?> </span>
                        <span class="post-details__rating-text user__rating-text">подписчиков</span>
                    </p>
                    <?php
                    endforeach; ?>

                    <?php
                    foreach ($authorPosts_count

                    as $key => $val): ?>
                    <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                        <span
                            class="post-details__rating-amount user__rating-amount"><?= $val['publication_count'] ?></span>
                        <span class="post-details__rating-text user__rating-text">публикаций</span>
                    </p>
                </div>

                <?php
                endforeach; ?>

                <div class="post-details__user-buttons user__buttons">
                    <button class="user__button user__button--subscription button button--main" type="button">
                        Подписаться
                    </button>
                    <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                </div>
            </div>
        </div>
    </section>
</div>

