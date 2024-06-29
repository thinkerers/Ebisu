<?php
$title = 'To-do list';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>

<form class="book taskDisplay" method="post">
    <legend><h2><?= $title ?></h2></legend>
    <label class="addTask">
        <input autofocus type="text" name="taskTitle" minlength="3">
        <button tabindex="0"  type="submit" name="addTask" title="Ajouter une tâche">+</button>
    </label>

    <?php foreach ($data['tasks'] as $id => $name) : ?>
        <label class="task">
            <?= htmlspecialchars($name) ?>
            <button tabindex="1" class="delete" type="submit" name="removeTask" value="<?= htmlspecialchars($id) ?>" title="Supprimer la tâche">X</button>
        </label>
    <?php endforeach; ?>
</form>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>