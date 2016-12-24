<!-- pour saisir les ingredients-->


<?php
//SeCompose(#idR, #nomIngr, quantite, #unite)


$idR=addslashes(htmlspecialchars($_POST["idR"]));
//echo"variable id est bien ici";
//echo $idR;
?>

<h4>Si vous ne trouvez pas l'ingredient, merci de l'ajouter via la forme Saisie Ingrédients sur notre site</h4>

<?php

if(isset($_POST['ingred'])) { // Si un formulire a été envoyé : inserer l'ingrédient dans la table

    
	
    $nomIngr = addslashes(htmlspecialchars($_POST['ingred']));
    $quantite = addslashes(htmlspecialchars($_POST['quantite']));
	$unite = addslashes(htmlspecialchars($_POST['unite']));
    
	
	// Vérification que l'utilisateur n'a pas rentré n'importe quoi et mesures de sécurité sur ce qui a été rentré
    if (!(($nomIngr) and ctype_xdigit($quantite) and ($unite))) {
        echo "Au moins une des valeurs rentrée est invalide";
    } else { $requete = "INSERT INTO  SeCompose (idR, nomIngr,quantite, unite) VALUES ('$idR','$nomIngr','$quantite','$unite');";
			//echo "<br>".$requete."<br>";//-> affiche le requete
			mysqli_query($connexion, $requete);
									
			// Vérification de la bonne crétion du lien
			$requete = "SELECT * FROM SeCompose WHERE nomIngr='$nomIngr' AND quantite='$quantite' AND unite='$unite';";
			$resultat = mysqli_query($connexion,$requete);
            if($resultat == FALSE) {
            echo "Erreur : problème d'exécution de la requête de vérification du SeCompose</p>";
            } else {
                if (mysqli_num_rows($resultat) == 0) {
                echo "Erreur : Lien SeCompose non créé suite à une erreur inconnue</p>";
                } else { // Aucun problème, tout a bien été créé
					echo "<p>Confirmation de l'ajout </p>";
					echo "<h4>Les donnees saisies:</h4>";
					echo $nomIngr;
					echo "<br>";
					echo $quantite;
					echo "<br>";
					echo $unite;
					echo "<br>";
				}
			}

			
		
			 
			 
			?>	
	<form method="POST" action="index.php?page=saisie_recette_p5.php" >			
	<input type="submit" value="ajouter encore"/>
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>
	
	<form method="POST" action="index.php?page=saisie_recette_p3.php" >			
	<input type="submit" value="j'ai fini"  />
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>		
				

			
<?php		
			
}}
else {
	
	?>
<div id="form_ingredients"> 


	<fieldset> 
	<legend> Choisissez les ingredientes: </legend>
		<form action="index.php?page=saisie_recette_p5.php" method="post">
			
			<div class="champ">
			Ingredient: 
			<select name="ingred" size="1" id="ingr">
			<?php // Récupération des ingrediantes existantess
                $requete = "SELECT nomIngr FROM Ingredient ORDER BY nomIngr ASC;";
                $resultat = mysqli_query($connexion, $requete);
                if(mysqli_num_rows($resultat) == 0) { // aucun résultat
                    echo "<option>Aucun ingredient existant</option>";
                } else { // au moins un résultat
                    while ($row = mysqli_fetch_assoc($resultat)) { // boucle sur chaque n-uplet
                        echo "<option>".$row['nomIngr']."</option>";
                    }
                }
            ?>
			</select>
			</div>
			<br>
			
			<div class="champ">Quantite necessaire
				<input type="number" name="quantite" min="0"/>
			</div>
			
			
			<div class="champ">
				<label for="unite">Unité: </label> 
				<input type="radio" name="unite" value="litre"/>Litre
				<input type="radio" name="unite" value="piece"/>Pièce
				<input type="radio" name="unite" value="gramme"/>Gramme
				<input type="radio" name="unite" value="cuillere a soupe"/>Cuillère à soupe
				<input type="radio" name="unite" value="cuillere a cafe"/>Cuillère à café
				<input type="radio" name="unite" value="pot de yaourt"/>Pot de yaourt
				
			</div>
			
			
			
			<div class="champ">
				<input type ="reset" value="Supprimer"/>
				<input type="submit" value="Continuer"/>
				
			</div>
			
			
			
			<div>
			<input type="hidden" name="idR" value=<?php echo $_POST["idR"]?>>
			<?php($_POST["idR"])?>
			</div>
			
			
		</form>	 
	</fieldset>
</div>

<?php
	}
	


?>

