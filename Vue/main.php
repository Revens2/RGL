<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des Gymnases</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        var gymnases = <?php echo json_encode($gymnases); ?>;
        var sports = <?php echo json_encode($sports); ?>;
    </script>
</head>
<body>
    <?php include '../Vue/menu.php'; ?>
     

    <div class="container">
        <h1>Localisation des Gymnases</h1>
        <div>
            <?php if ($connect->isAdmin()): ?>
                <button id="btnOpensportModal">Ajouter un sport</button>
                <button id="btnOpengymModal">Ajouter un gymnase</button>
            <?php endif; ?>
        </div>

        <div id="map"></div>

        <!-- Modale Ajouter Gymnase -->
      <div id="gymModal" class="modal">
  <div class="modal-content">
    <span id="closeGymModal" class="close">&times;</span>
    <form method="POST" action="../Controleur/main.php">
      <input type="hidden" name="action" value="add_gymnase">

      <!-- NOM (input text) -->
      <label for="nom">Nom du Gymnase :</label>
      <input
        type="text"
        id="tbgymname"
        name="nom"
        required
        pattern="[A-Za-zÀ-ÿ\s\-'0-9]{2,50}"
        title="2 à 50 caractères : lettres, chiffres, espaces, apostrophes ou tirets autorisés."
      >
      <br><br>

      <!-- LATITUDE (input number) -->
      <label for="latitude">Latitude :</label>
      <input
        type="number"
        id="tblatitude"
        name="latitude"
        step="0.000001"
        required
        title="Veuillez saisir un nombre (latitude) avec une précision jusqu’à 6 décimales."
      >
      <br><br>

      <!-- LONGITUDE (input number) -->
      <label for="longitude">Longitude :</label>
      <input
        type="number"
        id="tblongitude"
        name="longitude"
        step="0.000001"
        required
        title="Veuillez saisir un nombre (longitude) avec une précision jusqu’à 6 décimales."
      >
      <br><br>

      <!-- ADRESSE (input text) -->
      <label for="adresse">Adresse :</label>
      <input
        type="text"
        id="tbadresse"
        name="tbadresse"
        required
        pattern="[^<>]{5,100}"
        title="5 à 100 caractères, sans chevrons < ou >."
      >
      <br><br>

      <!-- VILLE (input text) -->
      <label for="Ville">Ville :</label>
      <input
        type="text"
        id="tbville"
        name="ville"
        required
        pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
        title="2 à 50 caractères : lettres, espaces, apostrophes ou tirets autorisés."
      >
      <br><br>

      <!-- CODE POSTAL (input text) -->
      <label for="Zip">Code Postal :</label>
      <input
        type="text"
        id="tbzip"
        name="zip"
        required
        pattern="[0-9]{4,5}"
        title="4 ou 5 chiffres."
      >
      <br><br>

      <input type="submit" value="Ajouter le gymnase">
    </form>
  </div>
</div>



        <!-- Modale Ajouter Sport -->
       <div id="sportModal" class="modal">
            <div class="modal-content">
                <span id="closesportModal" class="close">&times;</span>
                <form method="POST" action="../Controleur/main.php">
                    <input type="hidden" name="action" value="add_sport">

                    <label for="sport_nom">Nom du Sport :</label>
                    <!-- PATTERN POUR BLOQUER CHIFFRES ET CARACTÈRES SPÉCIAUX -->
                    <input
                        type="text"
                        id="tbsportname"
                        name="sport_nom"
                        required
                        pattern="[A-Za-zÀ-ÿ\s]+"
                        title="Seules les lettres et espaces sont autorisés."
                    ><br><br>

                    <label for="collectif">Sport Collectif :</label>
                    <input type="checkbox" id="cbCollectif" name="collectif"><br><br>

                    <input type="submit" value="Ajouter le sport">
                </form>
            </div>
        </div>


<!-- Modale Réservation -->
<div 
  id="resaModal" 
  class="modal"
  <?php if ($showResaModal): ?>
      style="display:block;"
  <?php endif; ?>
