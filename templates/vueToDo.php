<?php
$title = 'Créer un compte';
$style ='@import url(public/css/form.css);';
ob_start(); 
?>
<!-- <script defer src="public/js/toDo.js"></script> -->
<!-- <h2>Tâches à réaliser :</h2> -->

<div class="book">
    <form method="post" class="taskDisplay">
        <legend><h2>To-do list</h2></legend>
        
        <!-- affichage des tâches -->
        <?php foreach ( $_SESSION["tasks"] as $row){
            echo '
            <div class="task">
                <div>
                    <input type="checkbox" id="task" name="task"  />
                    <label for="task">' . $row . '</label>
                </div>
                <button class="delete" type="submit" name="removeTask" title="Supprimer la tâche"
                value='. $row .'>X</button>
            </div>
            ';
        }?>
    
    </form>
    
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