<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if (isset($bets)): ?>
        <table class="rates__list">
            <?php foreach ($bets as $bet): ?>
                <tr class="rates__item
            <?= (count_time($bet['end_time']) < 1 && $bet['winner_id'] === $user_id) ? 'rates__item--win' : ''; ?>
            <?= (count_time($bet['end_time']) < 1 && $bet['winner_id'] !== $user_id) ? 'rates__item--end' : ''; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= $bet['image']; ?>" width="54" height="40" alt="<?= $bet['name']; ?>">
                        </div>
                        <h3 class="rates__title"><a href="lot.php?id=<?= $bet['id'] ?>"><?= $bet['name']; ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?= $bet['cat_name']; ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer
                    <?= (count_time($bet['end_time']) < 1 && $bet['winner_id'] !== $user_id) ? 'timer--end' : ''; ?>
                    <?= (count_time($bet['end_time']) < 1 && $bet['winner_id'] === $user_id) ? 'timer--win' : ''; ?>
                    <?= (count_time($bet['end_time']) >= 1 && count_time($bet['end_time']) < 3600) ? 'timer--finishing' : ''; ?>">
                            <?= (count_time($bet['end_time']) < 1 && $bet['winner_id'] === $user_id) ? 'Ставка выиграла' : ''; ?>
                            <?= (count_time($bet['end_time']) < 1 && $bet['winner_id'] !== $user_id) ? 'Торги окончены' : ''; ?>
                            <?= (count_time($bet['end_time']) >= 1) ? gmdate("d:H:i",
                                count_time($bet['end_time'])) : ''; ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= $bet['amount']; ?>
                    </td>
                    <td class="rates__time">
                        <?= $bet['creation_time']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
