<?php
$title = 'Créer un compte';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>

<form class="book taskDisplay" method="post">
    <legend><h2>To-do list</h2></legend>
    <label>
        <small>Nouvelle tâche</small><br>
        <input autofocus type="text" name="taskTitle" minlength="3">
        <button tabindex="0"  type="submit" name="addTask" title="Ajouter une tâche">ajouter</button>
    </label>

    <?php foreach ($_SESSION["tasks"] as $id => $name) : ?>
        <label class="task">
            <input type="checkbox" name="task[]" value="<?= htmlspecialchars($id) ?>" />
            <?= htmlspecialchars($name) ?>
            <button tabindex="1" class="delete" type="submit" name="removeTask" value="<?= htmlspecialchars($id) ?>" title="Supprimer la tâche">X</button>
        </label>
        <br>
    <?php endforeach; ?>
</form>




<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>