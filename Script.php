<?php
session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
$connect = new cConnected($conn);

$editGymData = null;
$showEditModal = false;
$allSports = [];
$associatedSports = [];
$disposport = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'edit_gymnase') {
            $gymid = $_POST['gymid'];

            $stmt = $conn->prepare("SELECT * FROM gymnase WHERE Id_Gymnase = ?");
            $stmt->bind_param("i", $gymid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $editGymData = $result->fetch_assoc();
                $showEditModal = true;
            }
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM sport");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $allSports[] = $row;
            }
            $stmt->close();


            $stmt = $conn->prepare("SELECT Id_Sport FROM gymnase_sport WHERE Id_Gymnase = ?");
            $stmt->bind_param("i", $gymid);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $associatedSports[] = $row['Id_Sport'];
            }
            $stmt->close();
        } elseif ($action == 'parametre') {
            $gymid = $_POST['paragymid'];
            $gymname = $_POST['paranom'];
            $latitude = $_POST['paralatitude'];
            $longitude = $_POST['paralongitude'];
            $adresse = $_POST['tbparaadresse'];
            $ville = $_POST['paraville'];
            $zip = $_POST['parazip'];

            $stmt = $conn->prepare("UPDATE gymnase SET Nom = ?, Coordonnees_lattitude = ?, Coordonnees_longitude = ?, Adresse = ?, Ville = ?, Zip = ? WHERE Id_Gymnase = ?");
            if ($stmt === false) {
                die("Erreur de préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("sddsssi", $gymname, $latitude, $longitude, $adresse, $ville, $zip, $gymid);

            if ($stmt->execute()) {

                $stmt = $conn->prepare("DELETE FROM gymnase_sport WHERE Id_Gymnase = ?");
                $stmt->bind_param("i", $gymid);
                $stmt->execute();
                $stmt->close();


                if (isset($_POST['sports']) && is_array($_POST['sports'])) {
                    $stmt = $conn->prepare("INSERT INTO gymnase_sport (Id_Gymnase, Id_Sport) VALUES (?, ?)");
                    foreach ($_POST['sports'] as $sport_id) {
                        $stmt->bind_param("ii", $gymid, $sport_id);
                        $stmt->execute();
                    }
                    $stmt->close();
                }

                echo "Le gymnase a bien été mis à jour !";
                header("Location: main.php");
                exit();
            } else {
                echo "Erreur lors de la mise à jour du gymnase : " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action == 'add_reservation') {
            $gymid = $_POST['gymeid'];

            // Récupérer les informations du gymnase
            $stmt = $conn->prepare("SELECT * FROM gymnase WHERE Id_Gymnase = ?");
            $stmt->bind_param("i", $gymid);
            $stmt->execute();
            $result = $stmt->get_result();
            $gymData = $result->fetch_assoc();
            $stmt->close();

            // Récupérer les sports disponibles pour ce gymnase
            $stmt = $conn->prepare("SELECT s.Id_Sport, s.Nom_du_sport FROM sport s JOIN gymnase_sport gs ON s.Id_Sport = gs.Id_Sport WHERE gs.Id_Gymnase = ?");
            $stmt->bind_param("i", $gymid);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $disposport[] = $row;
            }
            $stmt->close();

            // Indiquer que la popup de réservation doit être affichée
            $showResaModal = true;
        }
    }
}

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des Gymnases</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h1>Localisation des Gymnases</h1>

    <div>
        <?php if ($connect->isAdmin()): ?>
            <button id="btnOpengymModal">Ajouter un gymnase</button>
            <button id="btnOpensportModal">Ajouter un sport</button>
        <?php endif; ?>
    </div>

    <div id="map"></div>

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


    <div id="sportModal" class="modal">
        <div class="modal-content">
            <span id="closesportModal" class="close">&times;</span>
            <form method="POST" action="main.php">
                <input type="hidden" name="action" value="add_sport">
                <label for="sport_nom">Nom du Sport :</label>
                <input type="text" id="tbsportname" name="sport_nom" required><br><br>

                <label for="collectif">Sport Collectif :</label>
                <input type="checkbox" id="cbCollectif" name="collectif"><br><br>

                <input type="submit" value="Ajouter le sport">
            </form>
        </div>
    </div>

    <div id="resaModal" class="modal" <?php if (isset($showResaModal) && $showResaModal) echo 'style="display:block;"'; ?>>
   
        <div class="modal-content">
            <span id="closeResaModal" class="close">&times;</span>
            <h2>Réserver le gymnase</h2>
            <form method="POST" action="main.php">
                <input type="hidden" name="action" value="add_reservation">
                <input type="hidden" id="gymeidField" name="gymeid">
                <label for="gymNameField">Gymnase :</label>
                <input type="text" id="gymNameField" name="gymname" readonly><br><br>

                <label for="sport">Sport :</label>
                <input type="text" id="sport" name="sport" required><br><br>

                <label for="datedebut">Date de début :</label>
                <input type="datetime-local" id="datedebut" name="datedebut" required><br><br>

                <label for="datefin">Date de fin :</label>
                <input type="datetime-local" id="datefin" name="datefin" required><br><br>

                <label for="sports">Sports disponibles :</label><br>
                <?php
                if (!empty($disposport)) {
                    foreach ($disposport as $sport) {
                        echo '<input type="radio" name="sport" value="' . $sport['Id_Sport'] . '" required> ' . htmlspecialchars($sport['Nom_du_sport']) . '<br>';
                    }
                } else {
                    echo 'Aucun sport disponible pour ce gymnase.';
                }
                ?>

                <input type="submit" value="Confirmer la réservation">
            </form>
        </div> 
    </div>

    <div id="paraModal" class="modal" <?php if ($showEditModal)
        echo 'style="display:block;"'; ?>>
        <div class="modal-content">
            <span id="closeparaModal" class="close">&times;</span>
            <form method="POST" action="main.php">
                <input type="hidden" name="action" value="parametre">
                <input type="hidden" name="paragymid" value="<?php echo isset($editGymData['Id_Gymnase']) ? $editGymData['Id_Gymnase'] : ''; ?>">
                <label for="paranom">Nom du Gymnase :</label>
                <input type="text" id="tbparagymname" name="paranom" value="<?php echo isset($editGymData['Nom']) ? htmlspecialchars($editGymData['Nom']) : ''; ?>" required><br><br>

                <label for="paralatitude">Latitude :</label>
                <input type="number" id="tbparalatitude" name="paralatitude" step="0.000001" value="<?php echo isset($editGymData['Coordonnees_lattitude']) ? $editGymData['Coordonnees_lattitude'] : ''; ?>" required><br><br>

                <label for="paralongitude">Longitude :</label>
                <input type="number" id="tbparalongitude" name="paralongitude" step="0.000001" value="<?php echo isset($editGymData['Coordonnees_longitude']) ? $editGymData['Coordonnees_longitude'] : ''; ?>" required><br><br>

                <label for="tbparaadresse">Adresse :</label>
                <textarea id="tbparaadresse" name="tbparaadresse" required><?php echo isset($editGymData['Adresse']) ? htmlspecialchars($editGymData['Adresse']) : ''; ?></textarea><br><br>

                <label for="paraville">Ville :</label>
                <textarea id="tbparaville" name="paraville" required><?php echo isset($editGymData['Ville']) ? htmlspecialchars($editGymData['Ville']) : ''; ?></textarea><br><br>

                <label for="parazip">Zip :</label>
                <textarea id="tbparazip" name="parazip" required><?php echo isset($editGymData['Zip']) ? htmlspecialchars($editGymData['Zip']) : ''; ?></textarea><br><br>

               <label for="sports">Sports disponibles :</label><br>
                <?php
                if (isset($allSports)) {
                    foreach ($allSports as $sport) {
                        $checked = in_array($sport['Id_Sport'], $associatedSports) ? 'checked' : '';
                        echo '<input type="checkbox" name="sports[]" value="' . $sport['Id_Sport'] . '" ' . $checked . '> ' . htmlspecialchars($sport['Nom_du_sport']) . '<br>';
                    }
                }
                ?>
               
                <input type="submit" value="Modifier le gymnase">
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
            var popupContent = `<b>${gymnase.name}</b><br>${gymnase.address}<br>${gymnase.Ville}<br>${gymnase.Zip}`;
            <?php if ($connect->isAdmin()): ?>
                popupContent += `
                    <form method="POST" action="main.php">
                        <input type="hidden" name="action" value="edit_gymnase">
                        <input type="hidden" name="gymid" value="${gymnase.idgym}">
                        <input type="submit" value="Parametre">
                    </form>
                `;
            <?php endif; ?>

            <?php if ($connect->isClient()): ?>
                popupContent += `<br><button class="btnReserver" data-name="${gymnase.name}" data-idgym="${gymnase.idgym}">Réserver</button>`;
            <?php endif; ?>

            L.marker([gymnase.latitude, gymnase.longitude]).addTo(map)
                .bindPopup(popupContent)
                .openPopup();
        });


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

       

        var closeParaModal = document.getElementById("closeparaModal");
        closeParaModal.onclick = function() {
            document.getElementById('paraModal').style.display = "none";
        }

        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('paraModal')) {
                document.getElementById('paraModal').style.display = "none";
            }
        });
    </script>
</body>
</html>


