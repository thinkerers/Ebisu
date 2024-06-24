<?php
$title = 'Bienvenue !';
$style ='@import url(public/css/pomodoro.css);';

if(!isset($_SESSION['pomodoro-start'])){
    $_SESSION['pomodoro-duration'] = 25 * 60;
    $_SESSION['pomodoro-start'] = time();
}

if (isset($_SESSION['pomodoro-start'])) {
  $timeLeft = $_SESSION['pomodoro-duration'] - (time() - $_SESSION['pomodoro-start']);
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

[$H, $h] = [(int) floor($Hh??0 / 10), (int) $Hh??0 % 10];
[$M, $m] = [(int) floor($Mm??0 / 10), (int) $Mm??0 % 10];
[$S, $s] = [(int) floor($Ss??0 / 10), (int) $Ss??0 % 10];

$duration = $_SESSION['pomodoro-duration'] ?? 0;

ob_start();

?>
<style>
  @import url(public/css/style.css);
  @import url(public/css/pomodoro.css);

  @view-transition {
    navigation: auto;
}
</style>
<section>
<h1>Bienvenue sur Ebisu !</h1> 

<form 
name="pomodoro"
method="post"
style="
  --Hh:<?=$Hh?>;
  --Mm:<?=$Mm?>;
  --Ss:<?=$Ss?>;
  --duration:<?=$duration?>
">
<!-- Note:
 
The pomodoro is not updated when paused, the time displayed  need to be updated : refresh the page when the user pause the pomodoro, or use css to display the correct value.-->
  <time>
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
    <output class="Ss"><output class="Mm"><output class="Hh"></output></output></output>
  </time>
  <menu>
    <input type="checkbox" name="play" checked>
  </menu>
</form>

<!-- refresh page with button without js -->
<form method="post">
  <button type="submit" name="refresh">refresh</button>
</form>

<code>
  <h2>Debug</h2>
  <pre>
  <?= var_dump($_SESSION);?>
  </pre>
</code>
</div>
</section>

<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>