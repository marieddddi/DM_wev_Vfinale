<?php
include_once("libs/modele.php");
include_once("libs/maLibForms.php");
include_once("libs/maLibSecurisation.php");
include_once("libs/maLibUtils.php");



// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "index.php") {
	header("Location:../index.php?view=accueil");
	die("");
}

?>

<!-- on inclut les fichiers nécessaires -->
<script src="js/jquery-3.7.1.js"></script>
<script src="js/mon_compte.js"></script>
<link href="js/jquery-ui.min.css" rel="stylesheet" />
<script src="js/jquery-ui.min.js"></script>

<body>
	<div id="letout">

		<h1 id="palete">Ma palette de couleurs</h1>
		<div id="liste">
			<!-- Liste des couleurs pour la palette -->
			<?php
			// Affichage du tableau de couleurs récupéré
			
			valider('connecte', 'SESSION');
			$couleurs = palette_mon_compte($_SESSION['idUser']);
			$palette = conversion_chaine($couleurs);
			foreach ($palette as $p) {
				echo "<div class='color' style='background-color:" . $p . ";'></div>";
			}
			?>
		</div>

		<input type="button" value="Modifier" id="changercouleur" class="custom-button" />
		<input type="button" value="Valider" id="validercouleur" class="custom-button" />

		<div id="choix_couleurs">
			<!-- Liste des couleurs pour le choix -->

			<div class="coulcoul" style="background-color: #ff0000;"></div>
			<div class="coulcoul" style="background-color: #00ff00;"></div>
			<div class="coulcoul" style="background-color: #0000ff;"></div>
			<div class="coulcoul" style="background-color: #ffff00;"></div>
			<div class="coulcoul" style="background-color: #ff00ff;"></div>
			<div class="coulcoul" style="background-color: #00ffff;"></div>
			<div class="coulcoul" style="background-color: #000000;"></div>
			<div class="coulcoul" style="background-color: #ffffff;"></div>
			<div class="coulcoul" style="background-color: #ff8000;"></div>
			<div class="coulcoul" style="background-color: #ff0080;"></div>
			<div class="coulcoul" style="background-color: #80ff00;"></div>
			<div class="coulcoul" style="background-color: #8000ff;"></div>
			<div class="coulcoul" style="background-color: #0080ff;"></div>
			<div class="coulcoul" style="background-color: #00ff80;"></div>
			<div class="coulcoul" style="background-color: #abcdef;"></div>
			<div class="coulcoul" style="background-color: #fedcba;"></div>
			<div class="coulcoul" style="background-color: #aabbcc;"></div>
			<div class="coulcoul" style="background-color: #ccbbaa;"></div>
			<div class="coulcoul" style="background-color: #ccbbcc;"></div>
			<div class="coulcoul" style="background-color: #aaccbb;"></div>
			<div class="coulcoul" style="background-color: #bbaacc;"></div>
			<div class="coulcoul" style="background-color: #3faccc;"></div>
			<div class="coulcoul" style="background-color: #ccbbcc;"></div>
			<div class="coulcoul" style="background-color: #ccbbaa;"></div>
			<div class="coulcoul" style="background-color: #aabbcc;"></div>
			<div class="coulcoul" style="background-color: #fedcba;"></div>
			<div class="coulcoul" style="background-color: #abcdef;"></div>
			<div class="coulcoul" style="background-color: #ab0c50;"></div>
			<div class="coulcoul" style="background-color: #ab0a20;"></div>
			<div class="coulcoul" style="background-color: #ffa500;"></div>
			<div class="coulcoul" style="background-color: #a0522d;"></div>
			<div class="coulcoul" style="background-color: #ff4500;"></div>
			<div class="coulcoul" style="background-color: #da70d6;"></div>
			<div class="coulcoul" style="background-color: #ff69b4;"></div>
			<div class="coulcoul" style="background-color: #9370db;"></div>
			<div class="coulcoul" style="background-color: #32cd32;"></div>
			<div class="coulcoul" style="background-color: #20b2aa;"></div>
			<div class="coulcoul" style="background-color: #4682b4;"></div>
			<div class="coulcoul" style="background-color: #d3d3d3;"></div>
			<input type="file" id="uploadImage" accept="image/*">
		</div>



	</div>

	<!-- On affiche les smileys de l'utilisateur -->
	<h1 id="smileys">Mes smileys</h1>
	<div id="compte_ensemble">
		<?php
		// Affichage du tableau de smileys récupéré
		
		$smileys = smileys_mon_compte($_SESSION['idUser']);
		foreach ($smileys as $s) {
			foreach ($s as $valeur) {
				$affichage = conversion_chaine($valeur);
				echo "<div class='table-container' data-affichage='" . json_encode($affichage) . "'>";
				$taille = $affichage[0];
				$nb = 0;
				echo "<table class='smiley-table'>"; // Ouvre un tableau
				for ($i = 0; $i < $taille; $i++) {
					echo "<tr>"; // Nouvelle ligne pour chaque itération  
					for ($j = 0; $j < $taille; $j++) {
						echo "<td>";
						echo "<div class='pixel' style='background-color:" . $affichage[$nb + 1] . ";'></div>";
						echo "</td>";
						$nb++;
					}
					echo "</tr>"; // Ferme la ligne après avoir ajouté les éléments 
				}
				echo "</table>"; // Ferme le tableau à la fin de chaque itération 
				echo "</div>"; // Ferme le conteneur pour les tableaux
			}
		}

		?>

	</div>
	<!-- bouton pour dupliquer un smiley (traiter en js)-->
	<input type="button" value="Dupliquer" id="DupliquerSmiley" class="nw-button" />
	<div id='choix'></div>
</body>



<?php
//fonction pour convertir la chaine de couleurs en tableau
function conversion_chaine($couleurs)
{
	// Explode la chaîne de couleurs en un tableau en utilisant la virgule comme séparateur
	$couleursArray = explode(',', $couleurs);

	// Nettoie le tableau des éventuels espaces vides ou valeurs vides
	$couleursArray = array_filter(array_map('trim', $couleursArray));

	// Retourne le tableau des couleurs
	return $couleursArray;
}
?>