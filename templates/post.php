<?php
/* @var array $postMainContent */

/* @var array $authorPostsCount */
/* @var array $commentList */
/* @var array $commentAllList */
/* @var bool $isUserSubscribe */
/* @var string $userAvatar */
/* @var array $commentErrors */
/* @var array $errors */
/* @var array $hashtags */
/* @var int $repostCount */
/* @var bool $isCommentShowAll */


?>


<div class="container">
    <h1 class="page__title page__title--publication"><?= $postMainContent['header'] ?></h1>
    <section class="post-details">
        <h2 class="visually-hidden">Публикация</h2>
        <div class="post-details__wrapper <?= $postMainContent['icon_name'] ?>">
            <div class="post-details__main-block post post--details">


                <?php
                if ($postMainContent['icon_name'] === "post-photo"): ?>

                    <div class="post-details__image-wrapper post-photo__image-wrapper">
                        <img src="<?= $postMainContent['media']; ?>" alt="Фото от пользователя" width="760"
                             height="507">
                    </div>


                <?php
                elseif ($postMainContent['icon_name'] === "post-quote"): ?>


                    <div class="post-details__image-wrapper post-quote">
                        <div class="post__main">
                            <blockquote>
                                <p>
                                    <?= $postMainContent['text']; ?>
                                </p>
                                <cite><?= $postMainContent['author_copy_right']; ?></cite>
                            </blockquote>
                        </div>
                    </div>


                <?php
                elseif ($postMainContent['icon_name'] === "post-text"): ?>


                    <div class="post-details__image-wrapper post-text">
                        <div class="post__main">
                            <p>
                                <?= $postMainContent['text']; ?>
                            </p>
                        </div>
                    </div>


                <?php
                elseif ($postMainContent['icon_name'] === "post-link"): ?>


                    <div class="post__main">
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="<?= $postMainContent['media'] ?>"
                               title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img
                                            src="https://www.google.com/s2/favicons?domain=<?= $postMainContent['media'] ?>"
                                            alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?= $postMainContent['header'] ?></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                <?php
                elseif ($postMainContent['icon_name'] === "post-video"): ?>


                    <div class="post-details__image-wrapper post-photo__image-wrapper">
                        <?= embedYoutubeVideo($postMainContent['media']) ?>
                    </div>

                <?php
                endif ?>


                <div class="post__indicators">
                    <div class="post__buttons">

                        <a class="post__indicator post__indicator--likes button"
                           href="like.php?id=<?= $postMainContent['post_num'] ?>" title="Лайк">
                            <svg class="post__indicator-icon" width="20" height="17">
                                <use xlink:href="#icon-heart"></use>
                            </svg>
                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                 height="17">
                                <use xlink:href="#icon-heart-active"></use>
                            </svg>

                            <span><?= $postMainContent['count_likes'] ?></span>

                            <span class="visually-hidden">количество лайков</span>
                        </a>


                        <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                            <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-comment"></use>
                            </svg>
                            <span><?= $postMainContent['count_comments'] ?></span>
                            <span class="visually-hidden">количество комментариев</span>
                        </a>
                        <a class="post__indicator post__indicator--repost button"
                           href="repost.php?postId=<?= $postMainContent['post_num'] ?>" title="Репост">
                            <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-repost"></use>
                            </svg>

                            <span><?= $repostCount ?></span>
                            <span class="visually-hidden">количество репостов</span>
                        </a>
                    </div>
                    <span class="post__view"><?= $postMainContent['views'] ?></span>
                </div>

                <ul class="post__tags">

                    <?php
                    foreach ($hashtags as $key => $hashtag): ?>
                        <li><a href="search.php?q=<?= urlencode($hashtag) ?>"><?= $hashtag ?></a></li>
                    <?php
                    endforeach; ?>
                </ul>
                <div class="comments">
                    <form class="comments__form form" action="add-comment.php" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="<?= $userAvatar ?>" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section <?php
                        if ($errors):print 'form__input-section--error'; endif; ?>">
                            <textarea class="comments__textarea form__textarea form__input" id="commentText"
                                      name="commentText"
                                      placeholder="Ваш комментарий"></textarea>
                            <label class="visually-hidden">Ваш комментарий</label>
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка валидации</h3>
                                <p class="form__error-desc"><?= $errors['commentText'] ?></p>
                            </div>
                        </div>
                        <button class="comments__submit button button--green" name="postId"
                                value="<?= $postMainContent['post_num'] ?>" type="submit">Отправить
                        </button>
                    </form>

                    <div class="comments__list-wrapper">
                        <ul class="comments__list">
                            <?php
                            foreach ($commentList

                            as  $comments): ?>
                            <li class="comments__item user">
                                <div class="comments__avatar">
                                    <a class="user__avatar-link" href="#">
                                        <img class="comments__picture" src="<?= $comments['avatar'] ?>"
                                             alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="comments__info">
                                    <div class="comments__name-wrapper">
                                        <a class="comments__user-name" href="#">
                                            <span><?= $comments['name'] ?></span>
                                        </a>
                                        <time class="comments__time"
                                              datetime="<?= $comments['date'] ?>"><?= smallDate(
                                                $comments['date'],
                                                'назад'
                                            ) ?></time>
                                    </div>
                                    <p class="comments__text">
                                        <?= $comments['comment'] ?>
                                    </p>
                                </div>
                                <?php
                                endforeach; ?>
                            </li>
                        </ul>
                        <?php
                        if (($postMainContent['count_comments'] - 2) > 0 && !$isCommentShowAll): ?>
                            <a class="comments__more-link"
                               href="?post-id=<?= $postMainContent['post_num'] ?>&comment=all">
                                <span>Показать все комментарии</span>
                                <sup class="comments__amount"><?= $postMainContent['count_comments'] - 2 ?></sup>
                            </a>
                        <?php
                        endif ?>

                    </div>

                </div>
            </div>


            <div class="post-details__user user">
                <div class="post-details__user-info user__info">
                    <div class="post-details__avatar user__avatar">
                        <a class="post-details__avatar-link user__avatar-link" href="#">
                            <img class="post-details__picture user__picture" src="<?= $postMainContent['avatar'] ?>"
                                 alt="Аватар пользователя">
                        </a>
                    </div>


                    <div class="post-details__name-wrapper user__name-wrapper">
                        <a class="post-details__name user__name" href="#">
                            <span><?= $postMainContent['name'] ?></span>
                        </a>
                        <time class="post-details__time user__time" datetime="2014-03-20"><?= smallDate(
                                $postMainContent['reg_date'],
                                'назад'
                            ) ?></time>
                    </div>
                </div>


                <div class="post-details__rating user__rating">
                    <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                        <span
                            class="post-details__rating-amount user__rating-amount"> <?= zeroForPostInfo(
                                $postMainContent['subscribe_count']
                            ) ?> </span>
                        <span class="post-details__rating-text user__rating-text">подписчиков</span>
                    </p>


                    <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                        <span
                            class="post-details__rating-amount user__rating-amount"><?= $authorPostsCount['publication_count'] ?></span>
                        <span class="post-details__rating-text user__rating-text">публикаций</span>
                    </p>
                </div>


                <form method="get" action="subscribe.php" class="post-details__user-buttons user__buttons">
                    <button name="authorId" value="<?= $postMainContent['user_id'] ?>"
                            class="user__button user__button--subscription button button--main <?php
                            if (!$isUserSubscribe):print 'button--quartz'; endif; ?>" type="submit">

                        <?php
                        if ($isUserSubscribe):print 'Подписаться';
                        else:print 'Отписаться'; endif; ?>

                    </button>
                    <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                </form>
            </div>
        </div>
    </section>
</div>

