//fonction pour changer la couleur du pixel de rgb à hex
function rgbToHex(rgbString) {
  var rgbArray = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  if (!rgbArray) return rgbString;

  function hex(x) {
    return ("0" + parseInt(x).toString(16)).slice(-2);
  }

  return "#" + hex(rgbArray[1]) + hex(rgbArray[2]) + hex(rgbArray[3]);
}

//////////////////////////////////////////////////////////////////////////////////////////

// Fonction pour afficher la fenêtre pop-up
function showPopup() {
  document.getElementById("popup-overlay").classList.add("show");
}

// Fonction pour masquer la fenêtre pop-up
function hidePopup() {
  document.getElementById("popup-overlay").classList.remove("show");
}

//////////////////////////////////////////////////////////////////////////////////////////

//fonction pour afficher connexion ou deconnexion selon le cas
function affDeconnexion() {
  var val = document.getElementById("mess");
  if (val.innerHTML == "Se connecter") val.innerHTML = "se déconnecter";
  else val.innerHTML = "se connecter";
}

//////////////////////////////////////////////////////////////////////////////////////////

i;
const pixelTable = document.getElementById("grille");

function rgb2hex(rgb) {
  if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

  function hex(x) {
    return ("0" + parseInt(x).toString(16)).slice(-2);
  }
  return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

//fnction pour obtenir la chaines des couleurs du smiley (de la grille)
function obtenirChaineCouleurs() {
  var tableau = document.getElementById("grille");
  var cellules = tableau.getElementsByTagName("td");
  var chaine = Math.sqrt(cellules.length) + ",";

  for (let i = 0; i < cellules.length; i++) {
    var couleur = window
      .getComputedStyle(cellules[i])
      .getPropertyValue("background-color");
    couleur = rgb2hex(couleur);
    chaine += couleur + ",";
  }
  return chaine.substring(0, chaine.length - 1);
}

//fonction pour convertir la chaine des couleurs en tableau
function conversion_chaine($couleurs) {
  // Explode la chaîne de couleurs en un tableau en utilisant la virgule comme séparateur
  $couleursArray = explode(",", $couleurs);
  // Nettoie le tableau des éventuels espaces vides ou valeurs vides
  $couleursArray = array_filter(array_map("trim", $couleursArray));
  // Retourne le tableau des couleurs
  return $couleursArray;
}

function transforme() {
  var chaine = obtenirChaineCouleurs();
  $.ajax({
    url: "templates/modifier_couleurs.php",
    type: "GET",
    data: {
      chaine: chaine,
    },
    success: function (data) {
      console.log(data);
      // Diviser la chaîne en utilisant "Colors received:" comme point de division
      const parts = data.split("Colors received:");

      // Si la chaîne a été divisée en au moins deux parties
      if (parts.length >= 2) {
        // Prendre la deuxième partie et la nettoyer
        const dataClean = parts[1].trim();
        console.log(dataClean); // Affiche uniquement la partie désirée

        var tableau = dataClean.split(",");
        console.log(tableau);
        // Div pour contenir le tableau
        var divTableContainer = $("<div>").addClass("table-container");

        var largeurGrille = parseInt(tableau[0]); // Largeur de la grille
        var couleurs = tableau.slice(1); // Liste des couleurs

        // Création du tableau HTML
        var table = $("<table>").addClass("smiley-table");
        console.log(largeurGrille);
        for (var i = 0; i < largeurGrille; i++) {
          var row = $("<tr>");
          for (var j = 0; j < largeurGrille; j++) {
            var color = couleurs[i * largeurGrille + j];
            var td = $("<td>");
            // Création de la div avec la classe "pixel" et le style de couleur de fond
            var divInsideTd = $("<div>").addClass("pixel").css({
              backgroundColor: color,
            });
            td.append(divInsideTd);
            row.append(td);
          }
          table.append(row);
        }

        // Ajout du tableau dans la div de conteneur
        divTableContainer.append(table);

        // Ajout de la div de conteneur à l'élément avec la classe ".lessmiley"
        $(".lessmiley").append(divTableContainer);
      }
    },
  });
}

//fonction pour enregistrer le smiley en format png
function enregistrePNG(taille) {
  convertToImageAndDownload(obtenirChaineCouleurs(), taille);
}

//fonction pour convertir la chaine des couleurs en image png et la télécharger
function convertToImageAndDownload(chaineCouleurs, taille) {
  var largeur = Math.sqrt(chaineCouleurs.split(",").length - 1); // Obtenez la largeur de l'image depuis la chaîne
  var canvas = document.createElement("canvas");
  var tailleCanvas = 500;

  if (taille === "petit") {
    tailleCanvas = 100;
  } else if (taille === "grand") {
    tailleCanvas = 1000;
  }

  var ratio = tailleCanvas / largeur; // Calculer le ratio de redimensionnement
  var tailleImage = largeur * ratio;

  canvas.width = tailleImage;
  canvas.height = tailleImage;
  var context = canvas.getContext("2d");

  // Parcourir les couleurs et les appliquer aux pixels du canvas
  var couleurs = chaineCouleurs.split(",");
  couleurs = couleurs.slice(1); // Ignorer la première valeur (la largeur)
  for (var i = 0; i < couleurs.length; i++) {
    var x = (i % largeur) * ratio;
    var y = Math.floor(i / largeur) * ratio;
    var couleur = couleurs[i];
    couleur = couleur.replace("#", "");
    couleur = "#" + couleur; // Rétablir la notation hexadécimale
    context.fillStyle = couleur;
    context.fillRect(x, y, ratio, ratio); // Redimensionner le dessin du pixel
  }

  // Créer une image à partir du canvas
  var image = canvas.toDataURL("image/png");

  // Créer un lien pour télécharger l'image
  var a = document.createElement("a");
  a.href = image;
  a.download = "image_" + Date.now() + ".png";
  a.click();
}
