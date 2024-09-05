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
    <div>
        <?php if ($connect->isAdmin()): ?>
    <button id="btnOpenModal">Créer un gymnase</button>
    <?php endif; ?>
    </div>
    

    <div id="map"></div>

    <div id="gymModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
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

    <script>
        var map = L.map('map').setView([48.80, 5.68], 8); 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var gymnases = <?php echo json_encode($gymnases); ?>;


        gymnases.forEach(function(gymnase) {
            L.marker([gymnase.latitude, gymnase.longitude]).addTo(map)
                .bindPopup(`<b>${gymnase.name}</b><br>${gymnase.address}`)

                .openPopup();
        });


        var modal = document.getElementById("gymModal");
        var btn = document.getElementById("btnOpenModal");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
