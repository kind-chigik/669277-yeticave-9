<form class="form container <?= !empty($error) ? 'form--invalid' : '' ?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= !empty($error['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= isset($user_enter['email']) ? htmlspecialchars($user_enter['email']) : ''; ?>"
               placeholder="Введите e-mail">
        <span class="form__error"><?= $error['email'] ?? ''; ?></span>
    </div>
    <div class="form__item form__item--last <?= !empty($error['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $error['password'] ?? ''; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
