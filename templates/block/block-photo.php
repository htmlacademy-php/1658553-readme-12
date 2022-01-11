<?php
/* @var bool $isPost */

/* @var array $errors */

?>
<h2 class="visually-hidden">Форма добавления фото</h2>
<form class="adding-post__form form" action="add.php" method="post"
      enctype="multipart/form-data">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label"
                       for="photo-heading">Заголовок
                    <span class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['heading'])
                ): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input"
                           id="photo-heading" type="text"
                           name="heading" placeholder="Введите заголовок"
                           value="<?= getPostVal('heading'); ?>">
                    <button class="form__error-button button" type="button">
                        !<span
                            class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['heading'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="photoUrl">Ссылка
                    из
                    интернета</label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['photoUrl'])
                ): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="photoUrl"
                           type="text"
                           name="photoUrl" placeholder="Введите ссылку"
                           value="<?= getPostVal('photoUrl'); ?>">
                    <button class="form__error-button button" type="button">
                        !<span
                            class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['photoUrl'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label"
                       for="tags">Теги</label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['tags'])
                ): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="tags"
                           type="text"
                           name="tags" placeholder="Введите теги"
                           value="<?= getPostVal('tags'); ?>">
                    <button class="form__error-button button" type="button">
                        !<span
                            class="visually-hidden">Информация об ошибке</span>
                    </button>
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
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие
                    ошибки:</b>
                <ul class="form__invalid-list">
                    <?php
                    if (($errors['heading'])): ?>
                        <li class="form__invalid-item">Заголовок. <?php
                            print ($errors['heading']) ?>
                        </li>
                    <?php
                    endif; ?>
                    <?php
                    if (($errors['photoUrl'])): ?>
                        <li class="form__invalid-item">Ссылка из
                            интернета. <?php
                            print ($errors['photoUrl']) ?> Или прикрепите файл
                            изображения
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
        endif ?>
    </div>
    <div
        class="adding-post__input-file-container form__input-container form__input-container--file">
        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
            <div
                class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                <input class="adding-post__input-file form__input-file"
                       id="userpic-file-photo"
                       type="file" name="userpic-file-photo" title="">
                <div class="form__file-zone-text">
                    <span>Перетащите фото сюда</span>
                </div>
            </div>
            <button
                class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button"
                type="button">
                <span>Выбрать фото</span>
                <svg class="adding-post__attach-icon form__attach-icon"
                     width="10" height="20">
                    <use xlink:href="#icon-attach"></use>
                </svg>
            </button>
        </div>
        <div
            class="adding-post__file adding-post__file--photo form__file dropzone-previews">

        </div>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">
            Опубликовать
        </button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
</form>
