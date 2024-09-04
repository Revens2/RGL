<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Carte des Gymnases</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>
    <h1>Localisation des Gymnases</h1>
    <div id="map"></div>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
        <button id="btnOpenModal">Créer un gymnase</button>
    <?php endif; ?>

    <div id="gymModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="POST" action="ajouter_gymnase.php">
                <label for="nom">Nom du Gymnase :</label>
                <input type="text" id="nom" name="nom" required><br><br>
                
                <label for="latitude">Latitude :</label>
                <input type="number" id="latitude" name="latitude" step="0.000001" required><br><br>
                
                <label for="longitude">Longitude :</label>
                <input type="number" id="longitude" name="longitude" step="0.000001" required><br><br>
                
                <label for="adresse">Adresse :</label>
                <textarea id="adresse" name="adresse" required></textarea><br><br>
                
                <input type="submit" value="Ajouter le gymnase">
            </form>
        </div>
    </div>
<?php
session_start();
include 'db_connect.php';
    $sql = "SELECT Nom, Coordonnées_lattitude, Coordonnées_longitude, Adresse FROM gymnase_";
    $result = $conn->query($sql);

    $gymnases = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $gymnases[] = [
                'name' => $row['Nom'],
                'latitude' => $row['Coordonnées_lattitude'],
                'longitude' => $row['Coordonnées_longitude'],
                'address' => $row['Adresse']
            ];
        }
    }
    


    $conn->close();
    ?>

    <script>

        var map = L.map('map').setView([48.80, 5.68], 8); 
            

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,

        }).addTo(map);

        var gymnases = <?php echo json_encode($gymnases); ?>;

        // Ajouter des marqueurs pour chaque gymnase
        gymnases.forEach(function(gymnase) {
            L.marker([gymnase.latitude, gymnase.longitude]).addTo(map)
                .bindPopup(`<b>${gymnase.name}</b><br>${gymnase.address}`)
                .openPopup();
        });

        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Fermer la modale
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Fermer la modale si l'utilisateur clique en dehors
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>


