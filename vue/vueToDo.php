<?php require_once dirname(dirname(__FILE__)) .'/model/dbConnect.php';

// Session
if (!isset($_SESSION)) {
    session_start();
}


// Form answer
if (isset($_POST["taskSubmit"])) {
    foreach ($_POST['task'] as $entry => $task) {
        try {
            echo '<hr/>';
            print_r($task);
            echo '<hr/>';
            // Préparer la requête SQL
            $db = new dbConnect();

            $stmt = $db->prepare('INSERT INTO tasks (name, priority, urgency, description) VALUES (:name, :priority, :urgency, :description)');

            // bind
            $stmt->bindValue(':name', $task['title']);
            $stmt->bindValue(':priority', $task['priority']??'');
            $stmt->bindValue(':urgency', $task['urgency']??'');
            $stmt->bindValue(':description', $task['description']??'');

            // Exécuter la requête
            return $stmt->execute();
        } catch (Exception $e) {
            // Gérer l'erreur (par exemple, la journaliser)
            return false; 
        }
    }
}

// Form
else { ?>
<script defer src="toDo.js"></script>
    <h1>To-Do List</h1>
    <!-- new task form -->
    <form action="vue/vueToDo.php" method="post" class="taskForm">

        <fieldset id="taskContainer">
            <ul>
                <li class="task">
                    <fieldset>
                        <small>Titre de la tâche</small><br>
                        <input type="text" name="task[0][title]" required><br>
                        <label for="urgent-0">Urgent</label>
                        <input type="radio" name="task[0][urgency]" id="urgent-0" value="urgent"><br>
                        <label for="notUrgent-0">Not urgent</label>
                        <input type="radio" name="task[0][urgency]" id="notUrgent-0" value="notUrgent"><br>
                        <label for="important-0">Important</label>
                        <input type="radio" name="task[0][importance]" id="important-0" value="important"><br>
                        <label for="notImportant-0">Not important</label>
                        <input type="radio" name="task[0][importance]" id="notImportant-0" value="notImportant"><br>
                        <small>Description de la tâche</small><br>
                        <textarea name="task[0][description]"></textarea><br>
                        <button type="button" class="removeTaskBtn" title="Supprimer la tâche">Supprimer</button>
                    </fieldset>
                </li>
            </ul>
            <button type="button" id="addTaskBtn" title="Ajouter une tâche">+</button>
        </fieldset>

        <input type="submit" name="taskSubmit" value="valider">
    </form>
<?php
}
?>

<!-- srcipt -->
