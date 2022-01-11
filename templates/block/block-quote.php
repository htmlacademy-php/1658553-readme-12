<?php
/* @var bool $isPost */

/* @var array $errors */

?>
<h2 class="visually-hidden">Форма добавления цитаты</h2>
<form class="adding-post__form form" action="add.php?content-type=2"
      method="post">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label"
                       for="quote-heading">Заголовок
                    <span class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['heading'])
                ): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input"
                           id="quote-heading" type="text"
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
            <div class="adding-post__input-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="citeText">Текст
                    цитаты <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['citeText'])
                ): print 'form__input-section--error';
                endif; ?>">
                                            <textarea
                                                class="adding-post__textarea adding-post__textarea--quote form__textarea form__input"
                                                id="citeText" name="citeText"
                                                placeholder="Текст цитаты"><?= getPostVal(
                                                    'citeText'
                                                ); ?></textarea>
                    <button class="form__error-button button" type="button">
                        !<span
                            class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['citeText'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="quoteAuthor">Автор
                    <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['quoteAuthor'])
                ): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input"
                           id="quoteAuthor" type="text"
                           name="quoteAuthor"
                           value="<?= getPostVal('quoteAuthor'); ?>">
                    <button class="form__error-button button" type="button">
                        !<span
                            class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?php
                            print $errors['quoteAuthor'] ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="cite-tags">Теги</label>
                <div class="form__input-section <?php
                if ($isPost
                    && ($errors['tags'])
                ): print 'form__input-section--error';
                endif; ?>">
                    <input class="adding-post__input form__input" id="cite-tags"
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
                    if (($errors['citeText'])): ?>
                        <li class="form__invalid-item">Текст цитаты. <?php
                            print ($errors['citeText']) ?></li>
                    <?php
                    endif; ?>
                    <?php
                    if (($errors['quoteAuthor'])): ?>
                        <li class="form__invalid-item">Автор. <?php
                            print ($errors['quoteAuthor']) ?>
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
        <button class="adding-post__submit button button--main" type="submit">
            Опубликовать
        </button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
</form>
