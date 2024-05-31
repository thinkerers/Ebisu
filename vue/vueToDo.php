<?php

    // Form answear
    if(isset($_POST["taskSubmit"])){

        //  form data
        $taskTitleInput = $_POST["taskTitleInput"];
        $taskDescriptionInput = $_POST["taskDescriptionInput"];
        $taskSubmit = $_POST["taskSubmit"];

        // Send data to database
        foreach($taskTitleInput as $taskTitle){
            // connect db
            // new entry in table
            // prepare
            // execute
        }

        // display tasks
            // connect db
            // Select from table
                // prepare
                // fetch
            
            // loop each fetch
                // echo

    }

    // Form
    else{ include("head.php"); ?>

        <h1>To-Do List</h1>
        <!-- new task form -->
        <form action="vueToDo.php" method="post" class="taskForm">
            
            <fieldset id="taskContainer">
                <ul >
                    <li>
                        <small>Titre de la tâche</small></br>
                        <input type="text" name="taskTitleInput" required></br>
                    
                        <small>Description de la tâche</small></br>
                        <textarea name="taskDescriptionInput" required></textarea></br>
                        
                        <button type="button" class="removeTaskBtn" title="Supprimer la tâche">Supprimer</button>
                    </li>
                </ul>
                
                <button type="button" id="addTaskBtn" title="Ajouter une tache">+</button>
            </fieldset>
            
            <input type="submit" name="taskSubmit" value="valider"> 
        </form>
        <?php 
    } 

// footer
include("footer.php") ?> 

<!-- srcipt -->
<script src="toDo.js"></script>