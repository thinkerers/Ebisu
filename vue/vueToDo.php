<?php include("head.php"); ?>

    <h1>To-Do List</h1>
    
    <!-- formulaire de nouvelle tâche -->
    <script src="./vue/toDo.js"></script>
    <form action="?" method="post" class="toDoForm">
        <small>Titre de la tâche</small></br>
        <input type="text" name="toDoTitleInput" required></br>

        <small>Description de la tâche</small></br>
        <textarea name="toDoDescriptionInput" required></textarea></br>

        <button type="button" class="removeSectionBtn" title="Supprimer la section"></button>

        <button type="button" id="addSectionBtn" title="Ajouter une section"></button>


        <input type="submit" name="toDoSubmit"vvalue="valider"> 
    </form>
    
    <!-- affichage des tâches -->
    <h2>Tâches à réaliser :</h2>

<?php include("footer.php") ?>