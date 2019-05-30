<form class="form container <?=$form_invalid; ?>" action="sign-up.php" method="post" autocomplete="off"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?=isset($error['email']) ? $invalid_field : '' ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?=$new_user['email'] ?? ''; ?>" placeholder="Введите e-mail">
        <span class="form__error">Введите e-mail</span>
    </div>
    <div class="form__item <?=empty($new_user['password']) ? $invalid_field : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?=$new_user['password'] ?? ''; ?>" placeholder="Введите пароль">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?=empty($new_user['name']) ? $invalid_field : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?=$new_user['name'] ?? ''; ?>" placeholder="Введите имя">
        <span class="form__error">Введите имя</span>
    </div>
    <div class="form__item <?=empty($new_user['message']) ? $invalid_field : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=$new_user['message'] ?? ''; ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>

    <?php if(isset($error)) : ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме:</span>
        <ul>
            <?php foreach ($error as $err => $val) : ?>
                <li><?=$val; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>


    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>