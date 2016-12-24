<!-- La page est divisée en deux parties : le formulaire et la carte -->

<div id="form">
    <form method="post" action="index.php?page=affichage_catographique.php">
        <label for="recherche">Que voulez afficher sur la carte ?</label>
        <select name="endroit" size="1" id="pays">
            <option>Ingrédients</option>
            <option>Utilisateurs</option>
            <option>Produits</option>
        </select>
        <input type="submit" name="valider" value="Rechercher" />
    </form>
</div>
<br />
<div id="map">
    <script>
        
        function initMap() {

        // Crée l'objet google maps
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12, // 12 permet d'avoir le zoom de 50km demandé
          center: <?php 
            if(isset($_POST['endroit'])) {
                echo "{lat:45.7820121, lng:4.8655159}";
            } else {
                echo "{lat:45.7820121, lng:4.8655159}";
            }
          ?>
        });

      // Génération d'un objet marker, sur lequel est ajouté un listener sur clique qui ouvre une fenêtre d'info
      <?php // Génération des points sur la carte avec les latitudes des lieux de la bdd, selon ce qui est demandé par l'utilisateur + la partie cliquable
        if(isset($_POST['endroit'])) {
        
            // Si l'utilisateur a choisi ce qu'il voulait voir
            $endroit = addslashes($_POST['endroit']);
            
            // Création de la requete permettant d'avoir une liste de lieux à mettre sur la carte
            switch ($endroit) {
                case "Ingrédients":
                    $requete = "SELECT nomIngr AS affichage, latitude, longitude FROM Provient;";
                    break;
                case "Utilisateurs":
                    $requete = "SELECT CONCAT(nomUser, ' ', prenomUser) AS affichage, latitude, longitude FROM Utilisatrice;";
                    break;
                case "Produits":
                    $requete = "SELECT nomProd AS affichage, latitude, longitude FROM Fabriquer;";
                    break;
                default:
                    echo "Merci de ne pas saisir n'importe quoi";
            }
            
            // Exécution de la requete, lecture du résultat de la requête et mise dans le tableau javascript
            $resultat = mysqli_query($connexion,$requete);
            if($resultat == FALSE) {
                printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");
            } else {
                if(mysqli_num_rows($resultat) != 0) { // il y a un résultat
                    $compteur = 0;
                    while ($row = mysqli_fetch_assoc($resultat)) {
                        $compteur = $compteur + 1;
                        ?>
                        var infowindow<?php echo $compteur; ?> = new google.maps.InfoWindow({
                            content: "<?php echo $row['affichage'];?>"
                        });
                        var marker<?php echo $compteur; ?> = new google.maps.Marker({
                            position: <?php echo '{lat:'.$row['latitude'].', lng:'.$row['longitude']."}";?>,
                            map: map,
                        });
                        marker<?php echo $compteur; ?>.addListener('click', function() {
                            infowindow<?php echo $compteur; ?>.open(map, marker<?php echo $compteur; ?>);
                        });
                        <?php
                    }
                }
            }
        } else {
        // Sinon, on localise le batiment Nautibus de l'UCBL
        ?>
        var infowindow = new google.maps.InfoWindow({
            content: "Université Claude Bernard, Bâtiment Nautibus"
        });
        var marker = new google.maps.Marker({
            position: {lat:45.7820121, lng:4.8655159},
            map: map
        });
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
        <?php
        }
    ?>
    }
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">// Pour obtenir l'image des clusters
    </script>
    <script async defer 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCW8p6RP1944DMMeJOwGtNgIBc2Hm9G3f8&callback=initMap"> // Pour charger la carte googlemaps
    </script>
 </div>
