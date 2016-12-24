<footer role="contentinfo">
    <!-- Partie Statistiques -->
    <div id="stats">
    <?php
    // Ingrédients
    $requete = "SELECT COUNT(*) AS nombre FROM Ingredient;";
    $resultat = mysqli_query($connexion, $requete);
    $row = mysqli_fetch_assoc($resultat);
    echo "Il y a ".$row['nombre']." ingrédients";
    
    // Recettes
    $requete = "SELECT COUNT(*) AS nombre FROM Recette;";
    $resultat = mysqli_query($connexion, $requete);
    $row = mysqli_fetch_assoc($resultat);
    echo ", ".$row['nombre']." recettes";
    
    // Utilisateurs
    $requete = "SELECT COUNT(*) AS nombre FROM Utilisatrice;";
    $resultat = mysqli_query($connexion, $requete);
    $row = mysqli_fetch_assoc($resultat);
    echo " et ".$row['nombre']." utilisateurs";
    
    ?>
    </div>
    <!-- Partie footer -->
    Projet développé dans le cadre de l'UE
        <a href="http://liris.cnrs.fr/~fduchate/ens/BDW1/"> BDW1 </a>
    à l'<a href="http://www.univ-lyon1.fr/">université Claude Bernard Lyon 1</a>
    en 2016-2017
</footer
