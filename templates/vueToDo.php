<?php
$title = 'To-do list';
$style ='@import url(public/css/form.css);';
ob_start(); 
function taskID($id){ return htmlspecialchars($id); } ?>

<div class="book">
<h2><?= $title ?></h2>
    <form method="post" class="taskDisplay">
        
        <!-- affichage des tâches -->
        <?php foreach ($data['tasks'] as $id => $keys) {
            $name = $keys['name'];

            echo '
            <div class="task">
                <label for="task">
                    <input type="submit" id="task" name="toggleTask" value="'
                    . taskID($id) .'" /> 
                     <span class="taskSpan">'. htmlspecialchars($name) . '</span>
                </label>
                
                <div class="taskType">
                    <input type="checkbox" name="urgent">
                    <input type="checkbox" name="important">
                    <button class="delete" type="submit" name="removeTask" title="Supprimer la tâche"
                    value='. taskID($id) .'>X</button>
                </div>
            </div>
            ';
        }?>
    
    </form>
    
    <!-- formulaire de nouvelle tâche -->
    <form method="post" class="taskForm">
        <fieldset id="taskContainer">
            <ul class="createToDo">
                <li>
                    <!-- <small>Titre de la tâche</small></br>  -->
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