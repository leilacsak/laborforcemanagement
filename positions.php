<?php
 
    require_once "app/functions.php";

    if(isset($_POST["createPosition"])) {
        createPosition($conn, $_POST);
    }

    if(isset($_POST["modifyPosition"])) {
        modifyPosition($conn, $_POST);
    }

    if(isset($_POST["deletePosition"])) {
        deletePosition($conn, $_POST);
    }

    $positions = getPositions($conn);

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
        <h1>Munkakörök kezelése</h1>

        <div class="grid-2">
            <div class="box">
                <h2>Munkakör felvitele</h2>
                <form method="POST">
                    <h3>Munkakör neve</h3>
                    <input type="text" name="positionName" 
                    placeholder="munkakör neve" class="text-input">

                    <h3>Munkakör leírása</h3>
                    <textarea name="positionDesc" class="text-input"></textarea>

                    <button class="btn" name="createPosition">Felvitel</button>
                </form>
            </div>
            <div class="box">
                <h2>Munkakör törlése/módosítása</h2>
                <div class="grid-2">
                    <?php foreach($positions as $position): ?>
                        <div class="white-box" method="POST">
                            <form method="POST">
                                <h3>Munkakör neve</h3>
                                <input type="text" class="text-input" 
                                value="<?=$position["PositionName"]?>"
                                placeholder="elnevezés módosítása" name="positionName">

                                <h3>Munkakör leírása</h3>
                                <textarea name="positionDesc" 
                                class="text-input"><?=$position["PositionDesc"]?></textarea>

                                <input type="hidden" name="positionID" 
                                value="<?=$position["PositionID"]?>">

                                <button class="btn" name="modifyPosition">Módosítás</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="positionID" 
                                value="<?=$position["PositionID"]?>">

                                <button class="btn" name="deletePosition">Törlés</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>