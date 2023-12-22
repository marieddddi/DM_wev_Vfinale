//element croix pour supprimer un smiley
var jCroix = $("<div>").append(
  '<span class="croix ui-icon ui-icon-closethick">'
);

$(document).ready(function () {
  var couleurModifiable = false;
  var color;
  var selectedColors = [];
  $(".color").each(function () {
    selectedColors.push($(this).css("background-color"));
  });
  console.log(selectedColors);
  // Cacher la liste des choix de couleurs au chargement de la page
  $("#choix_couleurs").hide();
  $("#validercouleur").hide();

  // Gestion du clic sur le bouton "Modifier"
  $("#changercouleur").click(function () {
    console.log("modif");
    // Afficher ou masquer la liste des choix de couleurs
    couleurModifiable = true;
    $("#choix_couleurs").show();
    $("#changercouleur").hide();
    $("#validercouleur").show();
  });

  // Gestion du clic sur le bouton "Valider"
  $("#validercouleur").click(function () {
    console.log("valider");
    // Afficher ou masquer la liste des choix de couleurs
    $("#choix_couleurs").hide();
    $("#validercouleur").hide();
    $("#changercouleur").show();
    couleurModifiable = false;
    var hexColors = selectedColors.map(function (color) {
      return rgbToHex(color);
    });

    var colorsString = hexColors.join(",");

    $.ajax({
      type: "GET",
      url: "templates/modifier_couleurs.php", // URL du script PHP pour la mise à jour en base de données
      data: { colors: colorsString }, // Envoyer les couleurs sous forme de paramètre
      success: function (response) {
        console.log("Couleurs mises à jour en base de données !");
        console.log(colorsString);
      },
    });
  });

  // Gestion du clic sur une couleur de la liste de choix
  $(".color").click(function () {
    if (couleurModifiable === true) {
      $(".coulcoul").click(function () {
        color = $(this).css("background-color"); // Récupérer la couleur de fond de l'élément cliqué
      });
      $(this).css("background-color", color);
      var index = $(".color").index(this);
      selectedColors[index] = color;
      console.log(selectedColors);
    }
  });

  //si notre souris survole un smiley
  $(document).on("mouseover", ".table-container", function () {
    // Apparition de la croix
    console.log("mouseover");
    ele = $(this).data("affichage");
    idSmiley = $(this).data("id");
    ElemeAsupp = $(this);
    jCroix.insertAfter($(this));
  });

  //si on clique sur la croix
  $(document).on("click", ".croix", function () {
    // Suppression de la table-container
    console.log("click");
    //recup l'id de la table
    console.log("Affichage récupéré : ", ele);
    ElemeAsupp.hide();
    chaineSupp = ele.join(",");
    console.log("chaineSupp", chaineSupp);
    $.ajax({
      type: "GET",
      url: "templates/modifier_couleurs.php", // URL du script PHP pour la mise à jour en base de données
      data: { affichage: chaineSupp }, // Envoyer les couleurs sous forme de paramètre
      success: function (response) {
        console.log("Smiley supprimé en base de données !");
      },
    });
  });

  let tableauClique = false; // Variable pour suivre si le tableau a été cliqué ou non

  $(document).on("click", "#DupliquerSmiley", function () {
    if (!tableauClique) {
      // Si le tableau n'a pas encore été cliqué
      $("#choix").html("Veuillez cliquer sur le smiley à dupliquer");
      tableauClique = true; //variable à true pour indiquer que le tableau a été cliqué
    }
  });

  $(document).on("click", ".table-container", function () {
    if (tableauClique) {
      // Si le tableau a déjà été cliqué
      $("#choix").html("");
      chaine = $(this).data("affichage");
      var chaineDup = chaine.join(",");
      $("#chaineCouleurs").val(chaine);
      console.log(chaineDup);
      $("#formChaine").submit();
      tableauClique = false; // Réinitialisez la variable pour la prochaine fois
      $.ajax({
        type: "POST",
        url: "templates/modifier_couleurs.php", // URL du script PHP pour la mise à jour en base de données
        data: { dup: chaineDup }, // Envoyer les couleurs sous forme de paramètre
        success: function (response) {
          console.log("Smiley dupliqué sur l'acceuil !");
          //on change de view et on va sur l'accueil
          window.location.href = "index.php";
        },
      });
    }
  });
});
