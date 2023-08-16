<?php
    require_once "app/functions.php";
    $logData = readLogs();
?>

<!DOCTYPE html>
<html lang="en">
    <?php
        require_once "common/head.php";
    ?>
<body>
    <?php
        require_once "common/nav.php";

    ?>
    <div class="container center-text">
        <h1>Naplók megtekintése</h1>
        <?=$logData?>
    </div>
</body>
</html>