<?php
/* @var string $pageContent */

/* @var array $contentTypes */
/* @var null|string $currentType */


?>
<div class="container">
    <h1 class="page__title page__title--feed">Моя лента</h1>
</div>
<div class="page__main-wrapper container">
    <section class="feed">
        <h2 class="visually-hidden">Лента</h2>
        <div class="feed__main-wrapper">
            <div class="feed__wrapper">
                <?= $pageContent ?>
            </div>
        </div>
        <ul class="feed__filters filters">
            <li class="feed__filters-item filters__item">
                <a class="filters__button <?php
                if (is_null($currentType)) {
                    print "filters__button--active";
                } ?>" href="?content_type=">
                    <span>Все</span>
                </a>
            </li>
            <li class="feed__filters-item filters__item">
                <a class="filters__button filters__button--photo button <?php
                if ($currentType === $contentTypes[TYPE_PHOTO]['id']) {
                    print "filters__button--active";
                } ?>"
                   href="?content_type=<?= $contentTypes[TYPE_PHOTO]['id'] ?>">
                    <span class="visually-hidden">Фото</span>
                    <svg class="filters__icon" width="22" height="18">
                        <use xlink:href="#icon-filter-photo"></use>
                    </svg>
                </a>
            </li>
            <li class="feed__filters-item filters__item">
                <a class="filters__button filters__button--video button <?php
                if ($currentType === $contentTypes[TYPE_VIDEO]['id']) {
                    print "filters__button--active";
                } ?>"
                   href="?content_type=<?= $contentTypes[TYPE_VIDEO]['id'] ?>">
                    <span class="visually-hidden">Видео</span>
                    <svg class="filters__icon" width="24" height="16">
                        <use xlink:href="#icon-filter-video"></use>
                    </svg>
                </a>
            </li>
            <li class="feed__filters-item filters__item">
                <a class="filters__button filters__button--text button <?php
                if ($currentType === $contentTypes[TYPE_TEXT]['id']) {
                    print "filters__button--active";
                } ?>"
                   href="?content_type=<?= $contentTypes[TYPE_TEXT]['id'] ?>">
                    <span class="visually-hidden">Текст</span>
                    <svg class="filters__icon" width="20" height="21">
                        <use xlink:href="#icon-filter-text"></use>
                    </svg>
                </a>
            </li>
            <li class="feed__filters-item filters__item">
                <a class="filters__button filters__button--quote button <?php
                if ($currentType === $contentTypes[TYPE_QUOTE]['id']) {
                    print "filters__button--active";
                } ?>"
                   href="?content_type=<?= $contentTypes[TYPE_QUOTE]['id'] ?>">
                    <span class="visually-hidden">Цитата</span>
                    <svg class="filters__icon" width="21" height="20">
                        <use xlink:href="#icon-filter-quote"></use>
                    </svg>
                </a>
            </li>
            <li class="feed__filters-item filters__item">
                <a class="filters__button filters__button--link button <?php
                if ($currentType === $contentTypes[TYPE_LINK]['id']) {
                    print "filters__button--active";
                } ?>"
                   href="?content_type=<?= $contentTypes[TYPE_LINK]['id'] ?>">
                    <span class="visually-hidden">Ссылка</span>
                    <svg class="filters__icon" width="21" height="18">
                        <use xlink:href="#icon-filter-link"></use>
                    </svg>
                </a>
            </li>
        </ul>
    </section>
    <aside class="promo">
        <article class="promo__block promo__block--barbershop">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей
                франшизе!
            </p>
            <a class="promo__link" href="#">
                Подробнее
            </a>
        </article>
        <article class="promo__block promo__block--technomart">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Товары будущего уже сегодня в онлайн-сторе Техномарт!
            </p>
            <a class="promo__link" href="#">
                Перейти в магазин
            </a>
        </article>
        <article class="promo__block">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Здесь<br> могла быть<br> ваша реклама
            </p>
            <a class="promo__link" href="#">
                Разместить
            </a>
        </article>
    </aside>
</div>


