<?php
/* @var bool $isPost */

/* @var array $errors */

?>
<h2 class="visually-hidden">Форма добавления текста</h2>
<form class="adding-post__form form" action="add.php?content-type=1" method="post">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="text-heading">Заголовок <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost && !is_bool($errors['heading'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="text-heading" type="text"
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
            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="post-text">Текст поста <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost && !is_bool($errors['text-content'])): print 'form__input-section--error';
                endif; ?>">
                                            <textarea class="adding-post__textarea form__textarea form__input"
                                                      id="post-text" name="text-content"
                                                      placeholder="Введите текст публикации"><?= getPostVal(
                                                    'text-content'
                                                ); ?></textarea>
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['text-content'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="tags">Теги</label>
                <div class="form__input-section <?php
                if ($isPost && !is_bool($errors['tags'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="tags" type="text"
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
                    if (!is_bool($errors['heading'])): ?>
                        <li class="form__invalid-item">Заголовок. <?php
                            print ($errors['heading']) ?></li>
                    <?php
                    endif; ?>
                    <?php
                    if (!is_bool($errors['text-content'])): ?>
                        <li class="form__invalid-item">Текст поста. <?php
                            print ($errors['text-content']) ?></li>
                    <?php
                    endif; ?>
                    <?php
                    if (!is_bool($errors['tags'])): ?>
                        <li class="form__invalid-item">Теги. <?php
                            print ($errors['tags']) ?></li>
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
