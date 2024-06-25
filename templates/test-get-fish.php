<?php
$title = 'Get Fish';
$style ='@import url(public/css/style.css);';
ob_start();
?>
<form method="post">
  <fieldset>
    <legend>Get Fish</legend>
    <label>
        <small id="emailHint">Clic to get a fish</small>
    </label>
    <input type="submit" name="getFish"value="getFish"/>
  </fieldset>
</form>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>
