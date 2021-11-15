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
                if ($isPost && !empty($errors['text-heading'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="text-heading" type="text"
                           name="text-heading" placeholder="Введите заголовок"
                           value="<?= getPostVal('text-heading'); ?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['text-heading'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="post-text">Текст поста <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost && !empty($errors['text-content'])): print 'form__input-section--error';
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
                <label class="adding-post__label form__label" for="post-tags">Теги</label>
                <div class="form__input-section <?php
                if ($isPost && !empty($errors['text-tags'])): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="post-tags" type="text"
                           name="text-tags" placeholder="Введите теги"
                           value="<?= getPostVal('text-tags'); ?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['text-tags'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($isPost && count($errors) > 0): ?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php
                    if (!empty($errors['text-heading'])): ?>
                        <li class="form__invalid-item">Заголовок. <?php
                            print ($errors['text-heading']) ?></li>
                    <?php
                    endif; ?>
                    <?php
                    if (!empty($errors['text-content'])): ?>
                        <li class="form__invalid-item">Текст поста. <?php
                            print ($errors['text-content']) ?></li>
                    <?php
                    endif; ?>
                    <?php
                    if (!empty($errors['text-tags'])): ?>
                        <li class="form__invalid-item">Теги. <?php
                            print ($errors['text-tags']) ?></li>
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
