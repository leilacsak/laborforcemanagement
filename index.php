<?php
 
    require_once "app/functions.php";

    if(isset($_POST["createDepartment"])) {
        createDepartments($conn, $_POST);
    }

    if(isset($_POST["modifyDepartment"])) {
        modifyDepartment($conn, $_POST);
    }

    if(isset($_POST["deleteDepartment"])) {
        deleteDepartment($conn, $_POST);
    }

    $departments = getDepartments($conn);
    // pre($departments);

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
        <h1>Részlegek kezelése</h1>

        <div class="grid-2">
            <div class="box">
                <h2>Részleg felvitele</h2>
                <form method="POST">
                    <h3>Részleg neve</h3>
                    <input type="text" name="departmentName" 
                    placeholder="részleg neve" class="text-input">

                    <button class="btn" name="createDepartment">Felvitel</button>
                </form>
            </div>
            <div class="box">
                <h2>Részlegek törlése/módosítása</h2>
                
                <div class="grid-2">
                    <?php foreach($departments as $department): ?>
                        <div class="white-box" method="POST">
                            <form method="POST">
                                <h3>Részleg neve</h3>
                                <input type="text" class="text-input" 
                                value="<?=$department["DepartmentName"]?>"
                                placeholder="elnevezés módosítása" name="departmentName">

                                <input type="hidden" name="departmentID" 
                                value="<?=$department["DepartmentID"]?>">

                                <button class="btn" name="modifyDepartment">Módosítás</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="departmentID" 
                                value="<?=$department["DepartmentID"]?>">

                                <button class="btn" name="deleteDepartment">Törlés</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>