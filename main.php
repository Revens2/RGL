<?php
session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
$connect = new cConnected($conn);

// Récupérer la liste des gymnases pour les afficher sur la carte
$sql = "SELECT Id_Gymnase, Nom, Coordonnees_lattitude, Coordonnees_longitude, Adresse, Ville, Zip FROM gymnase";
$result = $conn->query($sql);

$gymnases = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gymnases[] = [
            'idgym' => $row['Id_Gymnase'],
            'name' => $row['Nom'],
            'latitude' => $row['Coordonnees_lattitude'],
            'longitude' => $row['Coordonnees_longitude'],
            'address' => $row['Adresse'],
            'Ville' => $row['Ville'],
            'Zip' => $row['Zip']
        ];
    }
}

// Vérifier si un formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Cas 1 : Ajout d'un gymnase
        if ($action == 'add_gymnase') {
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

        // Cas 2 : Ajout d'une réservation
        elseif ($action == 'add_reservation') {
            $gymid= $_POST['gymeid'];
            $userid = $_SESSION['user_id'];
            $sport = $_POST['sport'];
            $datedebut = $_POST['datedebut'];
            $datefin = $_POST['datefin'];

            $stmt = $conn->prepare("INSERT INTO reservation (Id_gymnase, Id_utilisateur,Id_sport, Date_debut, Date_fin) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die("Erreur de préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("sssss", $gymid, $userid, $sport, $datedebut, $datefin);

            if ($stmt->execute()) {
                echo "La réservation a bien été ajoutée !";
            } else {
                echo "Erreur lors de l'ajout de la réservation : " . $stmt->error;
            }
            $stmt->close();
        }
    }
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
                <input type="hidden" name="action" value="add_gymnase">
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
            <form method="POST" action="main.php">
                <input type="hidden" name="action" value="add_reservation">
                <label for="gymeidField">ID du Gymnase :</label>
                <input type="number" id="gymeidField" name="gymeid" readonly><br><br>

                <label for="gymNameField">Nom du Gymnase :</label>
                <input type="text" id="gymNameField" name="gymname" readonly><br><br>

                <label for="lbsport">Sport :</label>
                <textarea id="tbsport" name="sport" required></textarea><br><br>

                <label for="lbdatedebut">Quand voulez-vous débuter la réservation ?</label><br>
                <input type="datetime-local" id="datedebut" name="datedebut" required><br><br>

                <label for="lbdatefin">Quand voulez-vous finir la réservation ?</label><br>
                <input type="datetime-local" id="datefin" name="datefin" required><br><br>

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
                popupContent += `<br><button class="btnReserver" data-name="${gymnase.name}" data-idgym="${gymnase.idgym}">Réserver</button>`;
            <?php endif; ?>

            L.marker([gymnase.latitude, gymnase.longitude]).addTo(map)
                .bindPopup(popupContent)
                .openPopup();
        });

        // Ouvrir et fermer la modale d'ajout de gymnase
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

            window.onclick = function(event) {
                if (event.target == gymModal) {
                    gymModal.style.display = "none";
                }
            }
        <?php endif; ?>

        // Ouvrir et fermer la modale de réservation
        document.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('btnReserver')) {
                var gymName = event.target.getAttribute('data-name');
                var gymid = event.target.getAttribute('data-idgym');
                document.getElementById('gymNameField').value = gymName;
                document.getElementById('gymeidField').value = gymid;
                document.getElementById('resaModal').style.display = "block";
            }
        });

        var closeResaModal = document.getElementById("closeResaModal");
        closeResaModal.onclick = function() {
            document.getElementById('resaModal').style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('resaModal')) {
                document.getElementById('resaModal').style.display = "none";
            }
        }
    </script>
</body>
</html>

