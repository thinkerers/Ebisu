<?php
$title = 'Fishing';
$style ='@import url(public/css/style.css);@import url(public/css/fishing.css);';
ob_start();
?>
<main>
<form method="post">
  <fieldset>
    <legend>Get Fish</legend>
    <label>
        <small id="emailHint">Clic to get a fish</small>
    </label>
    <input type="submit" name="getFish"/>
  </fieldset>
</form>

<h2>Fish caught</h2>

<ul>
    <li><?= $data['fish']->fishId ?? '' ?></li>
    <li><?= $data['fish']->variant ?? '' ?></li>
    <li><?= $data['fish']->rarity ?? ''  ?></li>
</ul>


<table class="fishes">
  <tbody>
    <?php for ($row = 0; $row < 4; $row++) { ?>
      <tr>
        <?php for ($col = 0; $col < 9; $col++) {
          $cellFishId = $row * 10 + $col;
          $caught = ($data['fish']->fishId === $cellFishId);
          $discovered = isset($_SESSION['discoveredFishes'][$cellFishId]);
        ?>
          <td
            class="<?= $caught ? 'caught' : ''; ?> <?= $discovered ? 'discovered' : ''; ?>"
            title="<?= $cellFishId; ?>"
            data-fish-count="<?= $_SESSION['discoveredFishes'][$cellFishId] ?? 0; ?>">🐟
          </td>
        <?php } ?>
      </tr>
    <?php } ?>
  </tbody>
</table>
</main>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>
