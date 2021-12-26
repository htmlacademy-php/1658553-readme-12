<?php
/* @var bool $isPost */

/* @var array $errors */
?>
<h2 class="visually-hidden">Форма добавления ссылки</h2>
<form class="adding-post__form form" action="add.php?content-type=5" method="post">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="link-heading">Заголовок <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost && ($errors['heading'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="link-heading" type="text"
                           name="heading" placeholder="Введите заголовок"
                           value="<?= getPostVal('heading'); ?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['heading'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="post-link">Ссылка <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost && ($errors['link-ref'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="post-link" type="text"
                           name="link-ref" value="<?= getPostVal('link-ref'); ?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['link-ref'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="link-tags">Теги</label>
                <div class="form__input-section <?php
                if ($isPost && ($errors['tags'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="link-tags" type="text"
                           name="tags" placeholder="Введите теги"
                           value="<?= getPostVal('tags'); ?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['tags'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($isPost && findErrors($errors)): ?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php
                    if (($errors['heading'])): ?>
                        <li class="form__invalid-item">Заголовок. <?php
                            print ($errors['heading']) ?>
                        </li>
                    <?php
                    endif; ?>
                    <?php
                    if (($errors['link-ref'])): ?>
                        <li class="form__invalid-item">Ссылка. <?php
                            print ($errors['link-ref']) ?>
                        </li>
                    <?php
                    endif; ?>
                    <?php
                    if (($errors['tags'])): ?>
                        <li class="form__invalid-item">Теги. <?php
                            print ($errors['tags']) ?>
                        </li>
                    <?php
                    endif; ?>
                </ul>
            </div>
        <?php
        endif; ?>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать
        </button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
</form>
