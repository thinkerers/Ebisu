<?php
$title = 'Bienvenue !';
$style ='@import url(public/css/pomodoro.css);';

if(!isset($_SESSION['pomodoro-start'])){
    $_SESSION['pomodoro-duration'] = 25 * 60;
    $_SESSION['pomodoro-start'] = time();
}

if (isset($_SESSION['pomodoro-start'])) {
  $elapsed = time() - $_SESSION['pomodoro-start'];
  $duration = $_SESSION['pomodoro-duration'];
  $timeLeft = $duration - $elapsed;
  $Hh = floor($timeLeft / 3600);
  $Mm = floor(($timeLeft % 3600) / 60);
  $Ss = $timeLeft % 60;


  if ($timeLeft <= 0) {
      unset($_SESSION['pomodoro-start'], $_SESSION['pomodoro-duration']);
      $Hh = $Mm = $Ss = 0; 
  }
} else {
  $Hh = $Mm = $Ss = 0; 
}

[$H, $h] = [(int) floor($Hh / 10), (int) $Hh % 10];
[$M, $m] = [(int) floor($Mm / 10), (int) $Mm % 10];
[$S, $s] = [(int) floor($Ss / 10), (int) $Ss % 10];

$duration = $_SESSION['pomodoro-duration'] ?? 0;

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