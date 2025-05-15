<?php require_once '../Controleur/main.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
</head>
<body>
    <script>
        // Initialisation de la carte
        var map = L.map('map').setView([48.80, 5.68], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Ajout des marqueurs et popups
        gymnases.forEach(function(gymnase) {
            var popupContent = `<b>${gymnase.name}</b><br>${gymnase.address}<br>${gymnase.Ville}<br>${gymnase.Zip}`;

            <?php if ($cUtilisateur->GetIsAdmin()): ?>
                    popupContent += `
                        <form method="POST" action="../Controleur/main.php">
                            <input type="hidden" name="action" value="edit_gymnase">
                            <input type="hidden" name="gymid" value="${gymnase.idgym}">
                            <input type="submit" value="Paramètres">
                        </form>
                    `;
            <?php endif; ?>
            <?php if ($cUtilisateur->GetIsClient()): ?>
              if (gymnase.sports && gymnase.sports.length > 0) {

                    popupContent += `<br><button class="btnReserver" data-name="${gymnase.name}" data-idgym="${gymnase.idgym}">Réserver</button>`;
            }else{
        popupContent += `<br>
    <label>Aucun sport disponible pour ce gymnase.</label>`;
        }
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
                } 

                document.getElementById('resaModal').style.display = "block";
            }

            // Bouton de fermeture
            document.getElementById("closeResaModal").onclick = function() {
                document.getElementById('resaModal').style.display = "none";
            }

            window.addEventListener('click', function(event) {
                if (event.target == document.getElementById('resaModal')) {
                    document.getElementById('resaModal').style.display = "none";
                }
            });
        });

        // Si admin, gére l'ouverture/fermeture de la modale gymnase
        <?php if ($cUtilisateur->GetIsAdmin()): ?>
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

</body>
</html>
