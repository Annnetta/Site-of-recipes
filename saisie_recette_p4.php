<!-- pour saisir les produits-->
<?php
$idR=addslashes(htmlspecialchars($_POST["idR"]));
//echo"variable id est bien ici";
//echo $idR;
?>

<!-- Tabs a remplir
/*PRODUIT (nomProd, categorieProd)*/
/*TRANSFORMER(#nomProd, #nomIngr, quantite, unite)*/
/*EST_COMPOSE(#idR, #nomProd, quantite, unite )*/											
/*UNITE (unite, abbreviation)*/
/*FABRIQUER (#nomProd, #latitude, #longitude, quantite, dateprov)*/
-->


<?php
	if(isset($_POST['nomProd'])){ // Si un formulire a été envoyé : entrer le produit dans la table

    // Vérification que l'utilisateur n'a pas rentré n'importe quoi et mesures de sécurité sur ce qui a été rentré
    $nomProd = addslashes(htmlspecialchars($_POST['nomProd']));
    $categorieProd = addslashes(htmlspecialchars($_POST['categorieProd']));
    $pays = addslashes(htmlspecialchars($_POST['endroit']));
    $quantite = addslashes(htmlspecialchars($_POST['qtProd']));
	$unite = addslashes(htmlspecialchars($_POST['unite']));
    $format = "d/m/Y";
    $dateobj = DateTime::createFromFormat($format, $_POST['dateProduct']);
    $dateProv = $dateobj->format("Y-m-d");
	
	
	
    $quantite1 = addslashes(htmlspecialchars($_POST['quantite1']));
	$unite1 = addslashes(htmlspecialchars($_POST['unite1']));
 	$nomIngr1 = addslashes(htmlspecialchars($_POST['ingred1']));
	
	
    
    $quantite2 = addslashes(htmlspecialchars($_POST['quantite2']));
	$unite2 = addslashes(htmlspecialchars($_POST['unite2']));
	$nomIngr2 = addslashes(htmlspecialchars($_POST['ingred2']));
	
	
    if (!(ctype_alpha($nomProd) and ctype_alpha($categorieProd) and ctype_xdigit($quantite) 
		and $dateProv !="1970-01-01" and ctype_alpha($pays) and ($quantite>0) and ($nomIngr1) and ctype_xdigit($quantite1) and ($unite1)
		and ($nomIngr2) and ctype_xdigit($quantite2) and ($unite2))) {
        echo "Au moins une des valeurs rentrée est invalide";
    } else { //si les donnes saisies sont bonnes
	
	
	// Vérification que le produit n'existe pas déjà dans la base pour cette recette
        $requete = "SELECT nomProd FROM EstCompose WHERE nomProd='$nomProd' AND idR='$idR';";
        $resultat = mysqli_query($connexion, $requete);
        if($resultat == FALSE) {
            printf("<p>Erreur : problème d'exécution de la requête de vérification des doublons</p>");
        } else {
            if (mysqli_num_rows($resultat) != 0) {
                echo "<p>Le produit $nomProd existe déjà dans la base de données pour cette recette</p>";
            } else {// Le dernière requete s'est bien exécutée et le produit n'existe pas encore
                
                // Création du produit dans la table Produit
                $requete = "INSERT INTO Produit (nomProd, categorieProd) VALUES ('$nomProd','$categorieProd');";
                mysqli_query($connexion,$requete);
				
				
          
                // Vérification de la bonne création du produit
                $requete = "SELECT * FROM Produit WHERE nomProd='$nomProd';";
				echo "<br>".$requete."<br>";//-> affiche le requete
                $resultat = mysqli_query($connexion,$requete);
                if($resultat == FALSE) {
                    echo "<p>Erreur : problème d'exécution de la requête de vérification de la création du produit</p>";
                } else {
                    if (mysqli_num_rows($resultat) == 0) {
                        echo "<p>Erreur : Le produit non créé suite à une erreur inconnue</p>";
                    } else { // Pas de problème jusque là et le produit est bien créé
                        echo "<p>Le produit $nomProd a bien été créé";
			
			
			
			
			
					
						
				//pour les produit
				
						/*EST_COMPOSE(#idR, #nomProd, quantite, unite )*/
						$requete = "INSERT INTO EstCompose (idR, nomProd, quantite, unite) VALUES ('$idR','$nomProd', '$quantite', '$unite');";
						echo "<br>".$requete."<br>";//-> affiche le requete
						mysqli_query($connexion,$requete);
						
						
						//verification de la bonne creation de EstCompose
						
						$requete = "SELECT * FROM EstCompose WHERE nomProd='$nomProd' AND quantite='$quantite' AND unite='$unite';";
						echo "<br>".$requete."<br>";//-> affiche le requete
						$resultat = mysqli_query($connexion,$requete);
						if($resultat == FALSE) {
							echo "<p>Erreur : problème d'exécution de la requête de vérification de la création du tableau EstCompose</p>";
							} else {
							if (mysqli_num_rows($resultat) == 0) {
							echo "<p>Erreur : Le lien non créé suite à une erreur inconnue</p>";
							} else { // Pas de problème jusque là et le produit bien créé
							echo "<p>Le lien EstCompose a bien été créé pour produit";
						
				
				
				
					
						
						/*FABRIQUER (#nomProd, #latitude, #longitude, quantite, dateprov)*/
					

							echo "Récupération de la latitude et de la longitude selon le pays choisi";
							$requete = "SELECT latitude, longitude FROM ZoneGeo WHERE pays='$pays'";
							$resultat = mysqli_query($connexion, $requete);
							if($resultat == FALSE or mysqli_num_rows($resultat)!=1) {
								printf("<p>Erreur : le pays spécifié n'existe pas dans la base de donnée</p>");
							} else {
								$result = mysqli_fetch_assoc($resultat);
                            $latitude = $result['latitude'];
                            $longitude = $result['longitude'];
                    
 
 
                            // Création d'un champ dans FABRIQUER pour relier l'ingrédients et produits avec sa zone géographique, via une quantité et une date de production
                            

/*							/*FABRIQUER (#nomProd, #latitude, #longitude, quantite, dateprov)*/
							$requete = "INSERT INTO Fabriquer (nomProd, latitude, longitude, quantite, dateprov) VALUES ('$nomProd','$latitude','$longitude','$quantite','$dateProv');";
                            mysqli_query($connexion, $requete);
                        
                            // Vérification de la bonne crétion du lien
                            $requete = "SELECT * FROM Fabriquer WHERE nomProd='$nomProd' AND latitude='$latitude' AND longitude='$longitude';";
                            $resultat = mysqli_query($connexion,$requete);
                            if($resultat == FALSE) {
                                echo "Erreur : problème d'exécution de la requête de vérification du lien Fabriquer</p>";
                            } else {
                                if (mysqli_num_rows($resultat) == 0) {
                                    echo "Erreur : Lien Fabriquerpour deuxieme ingredient non créé suite à une erreur inconnue</p>";
                                } else { // Aucun problème, tout a bien été créé
                                    echo " et relié à un lieu</p>";
							
							
							/*TRANSFORMER(#nomProd, #nomIngr, quantite, unite)*/
							
							$requete = "INSERT INTO Transformer (nomProd, nomIngr, quantite, unite) VALUES ('$nomProd','$nomIngr1','$quantite1','$unite1');";
                            mysqli_query($connexion, $requete);
                        
                            // Vérification de la bonne crétion du lien
                            $requete = "SELECT * FROM Transformer WHERE nomProd='$nomProd' AND nomIngr='$nomIngr1' AND quantite='$quantite1' AND unite='$unite1';";
                            $resultat = mysqli_query($connexion,$requete);
                            if($resultat == FALSE) {
                                echo "Erreur : problème d'exécution de la requête de vérification du lien Transformer</p>";
                            } else {
                                if (mysqli_num_rows($resultat) == 0) {
                                    echo "Erreur : Lien Transformer pour premier ingredient non créé suite à une erreur inconnue</p>";
                                } else { // Aucun problème, tout a bien été créé
                                    echo " Transformer est relié à un lieu</p>";
													
	
							$requete = "INSERT INTO Transformer (nomProd, nomIngr, quantite, unite) VALUES ('$nomProd','$nomIngr2','$quantite2','$unite2');";
                            mysqli_query($connexion, $requete);
                        
                            // Vérification de la bonne crétion du lien
                            $requete = "SELECT * FROM Transformer WHERE nomProd='$nomProd' AND nomIngr='$nomIngr2' AND quantite='$quantite2' AND unite='$unite2';";
                            $resultat = mysqli_query($connexion,$requete);
                            if($resultat == FALSE) {
                                echo "Erreur : problème d'exécution de la requête de vérification du lien Transformer</p>";
                            } else {
                                if (mysqli_num_rows($resultat) == 0) {
                                    echo "Erreur : Lien Transformer pour deuxiemme ingredient non créé suite à une erreur inconnue</p>";
                                } else { // Aucun problème, tout a bien été créé
                                    echo " Transformer est relié à un lieu</p>";
	
								}}}
							}
								}
							}
							}
					}
				}
					}
				}
			}
		}
	}
	
				
	
		?>	
	<form method="POST" action="index.php?page=saisie_recette_p4.php" >			
	<input type="submit" value="ajouter encore"  />
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>
	
	<form method="POST" action="index.php?page=saisie_recette_p3.php" >			
	<input type="submit" value="j'ai fini"  />
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>
	
	
			
<?php		
			
}
else {
	
	?>
<!--pour produit-->
<div id="form_ingredients">
    <form method="post" action="index.php?page=saisie_recette_p4.php">
        <fieldset>
            <legend> Saisie d'un produit </legend>
            
            <div class="champ">
            <label for="nomProd">Nom du produit</label>
            <input type="text" name="nomProd" id="nomProduit" /><br />
            </div>
            
            
			<div class="champ">
            <label for="categorieProd">Catégorie du produit</label>
            <input type="text" name="categorieProd" id="categorieProduit" /><br />
            </div>
			
            
            <div class="champ">
            <label for="dateProv">Date de fabrication</label>
            <input type="date" name="dateProduct" id="dateProv" placeholder="jj/mm/aaaa"/><br />
            </div>
            
            <div class="champ">
            <label for="quantite">Quantité necessaire</label>
            <input type="number" name="qtProd" id="quantite" /><br />
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
            <label for="pays">Pays de production</label>
            <select name="endroit" size="1" id="pays">
            <?php // Récupération des pays existants
                $requete = "SELECT pays FROM ZoneGeo ORDER BY pays ASC;";
                $resultat = mysqli_query($connexion, $requete);
                if(mysqli_num_rows($resultat) == 0) { // aucun résultat
                    echo "<option>Aucun pays existant</option>";
                } else { // au moins un résultat
                    while ($row = mysqli_fetch_assoc($resultat)) { // boucle sur chaque n-uplet
                        echo "<option>".$row['pays']."</option>";
                    }
                }
            ?>
            </select>
            </div>
			
			<br>




<h1>Merci de saisir au moins deux ingredients pour ce produit</h1>


<!--pout ingredient 1-->



	
			
			<div class="champ">
			Ingredient: 
			<select name="ingred1" size="1" id="ingr">
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
				<input type="number" name="quantite1" min="0"/>
			</div>
			
			
			<div class="champ">
				<label for="unite1">Unité: </label> 
				<input type="radio" name="unite1" value="litre"/>Litre
				<input type="radio" name="unite1" value="piece"/>Pièce
				<input type="radio" name="unite1" value="gramme"/>Gramme
				<input type="radio" name="unite1" value="cuillere a soupe"/>Cuillère à soupe
				<input type="radio" name="unite1" value="cuillere a cafe"/>Cuillère à café
				<input type="radio" name="unite1" value="pot de yaourt"/>Pot de yaourt
				
			</div>
			
			
			
		
	
<!--pour ingredient 2-->


			<div class="champ">
			Ingredient: 
			<select name="ingred2" size="1" id="ingr">
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
				<input type="number" name="quantite2" min="0"/>
			</div>
			
			
			<div class="champ">
				<label for="unite2">Unité: </label> 
				<input type="radio" name="unite2" value="litre"/>Litre
				<input type="radio" name="unite2" value="piece"/>Pièce
				<input type="radio" name="unite2" value="gramme"/>Gramme
				<input type="radio" name="unite2" value="cuillere a soupe"/>Cuillère à soupe
				<input type="radio" name="unite2" value="cuillere a cafe"/>Cuillère à café
				<input type="radio" name="unite2" value="pot de yaourt"/>Pot de yaourt
				
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