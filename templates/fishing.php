<?php
$title = 'Fishing';
$style ='@import url(public/css/style.css);@import url(public/css/fishing.css);';
ob_start();
?>
<main>
<form method="post">
    <button type="submit" name="getFish">Pêcher un poisson</button>
</form>


<table class="fishes">
  <tbody>
    <?php for ($row = 0; $row < 4; $row++) { ?>
      <tr>
        <?php for ($col = 0; $col < 9; $col++) {
          $cellFishId = $row * 10 + $col;
          $caught = ($data['fish']->fishId ?? null) === $cellFishId;
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
