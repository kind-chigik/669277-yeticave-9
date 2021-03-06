<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span><?= isset($cat_name) ? htmlspecialchars($cat_name) : ''; ?></span></h2>
        <?php if (isset($all_lots)) : ?>
            <ul class="lots__list">
                <?php foreach ($all_lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $lot['image']; ?>" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= isset($lot['cat_name']) ? htmlspecialchars($lot['cat_name']) : ''; ?></span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php?id=<?= $lot['id']; ?>"><?= htmlspecialchars($lot['name']); ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= formatting_amount($lot['start_price']); ?></span>
                                </div>
                                <div class="lot__timer timer <?= less_hour_left($lot['end_time']) ? 'timer--finishing' : '' ?>">
                                    <?= time_before_end($lot['end_time']); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
    <?php if (!empty($all_lots) && isset($pages) && count($pages) > 1) : ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <?php if ($current_page > 1): ?>
                <a href="all-lots.php?id=<?= $cat_id; ?>&page=<?= $current_page - 1; ?>">Назад</a></li>
                <?php endif; ?>

            <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?= ($page === $current_page) ? 'pagination-item-active' : ''; ?>">
                    <a href="all-lots.php?id=<?= $cat_id; ?>&page=<?= $page; ?>"><?= $page; ?></a>
            </li>
            <?php endforeach; ?>

            <li class="pagination-item pagination-item-next">
                <?php if ($current_page < count($pages)): ?>
                <a href="all-lots.php?id=<?= $cat_id; ?>&page=<?= $current_page + 1; ?>">Вперед</a></li>
                <?php endif; ?>
        </ul>
    <?php endif; ?>
</div>
