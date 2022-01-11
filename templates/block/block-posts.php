<?php
/* @var array $postListRows */


foreach (
    $postListRows

    as $post
):?>

    <article class="popular__post post <?= $post['icon_name'] ?>">
        <header class="post__header">
            <h2>
                <a href="post.php?post-id=<?= $post['post_num'] ?>">
                    <?= htmlspecialchars($post['header']) ?>
                </a>
            </h2>
        </header>
        <div class="post__main">
            <?php
            if ($post['icon_name'] === "post-quote"): ?>
                <blockquote>
                    <p>
                        <?= htmlspecialchars($post['text_content']) ?>
                    </p>
                    <cite><?= $post['author_copy_right'] ?></cite>

                </blockquote>
            <?php
            elseif ($post['icon_name'] === "post-text"): ?>

                <p>
                    <?= cutText(
                        htmlspecialchars($post['text_content']),
                        $post['post_num']
                    ) ?>

                </p>


            <?php
            elseif ($post['icon_name'] === "post-photo"): ?>

                <div class="post-photo__image-wrapper">
                    <img src="<?= $post['media'] ?>" alt="Фото от пользователя"
                         width="360"
                         height="240">
                </div>

            <?php
            elseif ($post['icon_name'] === "post-link"): ?>

                <div class="post-link__wrapper">
                    <a class="post-link__external" href="<?= $post['media'] ?>"
                       title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                            <div class="post-link__icon-wrapper">
                                <img
                                    src="https://www.google.com/s2/favicons?domain=<?= $post['media'] ?>"
                                    alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3>
                                    <?= htmlspecialchars($post['header']) ?>
                                </h3>
                                <span><?= parse_url(
                                        $post['media']
                                    )['host'] ?></span>
                            </div>
                        </div>

                    </a>
                </div>

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
                        <span
                            class="visually-hidden">Запустить проигрыватель</span>
                    </a>
                </div>
            <?php
            endif ?>

        </div>
        <footer class="post__footer">
            <div class="post__author">
                <a class="post__author-link"
                   href="profile.php?user=<?= $post['author_id'] ?>"
                   title="Автор">
                    <div class="post__avatar-wrapper">
                        <img class="post__author-avatar"
                             style="width: 40px; height: 40px"
                             src="<?= htmlspecialchars($post['avatar']) ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="post__info">
                        <b class="post__author-name">
                            <?= htmlspecialchars($post['name']) ?>

                        </b>
                        <time title="<?= cutDate($post['create_date']) ?>"
                              class="post__time"
                              datetime="<?= fullDate(
                                  $post['create_date']
                              ) ?>"><?= smallDate(
                                $post['create_date'],
                                'назад'
                            ) ?></time>
                    </div>
                </a>
            </div>
            <div class="post__indicators">
                <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button"
                       href="like.php?id=<?= $post['post_num'] ?>" title="Лайк">
                        <svg class="post__indicator-icon" width="20"
                             height="17">
                            <use xlink:href="#icon-heart"></use>
                        </svg>
                        <svg
                            class="post__indicator-icon post__indicator-icon--like-active"
                            width="20"
                            height="17">
                            <use xlink:href="#icon-heart-active"></use>
                        </svg>
                        <span><?= $post['count_likes'] ?></span>

                        <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--comments button"
                       href="#"
                       title="Комментарии">
                        <svg class="post__indicator-icon" width="19"
                             height="17">
                            <use xlink:href="#icon-comment"></use>
                        </svg>

                        <span><?= $post['count_comments'] ?></span>
                        <span
                            class="visually-hidden">количество комментариев</span>

                    </a>
                </div>
            </div>
        </footer>
    </article>
<?php
endforeach; ?>
