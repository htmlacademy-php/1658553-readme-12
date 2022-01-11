<?php
/* @var array $profileSubscribe */

?>
<section class="profile__subscriptions tabs__content tabs__content--active">
    <h2 class="visually-hidden">Подписки</h2>
    <ul class="profile__subscriptions-list">
        <?php
        foreach ($profileSubscribe as $profile => $info): ?>
            <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link"
                           href="profile.php?user=<?= $info['info']['user_id'] ?>">
                            <img class="post-mini__picture user__picture"
                                 src="<?= $info['info']['avatar'] ?>"
                                 alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name"
                           href="profile.php?user=<?= $info['info']['user_id'] ?>">
                            <span><?= $info['info']['name'] ?></span>
                        </a>
                        <time class="post-mini__time user__additional"
                              datetime="profile.php?user=<?= $info['info']['reg_date'] ?>"><?= smallDate(
                                $info['info']['reg_date'],
                                'на сайте'
                            ) ?></time>
                    </div>
                </div>
                <div class="post-mini__rating user__rating">
                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                        <span
                            class="post-mini__rating-amount user__rating-amount"><?= zeroForPostInfo(
                                $info['info']['publication_count']
                            ) ?></span>
                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                    </p>
                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                        <span
                            class="post-mini__rating-amount user__rating-amount"><?= zeroForPostInfo(
                                $info['info']['subscribe_count']
                            ) ?></span>
                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                    </p>
                </div>
                <div class="post-mini__user-buttons user__buttons">
                    <form method="get" action="subscribe.php"
                          class="post-details__user-buttons user__buttons">
                        <button name="authorId"
                                value="<?= $info['info']['user_id'] ?>"
                                class="post-mini__user-button user__button user__button--subscription button button--main <?php
                                if (!$info['isSubscribe']):print 'button--quartz'; endif; ?>"
                                type="submit">

                            <?php
                            if ($info['isSubscribe']):print 'Подписаться';
                            else:print 'Отписаться'; endif; ?>

                        </button>
                    </form>
                </div>
            </li>

        <?php
        endforeach; ?>
    </ul>
</section>
