<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= isset($winner_name) ? htmlspecialchars($winner_name) : ''; ?></p>
<p>Ваша ставка для лота <a href="http://yetycave/lot.php?id=<?= isset($lot_id) ? $lot_id : ''; ?>"><?= isset($lot_name) ? htmlspecialchars($lot_name) : ''; ?></a> победила.</p>
<p>Перейдите по ссылке <a href="http://yetycave/my-bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>