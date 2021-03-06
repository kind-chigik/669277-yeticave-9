<div class="container">
    <section class="lots">
        <h2><?= isset($search_title) ? htmlspecialchars($search_title) : ''; ?></h2>
        <?php if (isset($lots)): ?>
            <ul class="lots__list">
                <?php foreach ($lots as $key => $val): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $val['image']; ?>" width="350" height="260" alt="<?= htmlspecialchars($val['name']); ?>">
                        </div>
                        <div class="lot__info">
                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($val['cat_lot']); ?></span>
                                <h3 class="lot__title"><a class="text-link"
                                                          href="lot.php?id=<?= $val['id']; ?>"><?= htmlspecialchars($val['name']); ?></a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= formatting_amount($val['start_price']); ?></span>
                                    </div>
                                    <div class="lot__timer timer <?= less_hour_left($val['end_time']) ? 'timer--finishing' : '' ?>">
                                        <?= time_before_end($val['end_time']); ?>
                                    </div>
                                </div>
                            </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
    <?php if (!empty($lots) && isset($pages) && count($pages) > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <?php if ($current_page > 1): ?>
                    <a href="?search=<?= $search ?? ''; ?>&page=<?= $current_page - 1 ?? ''; ?>">Назад</a>
                <?php endif; ?>
            </li>

            <?php foreach ($pages as $page): ?>
                <li class="pagination-item <?= ($page === $current_page) ? 'pagination-item-active' : ''; ?>">
                    <a href="?search=<?= $search ?? ''; ?>&page=<?= $page ?? ''; ?>"><?= $page ?? ''; ?></a>
                </li>
            <?php endforeach; ?>

            <li class="pagination-item pagination-item-next">
                <?php if ($current_page < count($pages)): ?>
                    <a href="?search=<?= $search ?? ''; ?>&page=<?= $current_page + 1 ?? ''; ?>">Вперед</a>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>
</div>