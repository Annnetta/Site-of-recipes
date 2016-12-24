<?php

//cette page et bonne

if(isset($_POST['titreR'])) {

/*RECETTE (idR, titreR, categorieR, descriptionR, nb_personnesR, #emailUser, Date)*/

$titreR =addslashes(htmlspecialchars($_POST['titreR']));
$categorieR=addslashes(htmlspecialchars($_POST['categorieR']));
$descriptionR=addslashes(htmlspecialchars($_POST['descriptionR']));
$nbPersonnesR=addslashes(htmlspecialchars($_POST['nbPersonnesR']));
$datecejour=date('Y-m-d'); 


    // Vérification que l'utilisateur n'a pas rentré n'importe quoi et mesures de sécurité sur ce qui a été rentré
	if (!(ctype_alnum ($titreR) and ($categorieR)  and ($descriptionR) and ctype_digit($nbPersonnesR))) {
		echo "Au moins une des valeurs rentrée est invalide";
		}
		
		else {
			//on cree notre recette 	
			$requete = "INSERT INTO Recette (titreR, categorieR, descriptionR, nbPersonnesR, Date) 
			VALUES ('$titreR', '$categorieR', '$descriptionR', '$nbPersonnesR', '$datecejour');";
		
			//echo "<br>".$requete."<br>";//-> affiche le requete
			mysqli_query($connexion, $requete);
			
			// Vérification de la bonne création de la recette
			$requete = "SELECT * FROM Recette WHERE titreR='$titreR';";
            $resultat = mysqli_query($connexion,$requete);
            if($resultat == FALSE) {
                echo "<p>Erreur : problème d'exécution de la requête de vérification de la création d'ingrédients</p>";
                } else {
                    if (mysqli_num_rows($resultat) == 0) {
                        echo "<p>Erreur : Recette non créé suite à une erreur inconnue</p>";
                    } else { // Pas de problème jusque là et recette bien créé
					
							$requete1="SELECT idR FROM Recette WHERE titreR='$titreR' AND categorieR='$categorieR' 
							AND descriptionR='$descriptionR' AND nbPersonnesR='$nbPersonnesR' AND Date='$datecejour';";
							$resultat = mysqli_query($connexion, $requete1);
							$row = mysqli_fetch_assoc($resultat);
							$id=$row['idR'];
							//echo $id;
							
							
							
							
                        echo "<p>Confirmation de l'ajout de la recette : $titreR </p>";
						echo "<h4>Vous avez saisi:</h4>";
						echo $titreR;
						echo "<br>";
						echo $categorieR;
						echo "<br>";
						echo $descriptionR;
						echo "<br>";
						echo $nbPersonnesR;
						echo "<br>";
						
	?>
	
	<form method="POST" action="index.php?page=saisie_recette_p2.php" >			
	<!--<input type="submit" value="Continuer"  formaction="index.php?page=saisie_recette_p2.php?idR="/>-->
	<input type="submit" value="Continuer"  />
	<input type="hidden" name="idR"  value="<?php echo $id;?>"/>
	</form>
	
	
	
	<?php
			
		
			
							}
						}
		}
}
		
            
     
 else { // Aucun formulaire n'a été envoyé : on l'affiche

?>

 <div id="form_ingredients"> 
	<fieldset> 
	<legend> Saisie d'une recette </legend>
	<form name="forma" action="index.php?page=saisie_recette_p1.php" method="post"/>
	<br>

	<div class="champ">
	Nom de la recette: <input type="text" name="titreR"></div>

	<div class="champ">
		Categorie de la recette: 
	  <input type="radio" name="categorieR" value="entree"/>Entrée
	  <input type="radio" name="categorieR" value="plat"/>Plat
	  <input type="radio" name="categorieR" value="amuse bouche"/>Amuse bouche
	  <input type="radio" name="categorieR" value="dessert"/>Dessert
	  <input type="radio" name="categorieR" value="boisson"/>Boisson
	  <input type="radio" name="categorieR" value="fromage"/>Fromage
	 </div>

	<div class="champ"> 
		Description:
		<label>
			  <textarea name="descriptionR" style="display:block;width:400px;height:100px" placeholder="Ecrivez la description de votre recette"></textarea>
		</label>
	</div>


	<div class="champ"> 
		Pour combien de personnes:<input type="number" name="nbPersonnesR" min="1"/>
	</div>

	<div class="champ"> 
		<input type="hidden" name="idR"/>
	</div>

	
	
	<div class="champ"> 
		<input type="reset" name="Reset" value="Supprimer tout"/> 
		<input type="submit" value="Continuer"/> 
	</div>
		
		
		
	</form>
	</fieldset> 
	</div>
<?php
}
?>



