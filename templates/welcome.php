<?php
$title = 'Bienvenue !';
ob_start(); 
?>
<style>
  @import url(style.css);
</style>
<section>
<h1>Bienvenue sur Ebisu !</h1>
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