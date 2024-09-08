<?php
session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
$connect = new cConnected($conn);

$sql = "SELECT Nom, Coordonnees_lattitude, Coordonnees_longitude, Adresse, Ville, Zip FROM gymnase";
$result = $conn->query($sql);

$gymnases = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gymnases[] = [
            'name' => $row['Nom'],
            'latitude' => $row['Coordonnees_lattitude'],
            'longitude' => $row['Coordonnees_longitude'],
            'address' => $row['Adresse'],
            'Ville' => $row['Ville'],
            'Zip' => $row['Zip']
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gymname = $_POST['nom'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $adresse = $_POST['tbadresse'];
    $ville = $_POST['ville'];
    $zip = $_POST['zip'];

 
    $stmt = $conn->prepare("INSERT INTO gymnase (Nom, Coordonnees_lattitude, Coordonnees_longitude, Adresse, Ville, Zip) VALUES (?, ?, ?, ?, ?, ?)");


    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }


    $stmt->bind_param("ssssss", $gymname, $latitude, $longitude, $adresse, $ville, $zip);


    if ($stmt->execute()) {
        echo "Le gymnase a bien été ajouté !";
    } else {
        echo "Erreur lors de l'ajout du gymnase : " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Carte des Gymnases</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</head>
<body>
    <h1>Localisation des Gymnases</h1>

    <!-- Bouton pour ajouter un gymnase (visible uniquement pour les admins) -->
    <div>
        <?php if ($connect->isAdmin()): ?>
            <button id="btnOpengymModal">Créer un gymnase</button>
        <?php endif; ?>
    </div>
    
    <!-- Carte pour afficher les gymnases -->
    <div id="map"></div>

    <!-- Modale pour ajouter un gymnase -->
    <div id="gymModal" class="modal">
        <div class="modal-content">
            <span id="closeGymModal" class="close">&times;</span>
            <form method="POST" action="main.php">
                <label for="nom">Nom du Gymnase :</label>
                <input type="text" id="tbgymname" name="nom" required><br><br>

                <label for="latitude">Latitude :</label>
                <input type="number" id="tblatitude" name="latitude" step="0.000001" required><br><br>

                <label for="longitude">Longitude :</label>
                <input type="number" id="tblongitude" name="longitude" step="0.000001" required><br><br>

                <label for="adresse">Adresse :</label>
                <textarea id="tbadresse" name="tbadresse" required></textarea><br><br>

                <label for="Ville">Ville :</label>
                <textarea id="tbville" name="ville" required></textarea><br><br>

                <label for="Zip">Zip :</label>
                <textarea id="tbzip" name="zip" required></textarea><br><br>

                <input type="submit" value="Ajouter le gymnase">
            </form>
        </div>
    </div>

    <!-- Modale pour réserver un gymnase -->
    <div id="resaModal" class="modal">
        <div class="modal-content">
            <span id="closeResaModal" class="close">&times;</span>
            <h2>Réserver le gymnase</h2>
            <form method="post" action="reserver.php">
                <label for="gymNameField">Gymnase :</label>
                <input type="text" id="gymNameField" name="gymname" readonly><br><br>

                <label for="dateReservation">Quand voulez-vous réserver ?</label><br>
                <input type="datetime-local" id="dateReservation" name="dateReservation" required><br><br>

                <input type="submit" value="Confirmer la réservation">
            </form>
        </div>
    </div>

    <ul>
        <li><a href="login.html" style="text-align: left;" class="btn">Déconnexion</a></li>
    </ul>

    <script>

        var map = L.map('map').setView([48.80, 5.68], 8); 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var gymnases = <?php echo json_encode($gymnases); ?>;

        gymnases.forEach(function(gymnase) {
            var popupContent = `<b>${gymnase.name}</b><br>${gymnase.address}<br>${gymnase.Ville}<br>${gymnase.Zip}`;

            <?php if ($connect->isClient()): ?>
                popupContent += `<br><button class="btnReserver" id="btnOpenresaModal" data-name="${gymnase.name}">Réserver</button>`;
            <?php endif; ?>

            L.marker([gymnase.latitude, gymnase.longitude]).addTo(map)
                .bindPopup(popupContent)
                .openPopup();
        });


        <?php if ($connect->isClient()): ?>
                var resaModal = document.getElementById("resaModal");
                var btnOpenResaModal = document.getElementById("btnOpenresaModal");
                var closeResaModal = document.getElementById("closeResaModal");

            btnOpenResaModal.onclick = function() {
                resaModal.style.display = "block";
            }

            closeResaModal.onclick = function() {
                resaModal.style.display = "none";
            }
        <?php endif; ?>


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
         <?php endif; ?>  
    </script>
</body>
</html>

