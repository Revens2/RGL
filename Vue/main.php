
<?php require_once '../Controleur/main.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carte des Gymnases</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        var gymnases = <?php echo json_encode($gymnases); ?>;
        var sports = <?php echo json_encode($sports); ?>;
    </script>
</head>
<body>
    <?php require_once '../Vue/menu.php'; ?>


    <div class="container">
        <h1>Localisation des Gymnases</h1>
        <div>
            <?php if ($user->GetIsAdmin()): ?>
                <button id="btnOpensportModal">Ajouter un sport</button>
                <button id="btnOpengymModal">Ajouter un gymnase</button>
            <?php endif; ?>
        </div>
        <?php if (!empty($error)): ?>
                    <p style="color:red;">
                        <?php echo htmlspecialchars($error); ?>
                    </p>
                <?php endif; ?>
        <div id="map"></div>
        <!--popup ajout gymnase-->
        <div id="gymModal" class="modal">
            <div class="modal-content">
                <span id="closeGymModal" class="close">&times;</span>
                <form method="POST" action="../Controleur/main.php">
                    <input type="hidden" name="action" value="add_gymnase" />

                    <label for="nom">Nom du Gymnase :</label>
                    <input
                        type="text"
                        id="tbgymname"
                        name="nom"
                        required
                        pattern="[A-Za-zÀ-ÿ\s\-'0-9]{2,50}"
                        title="2 à 50 caractères : lettres, chiffres, espaces, apostrophes ou tirets autorisés." />
                    <br /><br />

                    <label for="latitude">Latitude :</label>
                    <input
                        type="number"
                        id="tblatitude"
                        name="latitude"
                        step="0.000001"
                        required
                        title="Veuillez saisir un nombre (latitude) avec une précision jusqu’à 6 décimales." />
                    <br /><br />

                    <label for="longitude">Longitude :</label>
                    <input
                        type="number"
                        id="tblongitude"
                        name="longitude"
                        step="0.000001"
                        required
                        title="Veuillez saisir un nombre (longitude) avec une précision jusqu’à 6 décimales." />
                    <br /><br />

                    <label for="adresse">Adresse :</label>
                    <input
                        type="text"
                        id="tbadresse"
                        name="tbadresse"
                        required
                        pattern="[^<>]{5,100}"
                        title="5 à 100 caractères, sans chevrons < ou >." />
                    <br /><br />

                    <label for="Ville">Ville :</label>
                    <input
                        type="text"
                        id="tbville"
                        name="ville"
                        required
                        pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                        title="2 à 50 caractères : lettres, espaces, apostrophes ou tirets autorisés." />
                    <br /><br />

                    <label for="Zip">Code Postal :</label>
                    <input
                        type="text"
                        id="tbzip"
                        name="zip"
                        required
                        pattern="[0-9]{4,5}"
                        title="4 ou 5 chiffres." />
                    <br /><br />

                    <input type="submit" value="Ajouter le gymnase" />
                </form>
            </div>
        </div>

        <!--popup sport-->
        <div id="sportModal" class="modal">
            <div class="modal-content">
                <span id="closesportModal" class="close">&times;</span>
                <form method="POST" action="../Controleur/main.php">
                    <input type="hidden" name="action" value="add_sport" />

                    <label for="sport_nom">Nom du Sport :</label>
                    <input
                        type="text"
                        id="tbsportname"
                        name="sport_nom"
                        required
                        pattern="[A-Za-zÀ-ÿ\s]+"
                        title="Seules les lettres et espaces sont autorisés." /><br /><br />

                    <label for="collectif">Sport Collectif :</label>
                    <input type="checkbox" id="cbCollectif" name="collectif" /><br /><br />

                    <input type="submit" value="Ajouter le sport" />
                </form>
            </div>
        </div>

        <!--popup rese-->
        <div id="resaModal" class="modal"
            <?php if ($showResaModal): ?>
                style="display:block;"
            <?php endif; ?>>
            <div class="modal-content">
                <span id="closeResaModal" class="close">&times;</span>
                <h2>Réserver un gymnase</h2>

               

                <form method="POST" action="../Controleur/main.php">
                    <input type="hidden" name="action" value="add_reservation" />
                    <input type="hidden" id="gymeidField" name="gymeid" />

                    <label for="gymNameField">Gymnase :</label>
                    <input
                        type="text"
                        id="gymNameField"
                        name="gymname"
                        readonly /><br /><br />

                    <label for="datedebut">Date de début :</label>
                    <input
                        type="datetime-local"
                        id="datedebut"
                        name="datedebut"
                        required /><br /><br />

                    <label for="datefin">Date de fin :</label>
                    <input
                        type="datetime-local"
                        id="datefin"
                        name="datefin"
                        required /><br /><br />

                    <label for="sports">Sports disponibles :</label><br />
                    <div id="sportsContainer"></div>

                    <label for="commentaire">Commentaire :</label>
                    <textarea
                        id="commentaire"
                        name="commentaire"
                        pattern="[^<>]+"
                        title="Les chevrons < et > ne sont pas autorisés."></textarea>
                    <br /><br />

                    <input type="submit" value="Confirmer la réservation" />
                </form>
            </div>
        </div>


       <!--popup para gym-->
        <?php if ($showEditModal): ?>
            <div id="paraModal" class="modal" style="display: block;">
                <div class="modal-content">
                    <span id="closeParaModal" class="close">&times;</span>
                    <form method="POST" action="../Controleur/main.php" style="display: inline-block; margin-right: 10px;">
                        <input type="hidden" name="action" value="parametre" />
                        <input type="hidden" name="paragymid" value="<?php echo $editGymData['Id_Gymnase']; ?>" />

                        
                        <label for="paranom">Nom :</label>
                        <input
                            type="text"
                            id="paranom"
                            name="paranom"
                            value="<?php echo htmlspecialchars($editGymData['Nom']); ?>"
                            required
                            pattern="[A-Za-zÀ-ÿ0-9\s'’\-]{2,50}"
                            title="2 à 50 caractères : lettres (accents), chiffres, espaces, apostrophes, tirets." />
                        <br /><br />

                     
                        <label for="paralatitude">Latitude :</label>
                        <input
                            type="number"
                            id="paralatitude"
                            name="paralatitude"
                            value="<?php echo htmlspecialchars($editGymData['Coordonnees_latitude']); ?>"
                            step="0.000001"
                            required />
                        <br /><br />

                       
                        <label for="paralongitude">Longitude :</label>
                        <input
                            type="number"
                            id="paralongitude"
                            name="paralongitude"
                            value="<?php echo htmlspecialchars($editGymData['Coordonnees_longitude']); ?>"
                            step="0.000001"
                            required />
                        <br /><br />

                       
                        <label for="tbparaadresse">Adresse :</label>
                        <input
                            type="text"
                            id="tbparaadresse"
                            name="tbparaadresse"
                            required
                            pattern="[^<>]{5,100}"
                            title="5 à 100 caractères, sans < ni >."
                            value="<?php echo htmlspecialchars($editGymData['Adresse']); ?>" />
                        <br /><br />

                       
                        <label for="paraville">Ville :</label>
                        <input
                            type="text"
                            id="paraville"
                            name="paraville"
                            required
                            pattern="[A-Za-zÀ-ÿ\s'’\-]{2,50}"
                            title="2 à 50 caractères : lettres (accents), espaces, apostrophes, tirets."
                            value="<?php echo htmlspecialchars($editGymData['Ville']); ?>" />
                        <br /><br />

                       
                        <label for="parazip">Code Postal :</label>
                        <input
                            type="text"
                            id="parazip"
                            name="parazip"
                            required
                            pattern="[0-9]{4,5}"
                            title="4 ou 5 chiffres."
                            value="<?php echo htmlspecialchars($editGymData['Zip']); ?>" />
                        <br /><br />

                        <label for="sports">Sports disponibles :</label><br />
                        <?php foreach ($allSports as $sportItem): ?>
                            <input
                                type="checkbox"
                                name="sports[]"
                                value="<?php echo $sportItem['Id_Sport']; ?>"
                                <?php echo in_array($sportItem['Id_Sport'], $associatedSports) ? 'checked' : ''; ?> />
                            <?php echo htmlspecialchars($sportItem['Nom_du_sport']); ?><br />
                        <?php endforeach; ?>

                        <input type="submit" value="Modifier le gymnase" style="margin-left:55px;" />

                    </form>
                    <form method="POST" action="main.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce gymnase ?');" style="display: inline-block; margin-right: 10px;">
                        <input type="hidden" name="action" value="delete" />
                        <input type="hidden" name="Id_Gymnase" value="<?php echo $editGymData['Id_Gymnase']; ?>" />
                        <input type="submit" class="btn btn-delete" value="Supprimer" style="margin-left:-45px; width:150px;" />
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php require_once '../Vue/Map.php'; ?>

        <?php require_once '../Vue/footer.php'; ?>
</body>
</html>
