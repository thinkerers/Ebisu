document.getElementById("addTaskBtn").addEventListener("click", function () {
  var taskContainer = document
    .getElementById("taskContainer")
    .getElementsByTagName("ul")[0];
  var taskCount = taskContainer.getElementsByClassName("task").length;

  var newTask = document.createElement("li");
  newTask.className = "task";
  newTask.innerHTML = `
            <fieldset>
                <small>Titre de la tâche</small><br>
                <input type="text" name="task[${taskCount}][title]" required><br>
                <label for="urgent-${taskCount}">Urgent</label>
                <input type="radio" name="task[${taskCount}][urgency]" id="urgent-${taskCount}" value="urgent"><br>
                <label for="notUrgent-${taskCount}">Not urgent</label>
                <input type="radio" name="task[${taskCount}][urgency]" id="notUrgent-${taskCount}" value="notUrgent"><br>
                <label for="important-${taskCount}">Important</label>
                <input type="radio" name="task[${taskCount}][priority]" id="important-${taskCount}" value="important"><br>
                <label for="notImportant-${taskCount}">Not important</label>
                <input type="radio" name="task[${taskCount}][priority]" id="notImportant-${taskCount}" value="notImportant"><br>
                <small>Description de la tâche</small><br>
                <textarea name="task[${taskCount}][description]"></textarea><br>
                <button type="button" class="removeTaskBtn" title="Supprimer la tâche">Supprimer</button>
            </fieldset>
        `;

  taskContainer.appendChild(newTask);
});

document
  .getElementById("taskContainer")
  .addEventListener("click", function (e) {
    if (e.target.classList.contains("removeTaskBtn")) {
      e.target.closest("li").remove();
    }
  });