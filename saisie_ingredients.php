<?php

if(isset($_POST['nomIngr'])) { // Si un formulire a été envoyé : entrer l'ingrédient dans la table

    // Vérification que l'utilisateur n'a pas rentré n'importe quoi et mesures de sécurité sur ce qui a été rentré
    $nomIngr = addslashes(htmlspecialchars($_POST['nomIngr']));
    $categIngr = addslashes(htmlspecialchars($_POST['categIngr']));
    $pays = addslashes(htmlspecialchars($_POST['endroit']));
    $quantite = addslashes(htmlspecialchars($_POST['qtIngr']));
    $format = "d/m/Y";
    $dateobj = DateTime::createFromFormat($format, $_POST['dateIngr']);
    $dateprod = $dateobj->format("Y-m-d");
    if (!(ctype_alpha($nomIngr) and ctype_alpha($categIngr) and ctype_digit($quantite) and $dateprod!="1970-01-01" and ctype_alpha($pays))) {
        echo "Au moins une des valeurs rentrée est invalide";
    } else {
        
        // Vérification que l'ingrédient n'existe pas déjà dans la base
        $requete = "SELECT nomIngr FROM Ingredient WHERE nomIngr='$nomIngr';";
        $resultat = mysqli_query($connexion, $requete);
        if($resultat == FALSE) {
            printf("<p>Erreur : problème d'exécution de la requête de vérification des doublons</p>");
        } else {
            if (mysqli_num_rows($resultat) != 0) {
                echo "<p>L'ingrédient $nomIngr existe déjà dans la base de données</p>";
            } else {// Le dernière requete s'est bien exécutée et l'ingrédient n'existe pas encore
                
                // Création de l'ingrédient dans la table Ingrédint
                $requete = "INSERT INTO Ingredient (nomIngr, categorieIngr) VALUES ('$nomIngr','$categIngr');";
                mysqli_query($connexion,$requete);
                
                // Vérification de la bonne création de l'ingrédient
                $requete = "SELECT * FROM Ingredient WHERE nomIngr='$nomIngr';";
                $resultat = mysqli_query($connexion,$requete);
                if($resultat == FALSE) {
                    echo "<p>Erreur : problème d'exécution de la requête de vérification de la création d'ingrédients</p>";
                } else {
                    if (mysqli_num_rows($resultat) == 0) {
                        echo "<p>Erreur : Ingrédient non créé suite à une erreur inconnue</p>";
                    } else { // Pas de problème jusque là et ingrédient bien créé
                        echo "<p>L'ingrédient $nomIngr a bien été créé";
                    
                        // Récupération de la latitude et de la longitude selon le pays choisi
                        $requete = "SELECT latitude, longitude FROM ZoneGeo WHERE pays='$pays'";
                        $resultat = mysqli_query($connexion, $requete);
                        if($resultat == FALSE or mysqli_num_rows($resultat)!=1) {
                            printf("<p>Erreur : le pays spécifié n'existe pas dans la base de donnée</p>");
                        } else {
                            $result = mysqli_fetch_assoc($resultat);
                            $latitude = $result['latitude'];
                            $longitude = $result['longitude'];
                    
                            // Création d'un champ dans PROVENIR pour relier l'ingrédient sa zone géographique, via une quantité et une date de production
                            $requete = "INSERT INTO Provient (nomIngr, latitude, longitude, quantite, dateprov) VALUES ('$nomIngr','$latitude','$longitude','$quantite','$dateprod');";
                            mysqli_query($connexion, $requete);
                        
                            // Vérification de la bonne crétion du lien
                            $requete = "SELECT * FROM Provient WHERE nomIngr='$nomIngr' AND latitude='$latitude' AND longitude='$longitude';";
                            $resultat = mysqli_query($connexion,$requete);
                            if($resultat == FALSE) {
                                echo "Erreur : problème d'exécution de la requête de vérification du lien Provient</p>";
                            } else {
                                if (mysqli_num_rows($resultat) == 0) {
                                    echo "Erreur : Lien Provient non créé suite à une erreur inconnue</p>";
                                } else { // Aucun problème, tout a bien été créé
                                    echo " et relié à un lieu</p>";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} else { // Aucun formulaire n'a été envoyé : on l'affiche
?>
<div id="form_ingredients">
    <form method="post" action="index.php?page=saisie_ingredients.php">
        <fieldset>
            <legend> Saisie d'un ingrédient </legend>
            
            <div class="champ">
            <label for="nomIngredient">Nom de l'ingrédient</label>
            <input type="text" name="nomIngr" id="nomIngredient" /><br />
            </div>
            
            <div class="champ">
            <label for="categorieIngredient">Catégorie de l'ingrédient</label>
            <input type="text" name="categIngr" id="categorieIngredient" /><br />
            </div>
            
            <div class="champ">
            <label for="dateProd">Date de production</label>
            <input type="date" name="dateIngr" id="dateProd" placeholder="jj/mm/aaaa"/><br />
            </div>
            
            <div class="champ">
            <label for="quantite">Quantité disponible</label>
            <input type="number" name="qtIngr" id="quantite" min="0" /><br />
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

            <div class="champ">
            <input type="submit" name="valider" value="Créer" />
            <input type="reset" value="Annuler" />
            </div>
            
        </fieldset>
    </form>
</div>
<?php
}
?>
