<?php
/* Création de la requête */
if(isset($_POST['motcle'])) // Si un mot clé est rentré, la requête est crée selon ce mot clé
{
    $motcle = addslashes($_POST['motcle']);
    if (ctype_alpha($motcle)) { // Le mot clé ne doit contenir que des caractères
        $requete = "SELECT titreR FROM Recette WHERE titreR LIKE '%$motcle%' ORDER BY titreR ASC LIMIT 50";
    } else {
        echo "le mot clé saisie est invalide";
        $requete = 'SELECT titreR FROM Recette ORDER BY titreR ASC LIMIT 50 ;';
    }

} else { // Sinon, on fait la requête normale

    $requete = 'SELECT titreR FROM Recette ORDER BY titreR ASC LIMIT 50 ;';
}

/* Exécution de la requête et affichage */
$resultat = mysqli_query($connexion, $requete);

if($resultat == FALSE) {
    printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");
} else {
    if(mysqli_num_rows($resultat) == 0) { // aucun résultat
        echo "<p>Aucune recette n'a pas été trouvé.</p>";
    }
    else { // au moins un résultat
        echo "Liste des recettes trouvées : <p><ul>";
        while ($row = mysqli_fetch_assoc($resultat)) { // boucle sur chaque n-uplet
            echo "<h1>";
			echo $row['titreR'];
			echo "</h1>";
			echo("<br>");
			
			//Recuperation des autres donnees concernant la recette choisie:
			$requete="SELECT idR FROM `Recette` WHERE titreR='".$row['titreR']."';";//on choisi tous les id avec le titre cherche
			$result = mysqli_query($connexion, $requete);
			if($result == FALSE) {
				 printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");}
				 else{
					 if(mysqli_num_rows($result) == 0){echo "cette recette n'a pas id<br";}
					 else{	//au moins une recette
						while ($ligne = mysqli_fetch_assoc($result)) {
						echo "Pourquoi choisir cette recette?<br>";
						$requete="SELECT idR, descriptionR, nbPersonnesR FROM Recette WHERE idR='".$ligne['idR']."'  LIMIT 5;";
						//echo "<br>".$requete."<br>";
						$result = mysqli_query($connexion, $requete);
						if($result == FALSE) {printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");}
						
						else{
							while ($ligne = mysqli_fetch_assoc($result)) {
							echo($ligne['descriptionR']);
							echo("<br>");
							echo "c'est une recette pour ";
							echo ($ligne['nbPersonnesR']);
							echo " personne(s)<br>";
							
							
							
						$requete1="SELECT idR, numeroEt, nomInstr, descriptionInstr FROM `Instruction` WHERE idR='".$ligne['idR']."' ORDER BY numeroEt ASC;";
							//echo "<br>".$requete1."<br>";
						$id=$ligne['idR'];
						//echo $id;
						$result1 = mysqli_query($connexion, $requete1);
						if($result1 == FALSE) {printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");}
						
						else{
							while ($ligne1 = mysqli_fetch_assoc($result1)) {
						echo "<h3>";
						echo "Etape ";
						echo ($ligne1['numeroEt']);
						echo " ";
						echo ($ligne1['nomInstr']);
						echo "</h3>";
						echo "Instructions pour cette etape: <br>";
						echo ($ligne1['descriptionInstr']);
						
						echo "<br>";
						}
						
						$requete="SELECT idR, nomIngr, quantite, unite FROM `SeCompose` WHERE idR='".$ligne['idR']."';";
						
						//echo "<br>".$requete."<br>";
						$result = mysqli_query($connexion, $requete);
						if($result == FALSE) {printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");}
						else{
							echo "<h3>";
							echo "Vous avez besoin de : ";
							echo "</h3>";
							while ($ligne = mysqli_fetch_assoc($result)) {
								echo ($ligne['nomIngr']);
								echo " - ";
								echo ($ligne['quantite']);
								echo " ";
								echo ($ligne['unite']);
								echo "<br>";
						}}}
						
						
						

						$requete1="SELECT idR, nomProd, quantite, unite FROM `EstCompose` WHERE idR=$id;";
						//echo "<br>".$requete1."<br>";
						$result1 = mysqli_query($connexion, $requete1);
						if($result1 == FALSE) {printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");}
						else{
							echo "<h3>";
							echo "et des produits : ";
							echo "</h3>";
							while ($ligne1 = mysqli_fetch_assoc($result1)) {
								echo ($ligne1['nomProd']);
								echo " - ";
								echo ($ligne1['quantite']);
								echo " ";
								echo ($ligne1['unite']);
								
								echo "<br>";
								
							
						
							
						}
						}
						echo "<hr>";
							}
						}
						}
					 }
				 }
		}
						
			
						
						     echo "</ul></p>";
    
    mysqli_free_result($resultat);
	
					 }
}
				 
			
			
        
   



/* Affichage du formulaire pour rechercher un ingrédient via mot clé*/
?>
<form method="post" action="index.php?page=affichage_recettes.php">
    <label for="recherche">Recherche par mot-clé</label>
    <input type="text" name="motcle" id="recherche" /><br />
    <input type="submit" name="valider" value="Rechercher" />
</form>


