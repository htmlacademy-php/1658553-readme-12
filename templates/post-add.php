<?php
/* @var array $content_types */
/* @var null|int $content_type */

/* @var string $block_photo */
/* @var string $block_video */
/* @var string $block_text */
/* @var string $block_quote */
/* @var string $block_link */



?>
<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--photo <?php
                            if ($content_type === $content_types[TYPE_PHOTO]['id'] or is_null($content_type)) {
                                print "filters__button--active";
                            } ?>  tabs__item tabs__item--active button" href="?content-type=<?= $content_types[TYPE_PHOTO]['id'] ?>">
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-photo"></use>
                                </svg>
                                <span>Фото</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--video tabs__item button <?php
                            if ($content_type === $content_types[TYPE_VIDEO]['id']) {
                                print "filters__button--active";
                            } ?>" href="?content-type=<?= $content_types[TYPE_VIDEO]['id'] ?>">
                                <svg class="filters__icon" width="24" height="16">
                                    <use xlink:href="#icon-filter-video"></use>
                                </svg>
                                <span>Видео</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--text tabs__item button <?php
                            if ($content_type === $content_types[TYPE_TEXT]['id']) {
                                print "filters__button--active";
                            } ?>" href="?content-type=<?= $content_types[TYPE_TEXT]['id'] ?>">
                                <svg class="filters__icon" width="20" height="21">
                                    <use xlink:href="#icon-filter-text"></use>
                                </svg>
                                <span>Текст</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--quote tabs__item button <?php
                            if ($content_type === $content_types[TYPE_QUOTE]['id']) {
                                print "filters__button--active";
                            } ?>" href="?content-type=<?= $content_types[TYPE_QUOTE]['id'] ?>">
                                <svg class="filters__icon" width="21" height="20">
                                    <use xlink:href="#icon-filter-quote"></use>
                                </svg>
                                <span>Цитата</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--link tabs__item button <?php
                            if ($content_type === $content_types[TYPE_LINK]['id']) {
                                print "filters__button--active";
                            } ?>" href="?content-type=<?= $content_types[TYPE_LINK]['id'] ?>">
                                <svg class="filters__icon" width="21" height="18">
                                    <use xlink:href="#icon-filter-link"></use>
                                </svg>
                                <span>Ссылка</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <section class="adding-post__photo tabs__content <?php
                    if ($content_type === $content_types[TYPE_PHOTO]['id'] or is_null($content_type)) {
                        print "tabs__content--active";
                    } ?>">
                        <?= $block_photo ?>
                    </section>

                    <section class="adding-post__video tabs__content <?php
                    if ($content_type === $content_types[TYPE_VIDEO]['id']) {
                        print "tabs__content--active";
                    } ?>">
                        <?= $block_video ?>
                    </section>

                    <section class="adding-post__text tabs__content <?php
                    if ($content_type === $content_types[TYPE_TEXT]['id']) {
                        print "tabs__content--active";
                    } ?>">
                        <?= $block_text ?>
                    </section>

                    <section class="adding-post__quote tabs__content <?php
                    if ($content_type === $content_types[TYPE_QUOTE]['id']) {
                        print "tabs__content--active";
                    } ?>">
                        <?= $block_quote ?>
                    </section>

                    <section class="adding-post__link tabs__content <?php
                    if ($content_type === $content_types[TYPE_LINK]['id']) {
                        print "tabs__content--active";
                    } ?>">
                        <?= $block_link ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

