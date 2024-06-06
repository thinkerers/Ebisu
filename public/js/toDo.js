

//On récupère le bouton d'ajout de task par son ID
const addTaskBtn = document.getElementById("addTaskBtn");

//On récupère le container de task par son ID
const taskContainer = document.getElementById("taskContainer");

// Template pour chaque élément dans le container de to do
const taskTemplate = `
    <li>
        <small>Titre de la tâche</small></br>
        <input type="text" name="taskTitleInput" required></br>
    
        <small>Description de la tâche</small></br>
        <textarea name="taskDescriptionInput" required></textarea></br>
    
        <button type="button" class="removeTaskBtn" title="Supprimer la tâche">Supprimer</button>
    </li>
`;

// On ajoute un écouteur de click sur les boutons de suppression de chaque élément de liste
taskContainer.addEventListener("click", ({ target }) => {

    // L'évènement est déconstruit pour garder la target (cible), si la cible match le sélecteur css qui stipule que le bouton ne peut pas être dans un li unique, alors on continue
    if (target.matches("li .removeTaskBtn")) {
        
        // On prend le li le plus proche de la cible du click, et on le supprime de l'html
        target.closest("li").remove();
    }
});

// On ajoute un écouteur de click sur le bouton qui ajoute des task. Ici on a pas besoin de savoir ou le click a été fait donc on laisse la parenthèse vide.
addTaskBtn.addEventListener("click", (e) => {

    //querySelector permet de récupérer le premier enfant de taskContainer qui match le sélecteur css indiqué. On utilise la méthode insertAdjacentHTML pour insérer l'élément de liste, avec l'option 'beforeend' pour qu'il soit juste avant le tag fermant, donc en fin de liste.
    taskContainer
    .querySelector("ul")
    .insertAdjacentHTML("beforeend", taskTemplate);
});
