<?php

foreach (
    $postListRows

    as $key => $val
): ?>

    <article class="popular__post post <?= $val['icon_name'] ?>">
        <header class="post__header">
            <h2>
                <a href="post.php?post-id=<?= $val['post_num'] ?>">
                    <?= htmlspecialchars($val['header']) ?>

                </a>
            </h2>
        </header>

        <div class="post__main">
            <?php
            if ($val['icon_name'] == "post-quote"): ?>

                <div class="post-link__wrapper">
                    <a class="post-link__external" href="http://" title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                            <div class="post-link__icon-wrapper">
                                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                     alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3>
                                    <?= htmlspecialchars($val['header']) ?>

                                </h3>
                            </div>
                        </div>
                        <span>
                                <?= htmlspecialchars($val['text_content']) ?>

                            </span>
                    </a>
                </div>
            <?php
            elseif ($val['icon_name'] == "post-text"): ?>

                <p>
                    <?= cutText(htmlspecialchars($val['text_content'])) ?>

                </p>


            <?php
            elseif ($val['icon_name'] == "post-photo"): ?>

                <div class="post-photo__image-wrapper">
                    <img src="<?= htmlspecialchars($val['media']) ?>" alt="Фото от пользователя" width="360"
                         height="240">
                </div>

            <?php
            elseif ($val['icon_name'] == "post-link"): ?>

                <div class="post-link__wrapper">
                    <a class="post-link__external" href="http://" title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                            <div class="post-link__icon-wrapper">
                                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                     alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3>
                                    <?= htmlspecialchars($val['header']) ?>

                                </h3>
                            </div>
                        </div>
                        <span>
                                        <?= htmlspecialchars($val['text_content']) ?>

                            </span>
                    </a>
                </div>
            <?php
            elseif ($val['icon_name'] == "post-video"): ?>
                <div class="post-video__block">
                    <div class="post-video__preview">
                        <?= embed_youtube_cover($val['media']); ?>
                    </div>
                    <a href="http://localhost/1658553-readme-12/post.php?post-id=<?= $val['post_num'] ?>"
                       class="post-video__play-big button">
                        <svg class="post-video__play-big-icon" width="14" height="14">
                            <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        <span class="visually-hidden">Запустить проигрыватель</span>
                    </a>
                </div>
        <?php
        endif ?>

        </div>
        <footer class="post__footer">
            <div class="post__author">
                <a class="post__author-link" href="#" title="Автор">
                    <div class="post__avatar-wrapper">
                        <img class="post__author-avatar" src="<?= htmlspecialchars($val['avatar']) ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="post__info">
                        <b class="post__author-name">
                            <?= htmlspecialchars($val['name']) ?>

                        </b>
                        <time title="<?= cutdate($val['create_date']) ?>" class="post__time"
                              datetime="<?= fullDate($val['create_date']) ?>"><?= smallDate(
                                $val['create_date']
                            ) ?></time>
                    </div>
                </a>
            </div>
            <div class="post__indicators">
                <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                        <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                        </svg>
                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                             height="17">
                            <use xlink:href="#icon-heart-active"></use>
                        </svg>
                        <span><?= $val['count_likes'] ?></span>

                        <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--comments button" href="#"
                       title="Комментарии">
                        <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-comment"></use>
                        </svg>

                        <span><?= $val['count_comments'] ?></span>
                        <span class="visually-hidden">количество комментариев</span>

                    </a>
                </div>
            </div>
        </footer>
    </article>
<?php
endforeach; ?>
