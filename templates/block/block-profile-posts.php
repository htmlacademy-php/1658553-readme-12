<?php

/* @var array $profilePosts */

/* @var int $userId */
/* @var bool $isComment */
/* @var bool $isCommentShowAll */
/* @var array $errors */


foreach (
    $profilePosts

    as $post
):
    ?>


    <section class="profile__posts tabs__content tabs__content--active">
        <h2 class="visually-hidden">Публикации</h2>

        <article class="profile__post post <?= $post['icon_name'] ?>">
            <?php
            if ($post['repost']): ?>
                <header class="post__header">
                    <div class="post__author">
                        <a class="post__author-link"
                           href="profile.php?user=<?= $post['original']['id'] ?>"
                           title="Автор">
                            <div
                                class="post__avatar-wrapper post__avatar-wrapper--repost">
                                <img class="post__author-avatar"
                                     src="<?= $post['original']['avatar'] ?>"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name">Репост: <?= htmlspecialchars($post['original']['login']) ?></b>
                                <time class="post__time"
                                      datetime="<?= $post['create_date'] ?>"> <?= smallDate(
                                        $post['create_date'],
                                        'назад'
                                    ) ?></time>
                            </div>
                        </a>
                    </div>
                </header>
            <?php
            else: ?>
                <header class="post__header">
                    <h2>
                        <a href="post.php?post-id=<?= $post['post_num'] ?>"><?= htmlspecialchars($post['name']) ?></a>
                    </h2>
                </header>
            <?php
            endif; ?>
            <div class="post__main">
                <?php
                if ($post['icon_name'] === "post-photo"): ?>
                    <h2>
                        <a href="post.php?post-id=<?= $post['post_num'] ?>"><?= htmlspecialchars($post['header']) ?></a>
                    </h2>
                    <div class="post-photo__image-wrapper">
                        <img src="<?= htmlspecialchars($post['media']) ?>"
                             alt="Фото от пользователя" width="760"
                             height="396">
                    </div>
                <?php
                elseif ($post['icon_name'] === "post-text"): ?>
                    <h2>
                        <a href="post.php?post-id=<?= $post['post_num'] ?>"><?= htmlspecialchars($post['header']) ?></a>
                    </h2>
                    <p>
                        <?= htmlspecialchars(cutText(
                            ($post['text_content']),
                            $post['post_num']
                        )) ?>
                    </p>
                <?php
                elseif ($post['icon_name'] === "post-video"): ?>
                    <div class="post-video__block">
                        <div class="post-video__preview">
                            <?= embedYoutubeCover($post['media']); ?>
                        </div>
                        <a href="post.php?post-id=<?= $post['post_num'] ?>"
                           class="post-video__play-big button">
                            <svg class="post-video__play-big-icon" width="14"
                                 height="14">
                                <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                            <span class="visually-hidden">Запустить проигрыватель</span>
                        </a>
                    </div>
                <?php
                elseif ($post['icon_name'] === "post-quote"): ?>
                    <blockquote>
                        <p>
                            <?= htmlspecialchars($post['text_content']) ?>
                        </p>
                        <cite><?= htmlspecialchars($post['author_copy_right']) ?></cite>
                    </blockquote>
                <?php
                elseif ($post['icon_name'] === "post-link"): ?>
                    <div class="post-link__wrapper">
                        <a class="post-link__external"
                           href="<?= $post['media'] ?>"
                           title="Перейти по ссылке">
                            <div class="post-link__icon-wrapper">
                                <img
                                    src="https://www.google.com/s2/favicons?domain=<?= $post['media'] ?>"
                                    alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3><?= htmlspecialchars($post['header']) ?></h3>
                                <span><?= parse_url($post['media'])['host'] ?></span>


                            </div>
                            <svg class="post-link__arrow" width="11"
                                 height="16">
                                <use xlink:href="#icon-arrow-right-ad"></use>
                            </svg>
                        </a>
                    </div>
                <?php
                endif ?>
            </div>
            <footer class="post__footer">
                <div class="post__indicators">
                    <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes button"
                           href="like.php?id=<?= $post['post_num'] ?>"
                           title="Лайк">
                            <svg class="post__indicator-icon" width="20"
                                 height="17">
                                <use xlink:href="#icon-heart"></use>
                            </svg>
                            <svg
                                class="post__indicator-icon post__indicator-icon--like-active"
                                width="20" height="17">
                                <use xlink:href="#icon-heart-active"></use>
                            </svg>
                            <span><?= $post['count_likes'] ?></span>
                            <span
                                class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--repost button"
                           href="repost.php?postId=<?= $post['post_num'] ?>"
                           title="Репост">
                            <svg class="post__indicator-icon" width="19"
                                 height="17">
                                <use xlink:href="#icon-repost"></use>
                            </svg>
                            <span><?= $post['repost_count'] ?></span>
                            <span
                                class="visually-hidden"><?= $post['repost_count'] ?></span>
                        </a>
                    </div>
                    <time class="post__time" datetime="<?= smallDate(
                        $post['create_date'],
                        'назад'
                    ) ?>"><?= smallDate(
                            $post['create_date'],
                            'назад'
                        ) ?></time>
                </div>
                <ul class="post__tags">
                    <?php
                    foreach ($post['hashtags'] as $key => $hashtag): ?>
                        <li><a href="search.php?q=<?= urlencode(
                                $hashtag
                            ) ?>"><?php if (!empty($hashtag)){
                                print htmlspecialchars($hashtag);
                                }  ?></a></li>
                    <?php
                    endforeach; ?>
                </ul>
            </footer>
            <?php
            if (!$isComment): ?>

                <div class="comments">
                    <a class="comments__button button"
                       href="profile.php?user=<?= $userId ?>&comment=enabled">Показать
                        комментарии</a>
                </div>
            <?php
            elseif ($post['comment_list'][0]['comment']): ?>
                <div class="comments">
                    <div class="comments__list-wrapper">
                        <?php
                        foreach ($post['comment_list'] as $key => $comment): ?>
                        <ul class="comments__list">
                            <li class="comments__item user">
                                <div class="comments__avatar">
                                    <a class="user__avatar-link"
                                       href="profile.php?user=<?= $comment['user_comment_id'] ?>">
                                        <img class="comments__picture"
                                             src="<?= $comment['avatar'] ?>"
                                             alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="comments__info">
                                    <div class="comments__name-wrapper">
                                        <a class="comments__user-name"
                                           href="profile.php?user=<?= $comment['user_comment_id'] ?>">
                                            <span><?= htmlspecialchars($comment['name']) ?></span>
                                        </a>
                                        <time class="comments__time"
                                              datetime="<?= $comment['date'] ?>"><?= smallDate(
                                                $comment['date'],
                                                'назад'
                                            ) ?></time>
                                    </div>
                                    <p class="comments__text">
                                        <?= htmlspecialchars($comment['comment']) ?>
                                    </p>
                                </div>
                            </li>
                            <?php
                            endforeach; ?>
                        </ul>
                        <?php
                        if (($post['count_comments'] - 2) > 0
                            && !$isCommentShowAll
                        ): ?>
                            <a class="comments__more-link"
                               href="profile.php?user=<?= $userId ?>&comment=enabled&view=all">
                                <span>Показать все комментарии</span>
                                <sup
                                    class="comments__amount"><?= $post['count_comments']
                                    - 2 ?></sup>
                            </a>
                        <?php
                        endif ?>
                    </div>
                </div>
                <form class="comments__form form" action="add-comment.php"
                      method="post">
                    <div class="comments__my-avatar">
                        <img class="comments__picture"
                             src="<?= $post['avatar'] ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="form__input-section <?php
                    if ($errors):print 'form__input-section--error'; endif; ?>">
                            <textarea
                                class="comments__textarea form__textarea form__input"
                                id="commentText"
                                name="commentText"
                                placeholder="Ваш комментарий"></textarea>
                        <label class="visually-hidden">Ваш комментарий</label>
                        <button class="form__error-button button" type="button">
                            !
                        </button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка валидации</h3>
                            <p class="form__error-desc"><?= $errors['commentText'] ?></p>
                        </div>
                    </div>
                    <button class="comments__submit button button--green"
                            name="postId"
                            value="<?= $post['post_num'] ?>" type="submit">
                        Отправить
                    </button>
                </form>
            <?php
            endif; ?>
        </article>
    </section>
<?php
endforeach; ?>
