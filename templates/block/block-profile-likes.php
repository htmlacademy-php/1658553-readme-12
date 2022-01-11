<?php

/* @var array $profileLikes */

/* @var array $contentTypes */

foreach ($profileLikes as $array => $likeContent):
    ?>

    <section class="profile__likes tabs__content tabs__content--active">
        <h2 class="visually-hidden">Лайки</h2>
        <ul class="profile__likes-list">
            <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link"
                           href="profile.php?user=<?= $likeContent['user_who_likes'] ?>">
                            <img class="post-mini__picture user__picture"
                                 src="<?= $likeContent['avatar'] ?>"
                                 alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name"
                           href="profile.php?user=<?= $likeContent['user_who_likes'] ?>">
                            <span><?= $likeContent['login'] ?></span>
                        </a>
                        <div class="post-mini__action">
                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                            <time class="post-mini__time user__additional"
                                  datetime="<?= $likeContent['like_date'] ?>"><?= smallDate(
                                    $likeContent['like_date'],
                                    'назад'
                                ) ?>
                            </time>
                        </div>
                    </div>
                </div>

                <div class="post-mini__preview">


                    <?php
                    foreach ($contentTypes as $type => $typeId): ?>

                        <?php
                        if ($type === TYPE_PHOTO
                            && $typeId['id'] === $likeContent['content_type_id']
                        ): ?>
                            <a class="post-mini__link"
                               href="post.php?post-id=<?= $likeContent['post_id'] ?>"
                               title="Перейти на публикацию">
                                <div class="post-mini__image-wrapper">
                                    <img class="post-mini__image"
                                         src="<?= $likeContent['media'] ?>"
                                         width="109"
                                         height="109"
                                         alt="Превью публикации">
                                </div>
                                <span class="visually-hidden">Фото</span>
                            </a>

                        <?php
                        elseif ($type === TYPE_VIDEO
                            && $typeId['id'] === $likeContent['content_type_id']
                        ): ?>
                            <a class="post-mini__link"
                               href="post.php?post-id=<?= $likeContent['post_id'] ?>"
                               title="Перейти на публикацию">
                                <div class="post-mini__image-wrapper">
                                    <?= embedYoutubeCover(
                                        $likeContent['media']
                                    ); ?>
                                    <span class="post-mini__play-big">
                            <svg class="post-mini__play-big-icon" width="12"
                                 height="13">
                              <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                          </span>
                                </div>
                                <span class="visually-hidden">Видео</span>
                            </a>

                        <?php
                        elseif ($typeId['id']
                            === $likeContent['content_type_id']
                        ): ?>
                            <a class="post-mini__link"
                               href="post.php?post-id=<?= $likeContent['post_id'] ?>"
                               title="Перейти на публикацию">
                                <span class="visually-hidden">Текст</span>
                                <svg class="post-mini__preview-icon" width="20"
                                     height="21">
                                    <use
                                        xlink:href="#icon-filter-<?= $type ?>"></use>
                                </svg>
                            </a>
                        <?php
                        endif; ?>
                    <?php
                    endforeach; ?>
                </div>
            </li>
        </ul>
    </section>
<?php
endforeach; ?>
