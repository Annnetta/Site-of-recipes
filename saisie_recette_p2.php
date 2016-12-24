<?php
	
if (isset($_POST["nomInstr"])) 
	{

$idR=addslashes(htmlspecialchars($_POST["idR"]));
$nomInstr = addslashes(htmlspecialchars($_POST['nomInstr']));
$descriptionInstr=addslashes(htmlspecialchars($_POST['descriptionInstr']));
$nomUstensile=addslashes(htmlspecialchars($_POST['nomUstensile']));

$requete = "SELECT MAX(numeroEt) AS maxEt  FROM Etape WHERE idR=$idR;";
				//echo "<br>".$requete."<br>";//-> affiche le requete
				$resultat = mysqli_query($connexion, $requete);
				$row = mysqli_fetch_assoc($resultat);
				$numeroEt=$row['maxEt'];
				$numeroEt=$numeroEt+1;
				//echo $numeroEt;


//requete d'insertion en Etape	
/*ETAPE (numeroEt, #idR)*/		
$requete3="INSERT INTO Etape(numeroEt,idR) VALUES ('$numeroEt','$idR');";	
			//echo "<br>".$requete3."<br>";//-> affiche le requete
			
			$resInsert = mysqli_query($connexion, $requete3);
			
			if($resInsert == FALSE) {
			    echo '<p>Erreur lors de l\'insertion de l\'id pour cette etape</p>';
			    //exit();
			}
			{
			/*echo "<p>Confirmation de l'ajout du numero_etape pour cette etape</p>";
			echo "<h2>Les donnees saisies:</h2>";
			echo $numeroEt;
			echo "<br>";
			echo $idR;
			echo "<br>";*/
			}
				
		
			
			
			//ok
			
//instruction
/* INSTRUCTIONS (nomInstr, descriptionInstr, #numeroEt, #idR ) */
$requete1 = "INSERT INTO Instruction (nomInstr, descriptionInstr,numeroEt, idR) VALUES ('$nomInstr', '$descriptionInstr','$numeroEt','$idR');";	//ok

			//echo "<br>".$requete1."<br>";//-> affiche le requete
			$resInsert = mysqli_query($connexion, $requete1);
			
			if($resInsert == FALSE) {
			    echo '<p>Erreur lors de l\'instructions de l\'ustensile pour cette etape</p>';
			    exit();
			}
			{
			echo "<p>Confirmation de l'ajout de l\'instructions pour cette etape</p>";
			echo "<h4>Les donnees saisies:</h4>";
			echo $nomInstr;
			echo "<br>";
			echo $descriptionInstr;
			echo "<br>";
			}
			
			
			
/*NECESSITE(#numeroEt, #nomUstens, #idR)*/
$requete2 = "INSERT INTO Necessite(numeroEt, nomUstensile, idR) VALUES ('$numeroEt', '$nomUstensile', '$idR');";		
			//echo "<br>".$requete2."<br>";//-> affiche le requete
			$resInsert = mysqli_query($connexion, $requete2);
			
			if($resInsert == FALSE) {
			    echo '<p>Erreur lors de l\'insertion de l\'ustensile pour cette etape</p>';
			    exit();
			}
			
			echo "<p>Confirmation de l'ajout de l\'ustensile pour cette etape</p>";
			echo $nomUstensile;
			//echo "<br>";
			//echo $numeroEt;
			//echo "<br>";
			//echo $idR;


			
//bon
/*USTENSILE (nomUstens)*/			
$requete2 = "INSERT INTO Ustensile(nomUstensile) VALUES ('$nomUstensile');";		
			//echo "<br>".$requete2."<br>";//-> affiche le requete
			$resInsert = mysqli_query($connexion, $requete2);
		
			if($resInsert == FALSE) {
			    echo '<p>Erreur lors de l\'insertion de l\'ustensile pour cette etape(deja existe)</p>';
			    
			}
			
			echo "<p>Confirmation de l'ajout de l\'ustensile pour cette etape</p>";
			echo $nomUstensile;
			echo "<br>";
						//ok
			

		?>	
	<form method="POST" action="index.php?page=saisie_recette_p2.php" >			
	<input type="submit" value="ajouter etape"  />
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


<div id="form_ingredients"> 


	<fieldset> 
	<legend> Saisie des etapes: </legend>
		<form action="index.php?page=saisie_recette_p2.php" method="post">
			
			<div class="champ">
			Nom de l'instruction: <input type="text" name="nomInstr"/><br>
			</div>
			
			
			<div class="champ">Description:
				<label>
					<textarea name="descriptionInstr" style="display:block;width:400px;height:100px"> </textarea>
				</label><br>
			</div>
			
			
			<div class="champ">Ustensiles necessaires:  <input type="text" name="nomUstensile"/><br></div>
			
			
			
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
