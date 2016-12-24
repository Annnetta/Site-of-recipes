<?php
/* Création de la requête  pour récupérer le nom de l'ingrédient*/
if(isset($_POST['motcle'])) // Si un mot clé est rentré, la requête est crée selon ce mot clé
{
    $motcle = addslashes($_POST['motcle']);
    if (ctype_alpha($motcle)) { // Le mot clé ne doit contenir que des caractères
        $requete = "SELECT nomIngr FROM Ingredient WHERE nomIngr LIKE '%$motcle%' ORDER BY nomIngr ASC LIMIT 50";
    } else {
        echo "le mot clé saisie est invalide<br />";
        $requete = 'SELECT nomIngr FROM Ingredient ORDER BY nomIngr ASC LIMIT 50 ;';
    }

} else { // Sinon, on fait la requête normale

    $requete = 'SELECT nomIngr FROM Ingredient ORDER BY nomIngr ASC LIMIT 50 ;';
}

/* Exécution de la requête et affichage de l'ingrédient */
$resultat = mysqli_query($connexion, $requete);

if($resultat == FALSE) {
    printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");
} else {
    if(mysqli_num_rows($resultat) == 0) { // aucun résultat
        echo "<p>Aucun ingrédient à afficher.</p>";
    } else { // au moins un résultat
        echo "Liste des ingrédients : <p><ul>";
        while ($row = mysqli_fetch_assoc($resultat)) { // boucle sur chaque n-uplet
            echo "<li>".$row['nomIngr'];
             
             // Récupération du (des) pays de prodution de l'ingrédient et affichage
             $requete = "SELECT pays FROM ZoneGeo WHERE (latitude,longitude) IN (SELECT latitude, longitude FROM Provient WHERE nomIngr='".$row['nomIngr']."');";
             $result = mysqli_query($connexion, $requete);
             if($result == FALSE) {
                printf("<p style='font-color: red;'>Erreur : problème d'exécution de la requête SQL.</p>");
             } else {
                if(mysqli_num_rows($result) == 0) {
                    echo ", cet ingrédient n'est produit dans aucun pays (il a peut être fabriqué sur la lune ^^).";
                } else { // au moins un résultat
                    echo " provient de : ";
                    while ($ligne = mysqli_fetch_assoc($result)) {
                        echo $ligne['pays'].", ";
                    }
                }
            }
            echo "</li>";
        }
        echo "</ul></p>";
    }
    mysqli_free_result($resultat);
}

/* Affichage du formulaire pour rechercher un ingrédient via mot clé*/
?>
<form method="post" action="index.php?page=affichage_ingredients.php">
    <label for="recherche">Recherche par mot-clé</label>
    <input type="text" name="motcle" id="recherche" /><br />
    <input type="submit" name="valider" value="Rechercher" />
</form>
