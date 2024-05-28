<?php include("head.php"); ?>

<h1>To-Do List</h1>

<!-- formulaire de nouvelle tâche -->
<script defer src="toDo.js"></script>
<form action="self" method="post" class="taskForm">
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

<!-- affichage des tâches -->
<h2>Tâches à réaliser :</h2>

<?php include("footer.php") ?>