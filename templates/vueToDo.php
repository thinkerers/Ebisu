<?php
$title = 'Créer un compte';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<div class="book">
    <form method="post" class="taskDisplay">
        <legend><h2>To-do list</h2></legend>
        <?php
foreach ($_SESSION["tasks"] as $id => $name) {
    echo '
        <label class="task">
            <input type="checkbox" name="task[]" value="' . $id . '" />
            ' . htmlspecialchars($name) . '
            <button class="delete" type="submit" name="removeTask" value="' . $id . '" title="Supprimer la tâche">X</button>
        </label>
    ';
}
?>
    </form>

    <style>
        .book{
            overflow-y: scroll;
        }
        .task{
            display: grid;
            grid-auto-flow: column;
            align-content: center;
        }
        [type="checkbox"]{
            height: 2lh;
            aspect-ratio: 1;
            margin:0;
        }
    </style>
    
    <!-- formulaire de nouvelle tâche -->
    <form method="post" class="taskForm">
        <fieldset id="taskContainer">
            <ul class="createToDo">
                <li>
                    <small>Titre de la tâche</small></br>
                    <input type="text" name="taskTitle" required></br>
                
                    <!-- <small>Description de la tâche</small></br>
                    <textarea name="taskDescription" required></textarea></br> -->
                </li>
            </ul>
            
            <button type="submit" name="addTask" title="Ajouter une tache">ajouter</button>
        </fieldset>
        
        <!-- <input type="submit" name="taskSubmit" value="valider">  -->
    </form>
</div>


<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>