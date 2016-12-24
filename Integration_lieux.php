<?php
	
	$requete = "SELECT * FROM Adresse;";
    $resultat = mysqli_query($connexion, $requete);
    echo "Verification Table est vide?";
    if($resultat == FALSE) {
            printf("<p>Erreur : problème d'exécution de la requête</p>");
    }
	else{
		if (mysqli_num_rows($resultat) != 0) {
           echo "La BDD est déjà remplie<br>";
		} else {
			printf("<p>base est vide</p>");
			$requete = "CREATE TABLE Intermediare(id INT(7), adresse VARCHAR(100), codePostal VARCHAR(15), pays VARCHAR(100), ville VARCHAR(100));";
            mysqli_query($connexion, $requete);
            echo "Table Intermediare est crée<br>";
			$requete= "SELECT DISTINCT geonameid AS id, adresse, codePostal, pays, ville FROM PersonnesBDW1;";
			$result=mysqli_query($connexion, $requete);	
			while ($ligne = mysqli_fetch_assoc($result)) {
                $requete = "INSERT INTO Intermediare(id, adresse, codePostal, pays, ville) VALUES ('".$ligne['id']."',".'"'.$ligne['adresse'].'"'.",'".$ligne['codePostal']."','".$ligne['pays']."',".'"'.$ligne['ville'].'"'.");";
                mysqli_query($connexion, $requete);
            }
			echo "il y a $i doublons<br>";
			echo "Remplissage tab Intermediare OK <br>";
			
			//si on fait la jointure selon id...il y a un cas, quand id est NULL, donc on verifie
			
			
			
			$requete="SELECT id, latitude, longitude, nom, adresse, codePostal, pays, ville FROM LieuxBDW1 NATURAL JOIN Intermediare;";
			$result = mysqli_query($connexion, $requete);
			echo "Jointure faite<br>";
			while ($ligne = mysqli_fetch_assoc($result)) {
				if($ligne['id']!=0){
					$requete = "INSERT INTO Adresse (pays, ville, code_postale, adr_voie, latitude, longitude) VALUES ('".$ligne['pays']."',".'"'.$ligne['ville'].'"'.",'".$ligne['codePostal']."',".'"'.$ligne['adresse'].'"'.",'".$ligne['latitude']."','".$ligne['longitude']."');";
					echo $requete;
					$rer=mysqli_query($connexion, $requete);
					if($rer == FALSE){ echo $requete;}
				} 
				//on peut ajouter else qui va donner latitude et longitude a partir de tous les autres donnees
			}
			echo "Adresse remplie <br>";
			$requete = "DROP TABLE `Intermediare`";
            $result = mysqli_query($connexion, $requete);
			
		}
		echo "Travail fini<br>";
	}
	
	
	
	
	
	
	
?>
