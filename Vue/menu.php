

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<body>

    <nav>
        <ul>
            <li>
                <a href="../Controleur/main.php">
                    <img src="../icons/home.png" alt="Home">
                </a>
            </li>
        <?php if ($connect->isClient()): ?>
             <li>
                <a href="../Vue/reservation.php">
                    <img src="../icons/reservation.png" alt="Booking">
                </a>
            </li>
         <?php endif; ?>
        <?php if ($connect->isAdmin()): ?>
             <li>
                <a href="../Vue/validation.php">
                    <img src="../icons/validation.png" alt="Validation">
                </a>
            </li>
         <?php endif; ?>
            <li>
                <a href="../Vue/historique.php">
                    <img src="../icons/historical.png" alt="Historique">
                </a>
            </li>
            <li>
                <a href="../Vue/Vaccount.php">
                    <img src="../icons/user.png" alt="Account">
                </a>
            </li>
            <li>
                <a href="../Vue/login.html">
                     <img src="../icons/logout.png" alt="Logout">
                </a>
            </li>
            
        </ul>
    </nav>

</body>
</html>