>
    <div class="modal-content">
        <span id="closeResaModal" class="close">&times;</span>
        <h2>Réserver un gymnase</h2>

        <!-- Affichage du message d'erreur s'il existe -->
        <?php if (!empty($error)): ?>
            <p style="color:red;">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="../Controleur/main.php">
            <input type="hidden" name="action" value="add_reservation">
            <input type="hidden" id="gymeidField" name="gymeid">

            <label for="gymNameField">Gymnase :</label>
            <input 
                type="text" 
                id="gymNameField" 
                name="gymname" 
                readonly
            ><br><br>

            <label for="datedebut">Date de début :</label>
            <input 
                type="datetime-local" 
                id="datedebut" 
                name="datedebut" 
                required
            ><br><br>

            <label for="datefin">Date de fin :</label>
            <input 
                type="datetime-local" 
                id="datefin" 
                name="datefin" 
                required
            ><br><br>

            <label for="sports">Sports disponibles :</label><br>
            <div id="sportsContainer"></div>

            <label for="commentaire">Commentaire :</label>
            <textarea
                id="commentaire"
                name="commentaire"
             
                pattern="[^<>]+"
                title="Les chevrons < et > ne sont pas autorisés."
            ></textarea>
            <br><br>

            <input type="submit" value="Confirmer la réservation">
        </form>
    </div>
</div>


        <!-- Modale Paramètres -->
 <?php if ($showEditModal): ?>
    <div id="paraModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span id="closeParaModal" class="close">&times;</span>
            <form method="POST" action="../Controleur/main_controller.php">
                <input type="hidden" name="action" value="parametre">
                <input type="hidden" name="paragymid" value="<?php echo $editGymData['Id_Gymnase']; ?>">

                <!-- Nom -->
                <label for="paranom">Nom :</label>
                <input 
                    type="text"
                    id="paranom"
                    name="paranom"
                    value="<?php echo htmlspecialchars($editGymData['Nom']); ?>"
                    required
                    pattern="[A-Za-zÀ-ÿ0-9\s'’\-]{2,50}"
                    title="2 à 50 caractères : lettres (accents), chiffres, espaces, apostrophes, tirets."
                >
                <br><br>

                <!-- Latitude -->
                <label for="paralatitude">Latitude :</label>
                <input 
                    type="number"
                    id="paralatitude"
                    name="paralatitude"
                    value="<?php echo htmlspecialchars($editGymData['Coordonnees_latitude']); ?>"
                    step="0.000001"
                    required
                >
                <br><br>

                <!-- Longitude -->
                <label for="paralongitude">Longitude :</label>
                <input 
                    type="number"
                    id="paralongitude"
                    name="paralongitude"
                    value="<?php echo htmlspecialchars($editGymData['Coordonnees_longitude']); ?>"
                    step="0.000001"
                    required
                >
                <br><br>

                <!-- Adresse -->
                <label for="tbparaadresse">Adresse :</label>
                <input 
                    type="text"
                    id="tbparaadresse"
                    name="tbparaadresse"
                    required
                    pattern="[^<>]{5,100}"
                    title="5 à 100 caractères, sans < ni >."
                    value="<?php echo htmlspecialchars($editGymData['Adresse']); ?>"
                >
                <br><br>

                <!-- Ville -->
                <label for="paraville">Ville :</label>
                <input 
                    type="text"
                    id="paraville"
                    name="paraville"
                    required
                    pattern="[A-Za-zÀ-ÿ\s'’\-]{2,50}"
                    title="2 à 50 caractères : lettres (accents), espaces, apostrophes, tirets."
                    value="<?php echo htmlspecialchars($editGymData['Ville']); ?>"
                >
                <br><br>

                <!-- Code Postal -->
                <label for="parazip">Code Postal :</label>
                <input 
                    type="text"
                    id="parazip"
                    name="parazip"
                    required
                    pattern="[0-9]{4,5}"
                    title="4 ou 5 chiffres."
                    value="<?php echo htmlspecialchars($editGymData['Zip']); ?>"
                >
                <br><br>

                <label for="sports">Sports disponibles :</label><br>
                <?php foreach ($allSports as $sportItem): ?>
                    <input 
                        type="checkbox" 
                        name="sports[]" 
                        value="<?php echo $sportItem['Id_Sport']; ?>"
                        <?php echo in_array($sportItem['Id_Sport'], $associatedSports) ? 'checked' : ''; ?>
                    >
                    <?php echo htmlspecialchars($sportItem['Nom_du_sport']); ?><br>
                <?php endforeach; ?>

                <input type="submit" value="Modifier le gymnase">
            </form>
        </div>
    </div>
