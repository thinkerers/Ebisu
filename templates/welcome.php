<?php
$title = 'Bienvenue !';
$style = '@import url(public/css/pomodoro.css);';

function startPomodoro(int $duration): void
{
    $_SESSION['pomodoro-start'] = time();
    $_SESSION['pomodoro-duration'] = $duration;
}

function destroyPomodoro(): void
{
    unset($_SESSION['pomodoro-start'], $_SESSION['pomodoro-duration']);
}

function getPomodoroTime(): array
{
    $Hh = $Mm = $Ss = 0;
    if (isset($_SESSION['pomodoro-start']) && isset($_SESSION['pomodoro-duration'])) {
        $elapsed = time() - $_SESSION['pomodoro-start'];
        $duration = $_SESSION['pomodoro-duration'];
        $timeLeft = max(0, $duration - $elapsed);
        $Hh = floor($timeLeft / 3600);
        $Mm = floor(($timeLeft % 3600) / 60);
        $Ss = $timeLeft % 60;
    }
    return [$Hh, $Mm, $Ss, $duration, $elapsed];
}

// Start pomodoro if not already started
if (!isset($_SESSION['pomodoro-start'])) {
    startPomodoro(25 * 60);
}

// Destroy pomodoro if time is up
if (isset($_SESSION['pomodoro-start']) && isset($_SESSION['pomodoro-duration'])) {
    if (time() - $_SESSION['pomodoro-start'] >= $_SESSION['pomodoro-duration']) {
        destroyPomodoro();
    }
}

function splitDigits(int $number): array
{
    $digits = array_map('intval', str_split("$number"));
    return count($digits) === 1 ? [0, $digits[0]] : $digits;
}

// Get pomodoro time
[$Hh, $Mm, $Ss, $duration, $elapsed] = getPomodoroTime();
[$H, $h] = splitDigits($Hh);
[$M, $m] = splitDigits($Mm);
[$S, $s] = splitDigits($Ss);


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