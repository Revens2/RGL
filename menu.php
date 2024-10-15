<?php
session_start();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
</head>
<body>

    <nav>
        <ul>
            <li>
                <a href="main.php">
                    <img src="icons/home.png" alt="Home">
                </a>
            </li>
        <?php if ($connect->isClient()): ?>
             <li>
                <a href="reservation.php">
                    <img src="icons/reservation.png" alt="Booking">
                </a>
            </li>
         <?php endif; ?>
        <?php if ($connect->isAdmin()): ?>
             <li>
                <a href="validation.php">
                    <img src="icons/validation.png" alt="Validation">
                </a>
            </li>
         <?php endif; ?>
            <li>
                <a href="account.php">
                    <img src="icons/user.png" alt="Account">
                </a>
            </li>
            <li>
                <a href="login.html">
                     <img src="icons/logout.png" alt="Logout">
                </a>
            </li>
            
        </ul>
    </nav>

</body>
</html>
<body>

</body>
</html>
