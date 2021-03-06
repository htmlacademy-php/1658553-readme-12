<?php
/* @var array $contentTypes */

/* @var null|int $contentType */
/* @var string $blockContent */
/* @var string $className */


?>

<div class="page__main-section">
    <div class="container">
        <h1 class="page__title page__title--adding-post">Добавить
            публикацию</h1>
    </div>
    <div class="adding-post container">
        <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
                <ul class="adding-post__tabs-list filters__list tabs__list">
                    <li class="adding-post__tabs-item filters__item">
                        <a class="adding-post__tabs-link filters__button filters__button--photo <?php
                        if ($contentType === $contentTypes[TYPE_PHOTO]['id']
                            or is_null($contentType)
                        ) {
                            print "filters__button--active";
                        } ?>  tabs__item tabs__item--active button"
                           href="?content-type=<?= $contentTypes[TYPE_PHOTO]['id'] ?>">
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                            <span>Фото</span>
                        </a>
                    </li>
                    <li class="adding-post__tabs-item filters__item">
                        <a class="adding-post__tabs-link filters__button filters__button--video tabs__item button <?php
                        if ($contentType === $contentTypes[TYPE_VIDEO]['id']) {
                            print "filters__button--active";
                        } ?>"
                           href="?content-type=<?= $contentTypes[TYPE_VIDEO]['id'] ?>">
                            <svg class="filters__icon" width="24" height="16">
                                <use xlink:href="#icon-filter-video"></use>
                            </svg>
                            <span>Видео</span>
                        </a>
                    </li>
                    <li class="adding-post__tabs-item filters__item">
                        <a class="adding-post__tabs-link filters__button filters__button--text tabs__item button <?php
                        if ($contentType === $contentTypes[TYPE_TEXT]['id']) {
                            print "filters__button--active";
                        } ?>"
                           href="?content-type=<?= $contentTypes[TYPE_TEXT]['id'] ?>">
                            <svg class="filters__icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                            <span>Текст</span>
                        </a>
                    </li>
                    <li class="adding-post__tabs-item filters__item">
                        <a class="adding-post__tabs-link filters__button filters__button--quote tabs__item button <?php
                        if ($contentType === $contentTypes[TYPE_QUOTE]['id']) {
                            print "filters__button--active";
                        } ?>"
                           href="?content-type=<?= $contentTypes[TYPE_QUOTE]['id'] ?>">
                            <svg class="filters__icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                            <span>Цитата</span>
                        </a>
                    </li>
                    <li class="adding-post__tabs-item filters__item">
                        <a class="adding-post__tabs-link filters__button filters__button--link tabs__item button <?php
                        if ($contentType === $contentTypes[TYPE_LINK]['id']) {
                            print "filters__button--active";
                        } ?>"
                           href="?content-type=<?= $contentTypes[TYPE_LINK]['id'] ?>">
                            <svg class="filters__icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                            <span>Ссылка</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="adding-post__tab-content">

                <section class="<?= $className ?> tabs__content">
                    <?= $blockContent ?>
                </section>
            </div>
        </div>
    </div>
</div>


