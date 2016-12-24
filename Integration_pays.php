<?php
	$requete = "SELECT * FROM ZoneGeo;";
    $resultat = mysqli_query($connexion, $requete);
    echo "Verification Table vide";
    if($resultat == FALSE) {
            printf("<p>Erreur : problème d'exécution de la requête</p>");
    } else {
       if (mysqli_num_rows($resultat) != 0) {
            echo "La BDD est déjà remplie";
        } else {
            $requete = "CREATE TABLE Annexe(codePays VARCHAR(2) PRIMARY KEY, latitude VARCHAR(9), longitude VARCHAR(9));";
            mysqli_query($connexion, $requete);
            echo "Annexe crée";
            $requete = "SELECT codePays, AVG(latitude) AS lat, AVG(longitude) AS lon FROM LieuxBDW1 GROUP BY codePays;";
            $result = mysqli_query($connexion, $requete);
            echo "Sélection des pays terminée";
            while ($ligne = mysqli_fetch_assoc($result)) {
                $requete = "INSERT INTO Annexe (codePays, latitude, longitude) VALUES ('".$ligne['codePays']."','".$ligne['lat']."','".$ligne['lon']."');";
                mysqli_query($connexion, $requete);
            }
            echo "Remplissage annexe OK";
            $requete = "SELECT continent, latitude, longitude, nom FROM PaysBDW1 NATURAL JOIN Annexe";
            $result = mysqli_query($connexion, $requete);
            echo "Jointure faite";
            while ($ligne = mysqli_fetch_assoc($result)) {
            switch ($ligne['continent']) {
                case 'EU':
                    $continent = "Europe";
                    break;
                case 'AS':
                    $continent = "Asie";
                    break;
                case 'NA':
                    $continent = "Amerique du Nord";
                    break;
                case 'AF':
                    $continent = "Afrique";
                    break;
                case 'AN':
                    $continent = "Antarctique";
                    break;
                case 'SA':
                    $continent = "Amerique du Sud";
                    break;
                case 'OC':
                    $continent = "Oceanie";
                    break;
            }
                $requete = "INSERT INTO ZoneGeo (continent, latitude, longitude, pays) VALUES ('".$continent."','".$ligne['latitude']."','".$ligne['longitude']."','".$ligne['nom']."');";
                mysqli_query($connexion, $requete);
            }
            echo "ZoneGeo remplie";
            $requete = "DROP TABLE `Annexe`";
            $result = mysqli_query($connexion, $requete);
        }
    }
    echo "Travail terminé";
?>
