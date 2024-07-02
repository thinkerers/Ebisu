<?php
$title = 'To-do list';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>

<div class="book">
<h2><?= $title ?></h2>
    <form method="post" class="taskDisplay">
        
        <!-- affichage des tâches -->
        <?php foreach ($data['tasks'] as $id => $name) {
            echo '
            <div class="task">
                <div>
                    <input type="checkbox" id="task" name="task"  />
                    <label for="task">' . htmlspecialchars($name) . '</label>
                </div>
                <button class="delete" type="submit" name="removeTask" title="Supprimer la tâche"
                value='. htmlspecialchars($id) .'>X</button>
            </div>
            ';
        }?>
    
    </form>
    
    <!-- formulaire de nouvelle tâche -->
    <form method="post" class="taskForm">
        <fieldset id="taskContainer">
            <ul class="createToDo">
                <li>
                    <!-- <small>Titre de la tâche</small>--></br> 
                    <input autofocus minlength="3" type="text" name="taskTitle" required></br>
                
                    <!-- <small>Description de la tâche</small></br>
                    <textarea name="taskDescription" required></textarea></br> -->
                </li>
            </ul>
            
            <button type="submit" name="addTask" title="Ajouter une tache">+</button>
        </fieldset>
        
        <!-- <input type="submit" name="taskSubmit" value="valider">  -->
    </form>
</div>
<?php
$content = ob_get_clean();
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/layout.php');
?>