<?php endif; ?>

    <script>
        // Initialisation de la carte Leaflet
        var map = L.map('map').setView([48.80, 5.68], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Ajout des marqueurs et popups
        gymnases.forEach(function(gymnase) {
            var popupContent = `<b>${gymnase.name}</b><br>${gymnase.address}<br>${gymnase.Ville}<br>${gymnase.Zip}`;

            <?php if ($connect->isAdmin()): ?>
                popupContent += `
                    <form method="POST" action="main.php">
                        <input type="hidden" name="action" value="edit_gymnase">
                        <input type="hidden" name="gymid" value="${gymnase.idgym}">
                        <input type="submit" value="Paramètre">
                    </form>
                `;
            <?php endif; ?>

            <?php if ($connect->isClient()): ?>
                popupContent += `<br><button class="btnReserver" data-name="${gymnase.name}" data-idgym="${gymnase.idgym}">Réserver</button>`;
            <?php endif; ?>

            L.marker([gymnase.latitude, gymnase.longitude])
                .addTo(map)
                .bindPopup(popupContent);
        });

        // Gérer l'ouverture et la fermeture de la modale de réservation
        document.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('btnReserver')) {
                var gymId = event.target.getAttribute('data-idgym');
                var gymName = event.target.getAttribute('data-name');
                document.getElementById('gymNameField').value = gymName;
                document.getElementById('gymeidField').value = gymId;

                var selectedGym = gymnases.find(function(gym) {
                    return gym.idgym == gymId;
                });
                var sportsForGym = selectedGym.sports;

                var sportsContainer = document.getElementById('sportsContainer');
                sportsContainer.innerHTML = '';

                if (sportsForGym.length > 0) {
                    sportsForGym.forEach(function(sportId) {
                        var sportName = sports[sportId];
                        var radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = 'sport';
                        radio.value = sportId;
                        radio.required = true;

                        var label = document.createElement('label');
                        label.appendChild(radio);
                        label.appendChild(document.createTextNode(' ' + sportName));

                        sportsContainer.appendChild(label);
                        sportsContainer.appendChild(document.createElement('br'));
                    });
                } else {
                    sportsContainer.innerHTML = 'Aucun sport disponible pour ce gymnase.';
                }

                document.getElementById('resaModal').style.display = "block";
            }

            // Bouton de fermeture
            document.getElementById("closeResaModal").onclick = function() {
                document.getElementById('resaModal').style.display = "none";
            }

            // Cliquer à l'extérieur pour fermer
            window.addEventListener('click', function(event) {
                if (event.target == document.getElementById('resaModal')) {
                    document.getElementById('resaModal').style.display = "none";
                }
            });
        });

        // Si admin, gère l'ouverture/fermeture de la modale gymnase
        <?php if ($connect->isAdmin()): ?>
            var gymModal = document.getElementById("gymModal");
            var btnOpenGymModal = document.getElementById("btnOpengymModal");
            var closeGymModal = document.getElementById("closeGymModal");

            btnOpenGymModal.onclick = function() {
                gymModal.style.display = "block";
            }

            closeGymModal.onclick = function() {
                gymModal.style.display = "none";
            }

            window.addEventListener('click', function(event) {
                if (event.target == gymModal) {
                    gymModal.style.display = "none";
                }
            });

            // Modale sport
            var sportModal = document.getElementById("sportModal");
            var btnOpenSportModal = document.getElementById("btnOpensportModal");
            var closeSportModal = document.getElementById("closesportModal");

            btnOpenSportModal.onclick = function() {
                sportModal.style.display = "block";
            }

            closeSportModal.onclick = function() {
                sportModal.style.display = "none";
            }

            window.addEventListener('click', function(event) {
                if (event.target == sportModal) {
                    sportModal.style.display = "none";
                }
            });
        <?php endif; ?>

        // Si on doit afficher la modale de paramétrage (pour modification)
        <?php if ($showEditModal): ?>
            var paraModal = document.getElementById("paraModal");
            var closeParaModal = document.getElementById("closeParaModal");

            closeParaModal.onclick = function() {
                paraModal.style.display = "none";
            }

            window.addEventListener('click', function(event) {
                if (event.target == paraModal) {
                    paraModal.style.display = "none";
                }
            });
        <?php endif; ?>
    </script>

       <?php require_once '../Vue/footer.php'; ?>
</body>
</html>
