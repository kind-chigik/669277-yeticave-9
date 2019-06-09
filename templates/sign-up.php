<form class="form container <?= !empty($error) ? 'form--invalid' : '' ?>" action="sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= !empty($error['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= htmlspecialchars($new_user['email']) ?? ''; ?>"
               placeholder="Введите e-mail">
        <span class="form__error"><?= $error['email'] ?? ''; ?></span>
    </div>
    <div class="form__item <?= !empty($error['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?= !empty($error['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars($new_user['name']) ?? ''; ?>" placeholder="Введите имя">
        <span class="form__error">Введите имя</span>
    </div>
    <div class="form__item <?= !empty($error['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message"
                  placeholder="Напишите как с вами связаться"><?= htmlspecialchars($new_user['message']) ?? ''; ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>

    <?php if (isset($error)) : ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме:</span>
        <ul>
            <?php foreach ($error as $err => $val) : ?>
                <li><?= $val; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>


    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>