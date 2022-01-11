<?php
/* @var array $searchContent */

foreach (
    $searchContent

    as $post
): ?>


    <article class="search__post post <?= $post['icon_name'] ?>">
        <header class="post__header post__author">
            <a class="post__author-link" href="#" title="Автор">
                <div class="post__avatar-wrapper">
                    <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя" width="60"
                         height="60">
                </div>
                <div class="post__info">
                    <b class="post__author-name"><?= $post['name'] ?></b>
                    <span class="post__time"><?= smallDate(
                            $post['create_date'],'назад'
                        ) ?></span>
                </div>
            </a>
        </header>
        <div class="post__main">
            <?php
            if ($post['icon_name'] === "post-photo"): ?>


                <h2><a href="post.php?post-id=<?= $post['post_num'] ?>"><?= $post['header'] ?></a></h2>
                <div class="post-photo__image-wrapper">
                    <img src="<?= $post['media'] ?>" alt="Фото от пользователя" width="760" height="396">
                </div>


            <?php
            elseif ($post['icon_name'] === "post-text"): ?>


                <h2><a href="post.php?post-id=<?= $post['post_num'] ?>"><?= $post['header'] ?></a></h2>
                <p>
                    <?= cutText(htmlspecialchars($post['text_content']),$post['post_num']) ?>
                </p>



            <?php
            elseif ($post['icon_name'] === "post-video"): ?>


                <div class="post-video__block">
                    <div class="post-video__preview">
                        <?= embedYoutubeCover($post['media']); ?>
                    </div>
                    <a href="http://localhost/1658553-readme-12/post.php?post-id=<?= $post['post_num'] ?>"
                       class="post-video__play-big button">
                        <svg class="post-video__play-big-icon" width="14" height="14">
                            <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        <span class="visually-hidden">Запустить проигрыватель</span>
                    </a>
                </div>


            <?php
            elseif ($post['icon_name'] === "post-quote"): ?>


                <blockquote>
                    <p>
                        <?= $post['text_content'] ?>
                    </p>
                    <cite>Xью Оден</cite>
                </blockquote>


            <?php
            elseif ($post['icon_name'] === "post-link"): ?>

                <div class="post-link__wrapper">
                    <a class="post-link__external" href="<?= $post['media'] ?>" title="Перейти по ссылке">
                        <div class="post-link__icon-wrapper">
                            <img src=" http://www.google.com/s2/favicons?domain=<?= $post['media'] ?>" alt="Иконка">
                        </div>
                        <div class="post-link__info">
                            <h3><?= $post['header'] ?></h3>
                            <span><?= parse_url($post['media'])['host'] ?></span>
                        </div>
                        <svg class="post-link__arrow" width="11" height="16">
                            <use xlink:href="#icon-arrow-right-ad"></use>
                        </svg>
                    </a>
                </div>


            <?php
            endif ?>
        </div>

        <footer class="post__footer post__indicators">
            <div class="post__buttons">
                <a class="post__indicator post__indicator--likes button" href="like.php?id=<?= $post['post_num'] ?>" title="Лайк">
                    <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                    </svg>
                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                    </svg>
                    <span><?= $post['count_likes'] ?></span>
                    <span class="visually-hidden">количество лайков</span>
                </a>
                <a class="post__indicator post__indicator--comments button" href="post.php?post-id=<?= $post['post_num'] ?>" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span><?= $post['count_comments'] ?></span>
                    <span class="visually-hidden">количество комментариев</span>
                </a>
            </div>
        </footer>
    </article>


<?php
endforeach; ?>
