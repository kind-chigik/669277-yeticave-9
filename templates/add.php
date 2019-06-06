<form class="form form--add-lot container <?= !empty($error) ? 'form--invalid' : '' ?>" action="add.php" method="post"
      enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= !empty($error['lot-name']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" value="<?= $lot['lot-name'] ?? ''; ?>"
                   placeholder="Введите наименование лота">
            <span class="form__error"><?= $error['lot-name'] ?? ''; ?></span>
        </div>
        <div class="form__item <?= !empty($error['category']) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= $error['category'] ?? ''; ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= !empty($error['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message"
                  placeholder="Напишите описание лота"><?= $lot['message'] ?? ''; ?></textarea>
        <span class="form__error"><?= $error['message'] ?? ''; ?></span>
    </div>
    <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="lot-img" id="lot-img" value="">
            <label for="lot-img">
                Добавить
            </label>
            <span class="form__error">Загрузите изображение в формате: jpg, jpeg, png</span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= !empty($error['lot-rate']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" value="<?= $lot['lot-rate'] ?? ''; ?>" placeholder="0">
            <span class="form__error"><?= $error['lot-rate'] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--small <?= !empty($error['lot-step']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" value="<?= $lot['lot-step'] ?? ''; ?>" placeholder="0">
            <span class="form__error"><?= $error['lot-step'] ?? ''; ?></span>
        </div>
        <div class="form__item <?= !empty($error['lot-date']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов<sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $lot['lot-date'] ?? ''; ?>">
            <span class="form__error"><?= $error['lot-date'] ?? ''; ?></span>
        </div>
    </div>

    <?php if (isset($error)) : ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме:</span>
        <ul>
            <?php foreach ($error as $err => $val) : ?>
                <li><?= $val; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>