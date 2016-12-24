<!--saisir ingredient ou recette-->
<?php
$idR=addslashes(htmlspecialchars($_POST["idR"]));
//echo"variable id est bien ici";
//echo $idR;
?>

<div id="form_ingredients"> 


	<fieldset> 
	<legend> Veuillez saisir les produits et ingredientes pour cette recette </legend>


<h1>Je veux ajouter</h1>
<form method="POST" action="index.php?page=saisie_recette_p4.php" >			
	<input type="submit" value="Produit"  />
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>
	
	
<form method="POST" action="index.php?page=saisie_recette_p5.php" >			
	<input type="submit" value="Ingredient"  />
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>
	
	
	


<h1>Si vous avez fini saisie des ingrediants et des produits:</h1>
<form method="POST" action="index.php?page=saisie_recette_p6.php">
<input type="submit" value="J'ai fini"  />
	<input type="hidden" name="idR"  value="<?php echo $idR;?>"/>
	</form>
</fieldset>

</div>
