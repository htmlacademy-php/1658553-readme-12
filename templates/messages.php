<?php
/* @var array $conversation */

/* @var array $errors */
/* @var array $messagesList */
/* @var int $userTabsActive */
/* @var array $mainUserInfo */


?>

<h1 class="visually-hidden">Личные сообщения</h1>
<section class="messages tabs">
    <h2 class="visually-hidden">Сообщения</h2>
    <div class="messages__contacts">
        <ul class="messages__contacts-list tabs__list">

            <?php
            foreach ($conversation as $user => $info): ?>
                <li class="messages__contacts-item">
                    <a class="messages__contacts-tab  tabs__item <?php
                    if ((int)$userTabsActive === $info['id']) {
                        print 'messages__contacts-tab--active';
                    } ?>"
                       href="?dialog=<?= $info['id'] ?>">
                        <div class="messages__avatar-wrapper">
                            <img class="messages__avatar"
                                 src="<?= $info['avatar'] ?>"
                                 alt="Аватар пользователя">
                        </div>
                        <div class="messages__info">
                  <span class="messages__contact-name">
                    <?= htmlspecialchars($info['login']) ?>
                  </span>
                            <div class="messages__preview">

                                <p class="messages__preview-text">
                                    <?php
                                    if (!empty($info['preview']['content'])) {
                                        print htmlspecialchars($info['preview']['content']);
                                    } ?>
                                </p>

                                <time class="messages__preview-time"
                                      datetime="<?php
                                      if (!empty($info['preview']['create_date'])) {
                                          print $info['preview']['create_date'];
                                      } ?>">
                                    <?php
                                    if (!empty($info['preview']['create_date'])) {
                                        print date('H:i',
                                            strtotime($info['preview']['create_date']));
                                    } ?>

                                </time>
                            </div>
                        </div>
                    </a>
                </li>
            <?php
            endforeach; ?>

        </ul>
    </div>

    <div class="messages__chat" <?php
    if (!$userTabsActive) {
        print "style = 'visibility: hidden'";
    } ?>>
        <div class="messages__chat-wrapper">

            <ul class="messages__list tabs__content tabs__content--active">
                <?php
                foreach ($messagesList as $message => $info): ?>
                    <li class="messages__item">
                        <div class="messages__info-wrapper">
                            <div class="messages__item-avatar">
                                <a class="messages__author-link" href="#">
                                    <img class="messages__avatar"
                                         src="<?= $info['avatar'] ?>"
                                         alt="Аватар пользователя">
                                </a>
                            </div>
                            <div class="messages__item-info">
                                <a class="messages__author" href="#">
                                    <?= htmlspecialchars($info['login']) ?>
                                </a>
                                <time class="messages__time"
                                      datetime="<?= $info['create_date'] ?>">
                                    <?= smallDate($info['create_date'],
                                        'назад') ?>
                                </time>
                            </div>
                        </div>
                        <p class="messages__text">
                            <?= htmlspecialchars($info['content']) ?>
                        </p>
                    </li>
                <?php
                endforeach; ?>
            </ul>

        </div>
        <div class="comments">
            <form class="comments__form form" action="send-message.php" method="post">
                <div class="comments__my-avatar">
                    <img class="comments__picture" src="<?=$mainUserInfo['avatar'];?>"
                         alt="Аватар пользователя">
                </div>
                <div class="form__input-section <?php
                if ($errors) {
                    print 'form__input-section--error';
                } ?>">
                <textarea class="comments__textarea form__textarea form__input"
                          placeholder="Ваше сообщение" name="message"></textarea>
                    <label class="visually-hidden">Ваше сообщение</label>
                    <input type="hidden" name="interlocutor" value="<?= $userTabsActive ?>"/>
                    <button class="form__error-button button" type="button">!
                    </button>
                    <?php
                    if ($errors): ?>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка валидации</h3>
                            <p class="form__error-desc">Это поле обязательно к
                                заполнению</p>
                        </div>
                    <?php
                    endif; ?>
                </div>

                <button class="comments__submit button button--green"
                        type="submit">Отправить
                </button>
            </form>
        </div>
    </div>

</section>

