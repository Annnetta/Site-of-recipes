<?php


	set_time_limit(0);	//pour supprimer la limite d'execution d'integration

	$requete = "SELECT * FROM Adresse;";
    $resultat = mysqli_query($connexion, $requete);
    echo "Verification Table Adresse est vide?";
    if($resultat == FALSE) {
            printf("<p>Erreur : problème d'exécution de la requête</p>");
    } else{
		if (mysqli_num_rows($resultat) == 0) {
            echo "Tableau Adresse n'est pas remplie. Remplissez tout d'abord ce tableau! <br>";
			
        } else {
			echo "Tableau Adresse est bien rempli.On peut continuer<br>";
			$requete = "SELECT * FROM Utilisatrice;";
			$resultat = mysqli_query($connexion, $requete);
			echo "Verification Table Utilisatrice est vide?";
			if($resultat == FALSE) {
            printf("<p>Erreur : problème d'exécution de la requête</p>");
			} else{
				if (mysqli_num_rows($resultat) != 0) {echo "La BDD est déjà remplie";}
				else{
					$requete = "CREATE TABLE Temporaire (emailAdresse VARCHAR(100) PRIMARY KEY, nom VARCHAR(20), prenom VARCHAR(23), dateNaissance VARCHAR(10), 
					dateInscUser DATE, adresse VARCHAR(100), ville VARCHAR(100), sexe VARCHAR(6));";
					mysqli_query($connexion, $requete);
					echo "Table Temporaire est crée<br>";
					
					$requete= "SELECT emailAdresse, nom, prenom, dateNaissance, adresse, ville, sexe FROM PersonnesBDW1;"; //limit 100
					$result=mysqli_query($connexion, $requete);
					echo "selection des donees de table personnesBDW1 est terminee <br>";	
					
					//insertion des donnees dans temporaire
					$k=0;
					while ($ligne = mysqli_fetch_assoc($result)) {
						$datecejour=date('Y-m-d');
						$requete = "INSERT INTO Temporaire(emailAdresse, nom, prenom, dateNaissance,dateInscUser, adresse, ville, sexe) 
						VALUES ('".$ligne['emailAdresse']."','".$ligne['nom']."','".$ligne['prenom']."','".$ligne['dateNaissance']."', 
						'$datecejour','".$ligne['adresse']."','".$ligne['ville']."','".$ligne['sexe']."');";
						
						$resultat=mysqli_query($connexion, $requete);
						if($resultat == FALSE){ $k++;}
					}
					
					echo "il y a $k doublons<br>";
					echo "Remplissage tab Temporaire OK <br>";
					
					
					//on concate les donnees d'une tab Temporaire avec Adresse 
					$requete="SELECT latitude, longitude, emailAdresse, nom, prenom, dateNaissance, dateInscUser, sexe, t.ville 
					FROM Temporaire t INNER JOIN Adresse a ON (a.adr_voie=t.adresse AND t.ville=a.ville);";	//limit 100
					$result = mysqli_query($connexion, $requete);
					echo "Jointure faite<br>";
					
					//on insere ligne par ligne dans notre tab Utilisatrice! il faut changer dateNaissance 
					
					while ($ligne = mysqli_fetch_assoc($result)) {
					//on change Mr, Mrs, Dr, Ms par femme/homme/null
							switch ($ligne['sexe']) {
						case 'Mrs.':
							$sexe = "femme";
							break;
						case 'Ms.':
							$sexe = "femme";
							break;
						case 'Mr.':
							$sexe = "homme";
							break;
						case 'Dr.':
							$sexe = "NULL";
							break;
				
					}
					

					//on change varchar sur date
					$format = "m/d/Y";
					$dateobj = DateTime::createFromFormat($format, $ligne['dateNaissance']);
					$newdate= $dateobj->format("Y-m-d");
			
					//on insere les donnees
					$requete = "INSERT INTO Utilisatrice (emailUser, nomUser, prenomUser, datenaisUser, dateInscUser, latitude, longitude, genreUser) 
					VALUES ('".$ligne['emailAdresse']."','".$ligne['nom']."','".$ligne['prenom']."','".$newdate."',
					'".$ligne['dateInscUser']."','".$ligne['latitude']."','".$ligne['longitude']."', '".$sexe."');";
					mysqli_query($connexion, $requete);
					} 
					
					echo "tab Utilisatrice remplie <br>";
					$requete = "DROP TABLE `Temporaire`";
					$result = mysqli_query($connexion, $requete);
					
					
				}
				echo "Travail fini<br>";
		}
		}
	}