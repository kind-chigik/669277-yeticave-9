<section class="lot-item container">
    <h2><?= htmlspecialchars($lot['name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['cat_name']); ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($is_auth && $rate_not_current_user && $time_lot_not_end && ($lot['user_id'] !== $user_id)): ?>
                <div class="lot-item__state">
                    <div class="lot__timer timer <?= less_hour_left($lot['end_time']) ? 'timer--finishing' : '' ?>">
                        <?= time_before_end($lot['end_time']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= formatting_amount($current_price); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= $min_rate; ?> р</span>
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
                <h3>История ставок (<span><?= isset($count_rate) ? $count_rate : 0; ?></span>)</h3>
                <?php if (isset($lot_rate)) : ?>
                <table class="history__list">
                    <?php foreach ($lot_rate as $rate): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= htmlspecialchars($rate['name']); ?></td>
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
