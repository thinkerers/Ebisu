<?php
$title = 'Get Fish';
$style ='@import url(public/css/style.css);';
ob_start();
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<form method="post">
  <fieldset>
    <legend>Get Fish</legend>
    <label>
        <small id="emailHint">Clic to get a fish</small>
    </label>
    <input type="submit" name="getFish"value="getFish"/>
  </fieldset>
</form>

<table class="fishes" >
  <?php  for($row=0; $row<6; $row++){ ?>
  <tr>
    <?php for($col=0; $col<6; $col++) { ?>
      <td class="material-symbols-outlined" data-fish-id="<?= $row*6 + $col ?>">set_meal</td>
    <?php } ?>
  </tr>
  <?php }?>
</table>

<style>
  .fishes {
    border-collapse: collapse;
    width: 100%;
  }

  .fishes td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
  }

  .fishes tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  .fishes tr:hover {
    background-color: #f5f5f5;
  }
</style>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>
