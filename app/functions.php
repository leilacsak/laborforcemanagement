<?php require_once "conn.php";

    function pre($arr) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
    

    function changeLog($table, $id, $isUpdate) {
        $date = date("Y-m-d H:i:s");
        $data = "Table: {$table}\n";
        $data .= "ID: {$id}\n";
        $data .= "Date: {$date}\n";
        $data .= $isUpdate ? "Type: update\n" : "Type: delete\n";
        $data .= "********************************************\n";
        file_put_contents("change_logs.log", $data, FILE_APPEND);
    }

    /**Departments */
    function createDepartments($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO departments 
        (DepartmentName) VALUES(?)");
        $departmentName = trim($data["departmentName"]);

        $stmt->execute([$departmentName]);
    }

    function modifyDepartment($conn, $data) {
        $stmt = $conn->prepare("UPDATE departments 
        SET DepartmentName = ? WHERE DepartmentID = ?");
        $departmentName = trim($data["departmentName"]);

        $stmt->execute([
            $departmentName, 
            (int)$data["departmentID"]
        ]);

        changeLog("departments", $data["departmentID"], true);
    }

    function checkEmployeeByDepartment($conn, $departmentID) {
        $stmt = $conn->prepare("SELECT COUNT(EmployeeID) as EmployeeNum 
        FROM employees WHERE DepartmentID = ?");

        $stmt->execute([$departmentID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row["EmployeeNum"] > 0;
    }

    function checkEmployeeByPosition($conn, $positionID) {
        $stmt = $conn->prepare("SELECT COUNT(EmployeeID) as EmployeeNum 
        FROM employees WHERE PositionID = ?");

        $stmt->execute([$positionID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row["EmployeeNum"] > 0;
    }

    function deleteDepartment($conn, $data) {
        if(checkEmployeeByDepartment($conn, $data["departmentID"]))
            return;
        
        $stmt = $conn->prepare("DELETE FROM departments
        WHERE DepartmentID = ?");

        $stmt->execute([
            $data["departmentID"]
        ]);

        changeLog("departments", $data["departmentID"], false);
    }

    function getDepartments($conn) {
        $stmt = $conn->query("SELECT * FROM departments");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /***Positions */
    function createPosition($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO positions
        (PositionName, PositionDesc) VALUES(?,?)");
        $posName = trim($data["positionName"]);

        $stmt->execute([
            $posName, 
            $data["positionDesc"]
        ]);
    }

    function modifyPosition($conn, $data) {
        $stmt = $conn->prepare("UPDATE positions 
        SET PositionName = ?, PositionDesc = ? 
        WHERE PositionID = ?");
        
        $posName = trim($data["positionName"]);

        $stmt->execute([
            $posName,
            $data["positionDesc"],
            $data["positionID"]
        ]);

        changeLog("positions", $data["positionID"], true);
    }

    function deletePosition($conn, $data) {
        if(checkEmployeeByPosition($conn, $data["positionID"]))
            return;
        
        $stmt = $conn->prepare("DELETE FROM positions
        WHERE PositionID = ?");

        $stmt->execute([
            $data["positionID"]
        ]);

        changeLog("positions", $data["positionID"], false);
    }

    function getPositions($conn) {
        $stmt = $conn->query("SELECT * FROM positions");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * employees
     */

    function createEmployee($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO employees 
        (FullName, PositionID, DepartmentID, GrossWage,
        TaxNumber, NINumber, BankAccount)
        VALUES(?,?,?,?,?,?,?)");

        $fullName = trim($data["fullName"]);
        $taxNumber = trim($data["taxNumber"]);
        $niNumber = trim($data["niNumber"]);
        $bankAccount = trim($data["bankAccount"]);
        $errors = [];
        //^
        if(!preg_match("/^[\d]{10}$/", $data["taxNumber"]))
            $errors[] = "Az adóazonosító jel formátuma nem megfelelő!";

        if(!preg_match("/^[\d]{9}$/", $data["niNumber"]))
            $errors[] = "A TAJ szám formátuma nem megfelelő!";

        if(!preg_match("/^[\d]{8}\-[\d]{8}(\-[\d]{8})?$/", $data["bankAccount"]))
            $errors[] = "A számlaszám formátuma nem megfelelő!";

        if(!empty($errors))
            return $errors;
 
        $stmt->execute([
            $fullName,
            $data["positionID"],
            $data["departmentID"],
            $data["grossWage"],
            $taxNumber,
            $niNumber,
            $bankAccount
        ]);

        return [];
    }

    function modifyEmployee($conn, $data) {
        $stmt = $conn->prepare("UPDATE employees 
        SET FullName = ?, PositionID = ?, 
        DepartmentID = ?, GrossWage = ?,
        TaxNumber = ?, NINumber = ?, 
        BankAccount = ? WHERE EmployeeID = ?");

        $fullName = trim($data["fullName"]);
        $taxNumber = trim($data["taxNumber"]);
        $niNumber = trim($data["niNumber"]);
        $bankAccount = trim($data["bankAccount"]);
        $errors = [];

        if(!preg_match("/^[\d]{10}$/", $data["taxNumber"]))
            $errors[] = "Az adóazonosító jel formátuma nem megfelelő!";

        if(!preg_match("/^[\d]{9}$/", $data["niNumber"]))
            $errors[] = "A TAJ szám formátuma nem megfelelő!";

        if(!preg_match("/^[\d]{8}\-[\d]{8}(\-[\d]{8})?$/", $data["bankAccount"]))
            $errors[] = "A számlaszám formátuma nem megfelelő!";

        if(!empty($errors))
            return $errors;
 
        $stmt->execute([
            $fullName,
            $data["positionID"],
            $data["departmentID"],
            $data["grossWage"],
            $taxNumber,
            $niNumber,
            $bankAccount,
            $data["employeeID"]
        ]);

        changeLog("employees", $data["employeeID"], true);
        return [];
    }


    function deleteEmployee($conn, $data) {
        $stmt = $conn->prepare("DELETE FROM employees 
        WHERE EmployeeID = ?");

        $stmt->execute([
            $data["employeeID"]
        ]);

        changeLog("employees", $data["employeeID"], false);
    }

    /**
        dolgozók listája
        név szerint
        fizetés szerint
        bankra csoportosítva (bankszámlaszám első 5 jegye)
        szervezeti egységek, azon belül a dolgozói
     */

    function searchEmployee($conn, $data) {
        $sql = "SELECT * FROM employees 
        WHERE 1 = 1 ";

        $execArr = [];

        if(!empty($data["fullName"])) {
            $sql .= "AND FullName LIKE :FullName ";
            $fullName = trim($data["fullName"]);
            $execArr[":FullName"] = "%{$fullName}%";
        }

        if(!empty($data["wageFrom"])) {
            $sql .= "AND GrossWage >= :WageFrom ";
            $execArr[":WageFrom"] = (int)$data["wageFrom"];
        }

        if(!empty($data["wageTo"])) {
            $sql .= "AND GrossWage <= :WageTo ";
            $execArr[":WageTo"] = (int)$data["wageTo"];
        }

        if(!empty($data["bankAccount"])) {
            $sql .= "AND BankAccount LIKE :BankAccount ";
            $execArr[":BankAccount"] = "%".$data["bankAccount"]."%";
        }

        if(!empty($data["departmentID"])) {
            $sql .= "AND DepartmentID = :DepartmentID ";
            $execArr[":DepartmentID"] = $data["departmentID"];
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($execArr);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    function getEmployeeByID($conn, $employeeID) {
        $stmt = $conn->prepare("SELECT * FROM employees
        WHERE EmployeeID = ?");

        $stmt->execute([$employeeID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : [];
    }

    function selectMenu($menuName) {
        $menuArray = explode("/", $_SERVER["SCRIPT_NAME"]);
        $currentMenu = end($menuArray);
        return $currentMenu == $menuName ? "selected-menu" : "";
    }

    function readLogs() {
        if(!file_exists("change_logs.log"))
            return "";
        
        $file = file_get_contents("change_logs.log");
        $logData = str_replace("\n", "<br>", $file);
        return $logData;
    }

?>