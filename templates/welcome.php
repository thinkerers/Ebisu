<?php
$title = 'Bienvenue !';
ob_start(); 
?>
<style>
  @import url(style.css);
  @import url(pomodoro.css);
</style>
<section>
<h1>Bienvenue sur Ebisu !</h1>

<div class="pomodoro">
  <fieldset class="edit-timer">
    <fieldset name="Hh">
      <select name="H">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
      </select>
      <select name="h">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
      </select>
    </fieldset>
    <fieldset name="Mm">
      <select name="M">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
      <select name="m">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
      </select>
    </fieldset>
    <fieldset name="Ss">
      <select name="S">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3" selected="selected">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
      <select name="s">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
      </select>
    </fieldset>
  </fieldset>
  <time datetime="2018-07-07">
    <output class="Ss"><output class="Mm"><output class="Hh"></output></output></output>
  </time>
  <form>
    <menu>
      <button type="reset" title="reset">🔄</button>
      <input type="checkbox" name="play" title="toggle state" checked>
    <select name="speed">
          <option value="1">x1</option>
          <option value="10">x10</option>
          <option value="100">x100</option>
          <option value="1000">x1000</option>
        </select>
    </menu>
  </form>
</div>


<div class="pomodoro"style="
--minutes:<?=$_SESSION['min']??false;?>;
--heures:<?=$_SESSION['hour']??false;?>;
">
<form method="post">
<input type="time" name="setTime" id="time" value="00:00">
<input type="submit" name="action" value="startPomodoro"/>
</form>
<?= var_dump($_SESSION);?>
</div>
</section>

<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>