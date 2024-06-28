<?php
$title = 'Fishing';
$style ='@import url(public/css/style.css);@import url(public/css/fishing.css);';

ob_start();
?>
<main><!-- Temporary form to test the fishing -->
<form method="post">
  <fieldset>
    <legend>Get Fish</legend>
    <label>
        <small id="emailHint">Clic to get a fish</small>
    </label>
    <input type="submit" name="getFish"/>
  </fieldset>
</form>

ID of the fish caught: <?= $data['fish']->fishId ?? '' ?>

<!-- Display the fish caught -->
<div class="fish">
  <img src="public/img/fish/<?= $data['fish']->rarity ?>-<?= $data['fish']->variant ?>.png" alt="fish">
  <p><?= $data['fish']->rarity ?> <?= $data['fish']->variant ?></p>


<!-- Display a table of all the fish caught -->
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

<style>[data-fish-count]::after{content:attr(data-fish-count);}</style>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>
