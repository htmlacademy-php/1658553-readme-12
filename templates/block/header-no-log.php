<div class="header__wrapper container">
    <div class="header__logo-wrapper">
        <a class="header__logo-link" href="main.html">
            <img class="header__logo" src="img/logo.svg" alt="Логотип readme" width="128" height="24">
        </a>
        <p class="header__topic">
            micro blogging
        </p>
    </div>
    <form class="header__search-form form" style="visibility: hidden"  action="#" method="get">
        <div class="header__search">
            <label class="visually-hidden">Поиск</label>
            <input class="header__search-input form__input" type="search">
            <button class="header__search-button button" type="submit">
                <svg class="header__search-icon" width="18" height="18">
                    <use xlink:href="#icon-search"></use>
                </svg>
                <span class="visually-hidden">Начать поиск</span>
            </button>
        </div>
    </form>
    <div class="header__nav-wrapper">
        <nav class="header__nav">
            <ul class="header__my-nav" style="visibility: hidden " >
                <li class="header__my-page header__my-page--popular">
                    <a class="header__page-link" href="popular.html" title="Популярный контент">
                        <span class="visually-hidden">Популярный контент</span>
                    </a>
                </li>
                <li class="header__my-page header__my-page--feed">
                    <a class="header__page-link" href="feed.html" title="Моя лента">
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
                <li class="header__authorization">
                    <a class="header__user-button header__authorization-button button" href="login.html">Вход</a>
                </li>
                <li>
                    <a class="header__user-button header__user-button--active header__register-button button">Регистрация</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

