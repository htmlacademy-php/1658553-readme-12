<?php
/* @var string $postContent */

/* @var array $profileInfo */
/* @var bool $isUserSubscribe */
/* @var string $profileBlock */


?>
<h1 class="visually-hidden">Профиль</h1>
<div class="profile profile--default">
    <div class="profile__user-wrapper">
        <div class="profile__user user container">
            <div class="profile__user-info user__info">
                <div class="profile__avatar user__avatar">
                    <img class="profile__picture user__picture"
                         src="<?= $profileInfo['avatar'] ?>"
                         alt="Аватар пользователя">
                </div>
                <div class="profile__name-wrapper user__name-wrapper">
                    <span
                        class="profile__name user__name"><?= htmlspecialchars($profileInfo['name']) ?></span>
                    <time class="profile__user-time user__time"
                          datetime="2014-03-20"><?= smallDate(
                            $profileInfo['reg_date'],
                            'на сайте'
                        ) ?></time>
                </div>
            </div>
            <div class="profile__rating user__rating">
                <p class="profile__rating-item user__rating-item user__rating-item--publications">
                    <span class="user__rating-amount"><?= zeroForPostInfo(
                            $profileInfo['publication_count']
                        ) ?></span>
                    <span class="profile__rating-text user__rating-text">публикаций</span>
                </p>
                <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                    <span class="user__rating-amount"><?= zeroForPostInfo(
                            $profileInfo['subscribe_count']
                        ) ?></span>
                    <span class="profile__rating-text user__rating-text">подписчиков</span>
                </p>
            </div>
            <form method="get" action="subscribe.php"
                  class="profile__user-buttons user__buttons">
                <button name="authorId" value="<?= $profileInfo['user_id'] ?>"
                        class="profile__user-button user__button user__button--subscription button button--main <?php
                        if (!$isUserSubscribe):print 'button--quartz'; endif; ?>"
                        type="submit">

                    <?php
                    if ($isUserSubscribe):print 'Подписаться';
                    else:print 'Отписаться'; endif; ?>

                </button>
                <a class="user__button user__button--writing button button--green"
                   href="messages.php?dialog=<?=$profileInfo['user_id']?>">Сообщение</a>
            </form>
        </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
        <div class="container">
            <div class="profile__tabs filters">
                <b class="profile__tabs-caption filters__caption">Показать:</b>
                <ul class="profile__tabs-list filters__list tabs__list">
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link filters__button <?php
                        if ($profileBlock === SHOW_POSTS or is_null(
                                $profileBlock
                            )
                        ) {
                            print 'filters__button--active';
                        } ?> tabs__item tabs__item--active button"
                           href="?user=<?= $profileInfo['user_id'] ?>&show=<?= SHOW_POSTS ?>">Посты</a>
                    </li>
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link <?php
                        if ($profileBlock === SHOW_LIKES) {
                            print 'filters__button--active';
                        } ?> filters__button tabs__item button"
                           href="?user=<?= $profileInfo['user_id'] ?>&show=<?= SHOW_LIKES ?>">Лайки</a>
                    </li>
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link <?php
                        if ($profileBlock === SHOW_SUBSCRIBE) {
                            print 'filters__button--active';
                        } ?> filters__button tabs__item button"
                           href="?user=<?= $profileInfo['user_id'] ?>&show=<?= SHOW_SUBSCRIBE ?>">Подписки</a>
                    </li>
                </ul>
            </div>
            <div class="profile__tab-content">
                <?= $postContent ?>
            </div>
        </div>
    </div>
</div>


