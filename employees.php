<?php
 
    require_once "app/functions.php";
    $errors = [];
    $employeeID = isset($_GET["employeeID"]) 
    && is_numeric($_GET["employeeID"]) 
    ? (int)$_GET["employeeID"] : 0;

    if(isset($_POST["createEmployee"])) {
        $errors = createEmployee($conn, $_POST);
    }

    if(isset($_POST["modifyEmployee"])) {
        $errors = modifyEmployee($conn, $_POST);
    }

    if(isset($_POST["deleteEmployee"])) {
        deleteEmployee($conn, $_POST);
    }

    $positions = getPositions($conn);
    $departments = getDepartments($conn);
    $employees = searchEmployee($conn, $_GET);
    $employeeData = getEmployeeByID($conn, $employeeID);

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

        <?php if(!empty($errors)):?>
            <?php foreach($errors as $error): ?>
                <h3><?=$error?></h3>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="grid-2">
            <div class="box">
                <h2>Dolgozó felvitele</h2>

                <form method="POST">
                    <h3>Dolgozó neve</h3>
                    <input type="text" name="fullName" 
                    value="<?=isset($employeeData["FullName"]) 
                    ? $employeeData["FullName"] : ""?>"
                    placeholder="dolgozó neve" class="text-input">

                    <div class="grid-2">
                        <div>
                            <h3>Pozíció</h3>
                            <select name="positionID" class="text-input">
                                <option value="0">Válaszd ki a pozíciót!</option>
                                <?php foreach($positions as $position): ?>
                                    <option value="<?=$position["PositionID"]?>"
                                    <?=isset($employeeData["PositionID"]) 
                                    && $employeeData["PositionID"] == $position["PositionID"]
                                    ? "selected" : ""?>>
                                        <?=$position["PositionName"]?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <h3>Részleg</h3>
                            <select name="departmentID" class="text-input">
                                <option value="0">Válaszd ki a részleget!</option>
                                <?php foreach($departments as $department): ?>
                                    <option value="<?=$department["DepartmentID"]?>"
                                    <?=isset($employeeData["DepartmentID"]) 
                                    && $employeeData["DepartmentID"] == $department["DepartmentID"]
                                    ? "selected" : ""?>>
                                        <?=$department["DepartmentName"]?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div>
                            <h3>Bruttó bér</h3>
                            <input type="number" name="grossWage" 
                            value="<?=isset($employeeData["GrossWage"]) 
                            ? $employeeData["GrossWage"] : ""?>"
                            placeholder="bruttó bér" class="text-input">
                        </div>
                        <div>
                            <h3>Adószám</h3>
                            <input type="text" name="taxNumber" 
                            value="<?=isset($employeeData["TaxNumber"]) 
                            ? $employeeData["TaxNumber"] : ""?>"
                            placeholder="adószám" class="text-input">
                        </div>
                    </div> 

                    <div class="grid-2">
                        <div>
                            <h3>TAJ szám</h3>
                            <input type="text" name="niNumber" 
                            value="<?=isset($employeeData["NINumber"]) 
                            ? $employeeData["NINumber"] : ""?>"
                            placeholder="TAJ szám" class="text-input">
                        </div>
                        <div>
                            <h3>Bankszámlaszám</h3>
                            <input type="text" name="bankAccount" 
                            value="<?=isset($employeeData["BankAccount"]) 
                            ? $employeeData["BankAccount"] : ""?>"
                            placeholder="bankszámlaszám" class="text-input">
                        </div>
                    </div> 

                    <input type="hidden" name="employeeID" value="<?=$employeeID?>">

                    <?php if(empty($employeeData)): ?>
                        <button class="btn" name="createEmployee">Felvitel</button>
                    <?php else: ?>
                        <button class="btn" name="modifyEmployee">Módosítás</button>

                        <a href="http://localhost/munkaero_nyilvantarto/employees.php">Új dolgozó</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="box">
                <h2>Dolgozók keresése</h2>
                <form method="GET">
                    <div class="grid-2">
                        <div>
                            <h3>Dolgozó neve</h3>
                            <input type="text" name="fullName"
                            placeholder="dolgozó neve" class="text-input">
                        </div>
                        <div>
                            <h3>Szervezeti egység</h3>
                            <select name="departmentID" class="text-input">
                                <option value="">Válaszd ki a részleget!</option>
                                <?php foreach($departments as $department): ?>
                                    <option value="<?=$department["DepartmentID"]?>">
                                        <?=$department["DepartmentName"]?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div>
                            <h3>Bankszámlaszám</h3>
                            <input type="text" name="bankAccount" 
                            placeholder="bankszámlaszám" class="text-input">
                        </div>
                        <div class="grid-2">
                            <div>
                                <h3>Fizetés tól</h3>
                                <input type="number" class="text-input" name="wageFrom">
                            </div>

                            <div>
                                <h3>Fizetés ig</h3>
                                <input type="number" class="text-input" name="wageTo">
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn" name="search">Keresés</button>
                </form>

                <div class="grid-2">
                    <?php foreach($employees as $employee): ?>
                        <div class="white-box" method="POST">
                            <form method="POST">
                                <h3>Dolgozó neve</h3>
                                <input type="text" class="text-input" 
                                value="<?=$employee["FullName"]?>"
                                placeholder="elnevezés módosítása" name="fullName">

                                <a href="http://localhost/munkaero_nyilvantarto/employees.php?employeeID=<?=$employee["EmployeeID"]?>">
                                    Megnyitás
                                </a>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="employeeID" 
                                value="<?=$employee["EmployeeID"]?>">

                                <button class="btn" name="deleteEmployee">Törlés</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>