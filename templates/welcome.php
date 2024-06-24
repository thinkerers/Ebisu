<?php
$title = 'Bienvenue !';
$style = '@import url(public/css/pomodoro.css);';
$pomodoro = new src\lib\Pomodoro();
// Start pomodoro if not already started
if (!isset($_SESSION['pomodoro-start'])) {
  $pomodoro->start(25*60);
}

if (isset($pomodoro)) {
  $duration = $_SESSION['pomodoro-duration'];
  // Get pomodoro time values
  [$H, $h, $M, $m, $S, $s, $elapsed] = $pomodoro->getTime($_SESSION['pomodoro-start'], $_SESSION['pomodoro-duration']);

  // Destroy pomodoro if time is up
  if (time() - $_SESSION['pomodoro-start'] >= $_SESSION['pomodoro-duration']) {
    $pomodoro->destroy();
  }
}

ob_start();
?>
<style>
  @import url(public/css/style.css);
  @import url(public/css/pomodoro.css);
  /* @view-transition {
    navigation: auto;
} */
</style>
<section>
<h1>Bienvenue sur Ebisu !</h1> 

<form 
  name="pomodoro"
  method="post"
  style="
  --duration:<?=$duration?>;
  --elapsed:<?=$elapsed?>
  "
>
<fieldset name="Hh:Mm:Ss">
      <fieldset name="Hh">
        <select name="H">
          <?php for($i=0;$i<3;$i++): ?>
            <option value="<?=$i?>" <?= $i === $H ? 'selected="selected"' : '';?> ><?=$i?></option>
          <?php endfor; ?>
        </select>
        <select name="h">
          <?php for($i=0;$i<10;$i++): ?>
            <option value="<?=$i?>" <?= $i === $h ? 'selected="selected"' : '';?> ><?=$i?></option>
          <?php endfor; ?>
        </select>
      </fieldset>
      <fieldset name="Mm">
        <select name="M">
          <?php for($i=0;$i<6;$i++): ?>
            <option value="<?=$i?>" <?= $i === $M ? 'selected' : '';?> ><?=$i?></option>
          <?php endfor; ?>
        </select>
        <select name="m">
          <?php for($i=0;$i<10;$i++): ?>
            <option value="<?=$i?>" <?= $i === $m ? 'selected' : '';?> ><?=$i?></option>
          <?php endfor; ?>
        </select>
      </fieldset>
      <fieldset name="Ss">
        <select name="S">
          <?php for($i=0;$i<6;$i++): ?>
            <option value="<?=$i?>" <?= $i === $S ? 'selected' : '';?> ><?=$i?></option>
          <?php endfor; ?>
        </select>
        <select name="s">
          <?php for($i=0;$i<10;$i++): ?>
            <option value="<?=$i?>" <?= $i === $s ? 'selected' : '';?> ><?=$i?></option>
          <?php endfor; ?>
        </select>
      </fieldset>
    </fieldset>
  <input type="checkbox" name="play" checked>
</form>

</div>
</section>

<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>