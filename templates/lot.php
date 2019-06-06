<section class="lot-item container">
    <h2><?= $lot['name']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['cat_name']; ?></span></p>
            <p class="lot-item__description"><?= $lot['description']; ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($_SESSION['user'] && ($lot['user_id'] != $user_id) && (count_time($lot['end_time']) > 1)): ?>
                <div class="lot-item__state">
                    <div class="lot__timer timer <?= less_hour_left($lot['end_time']) ? 'timer--finishing' : '' ?>">
                        <?= time_before_end($lot['end_time']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= $current_price; ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= $min_rate; ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?= !empty($error) ? 'form__item--invalid' : ''; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?= $min_rate; ?>">
                            <span class="form__error"><?= $error['cost'] ?? ''; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= count($lot_rate) ?? 0; ?></span>)</h3>
                <?php if ($lot_rate) : ?>
                <table class="history__list">
                    <?php foreach ($lot_rate as $rate): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= $rate['name']; ?></td>
                            <td class="history__price"><?= $rate['amount']; ?></td>
                            <td class="history__time"><?= $rate['creation_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
