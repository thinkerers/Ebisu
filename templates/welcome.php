<?php
$title = 'Bienvenue !';
$style ='@import url(public/css/pomodoro.css);';
ob_start(); 
?>
<style>
  @import url(public/css/style.css);
  @import url(public/css/pomodoro.css);
</style>
<section>
<h1>Bienvenue sur Ebisu !</h1>


<form name="pomodoro" method="post">
  <time style="
  --Hh:<?=$_SESSION['hour']??'0';?>;
  --Mm:<?=$_SESSION['min']??'0';?>;
  ">
    <fieldset name="Hh:Mm:Ss">
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
    <output class="Ss"><output class="Mm"><output class="Hh"></output></output></output>
  </time>
  <menu>
    <button type="reset">🔄</button>
    <input type="checkbox" name="play" checked>
  </menu>
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