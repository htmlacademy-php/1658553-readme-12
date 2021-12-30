<?php
/* @var string $userName */

/* @var string $avatar */
/* @var string $userId */

?>
<div class="header__wrapper container">
    <div class="header__logo-wrapper">
        <a class="header__logo-link" href="index.php">
            <img class="header__logo" src="img/logo.svg" alt="Логотип readme" width="128" height="24">
        </a>
        <p class="header__topic">
            micro blogging
        </p>
    </div>
    <form class="header__search-form form"  action="search.php" method="get">
        <div class="header__search">
            <label class="visually-hidden">Поиск</label>
            <input class="header__search-input form__input" name="q" type="search">
            <button class="header__search-button button"  type="submit">
                <svg class="header__search-icon" width="18" height="18">
                    <use xlink:href="#icon-search"></use>
                </svg>
                <span class="visually-hidden">Начать поиск</span>
            </button>
        </div>
    </form>
    <div class="header__nav-wrapper">
        <nav class="header__nav">
            <ul class="header__my-nav">
                <li class="header__my-page header__my-page--popular">
                    <a class="header__page-link <?php
                    if (strrchr($_SERVER['PHP_SELF'], '/') === PAGE_POPULAR) {
                        print  "header__page-link--active";
                    } ?>"
                       href="popular.php" title="Популярный контент">
                        <span class="visually-hidden">Популярный контент</span>
                    </a>
                </li>
                <li class="header__my-page header__my-page--feed">
                    <a class="header__page-link <?php
                    if (strrchr($_SERVER['PHP_SELF'], '/') === PAGE_FEED) {
                        print  "header__page-link--active";
                    } ?>" href="feed.php" title="Моя лента">
                        <span class="visually-hidden">Моя лента</span>
                    </a>
                </li>
                <li class="header__my-page header__my-page--messages">
                    <a class="header__page-link" href="messages.html" title="Личные сообщения">
                        <span class="visually-hidden">Личные сообщения</span>
                    </a>
                </li>
            </ul>

            <ul class="header__user-nav">
                <li class="header__profile">
                    <a class="header__profile-link" href="#">
                        <div class="header__avatar-wrapper">
                            <img class="header__profile-avatar" src="<?= $avatar ?>"
                                 alt="Аватар профиля">
                        </div>
                        <div class="header__profile-name">
                                <span>
                                    <?= $userName ?>

                                </span>
                            <svg class="header__link-arrow" width="10" height="6">
                                <use xlink:href="#icon-arrow-right-ad"></use>
                            </svg>
                        </div>
                    </a>
                    <div class="header__tooltip-wrapper">
                        <div class="header__profile-tooltip">
                            <ul class="header__profile-nav">
                                <li class="header__profile-nav-item">
                                    <a class="header__profile-nav-link" href="profile.php?user=<?=$userId?>">
                          <span class="header__profile-nav-text">
                            Мой профиль
                          </span>
                                    </a>
                                </li>
                                <li class="header__profile-nav-item">
                                    <a class="header__profile-nav-link" href="#">
                          <span class="header__profile-nav-text">
                            Сообщения
                            <i class="header__profile-indicator">2</i>
                          </span>
                                    </a>
                                </li>

                                <li class="header__profile-nav-item">
                                    <a class="header__profile-nav-link" href="logout.php">
                          <span class="header__profile-nav-text">
                            Выход
                          </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="header__post-button button button--transparent" href="add.php">Пост</a>
                </li>
            </ul>
        </nav>
    </div>
