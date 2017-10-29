<?php

session_start();

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../../dbConnection.php';
$dbConn = getDatabaseConnection();


function getDepartmentInfo()
{
    global $dbConn;
    $sql = "SELECT * FROM tc_department ORDER BY deptName";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //print_r($records);
    
    return $records;
}

function getUserInfo($userId){
    global $dbConn;
    $sql = "SELECT * FROM tc_user WHERE userId = $userId";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    return $record;
 }
if (isset($_GET['updateUserForm'])) { //admin has submitted form to update user
    
    $sql = "UPDATE tc_user
            SET firstName = :fName,
                lastName = :lName
			WHERE userId = :userId";
	$namedParameters = array();
	$namedParameters[":fName"] = $_GET['firstName'];
	$namedParameters[":lName"] = $_GET['lastName'];
	$namedParameters[":userId"] = $_GET['userId'];
    $stmt = $dbConn->prepare($sql);
    $stmt->execute($namedParameters);
    
}



if (isset($_GET['userId'])) {
    
    $userInfo = getUserInfo($_GET['userId']);
    
    
}
?>

<!DOCTYPE hmtl>

<html>
    <head>
        <title> Admin: Updating User </title>
    </head>
    <body>

    <h1> Admin Section </h1>
    <h2> Updating User Info </h2>

    <fieldset>
        
        <legend> Update User </legend>
        
        <form>
            
            <input type="hidden" name="userId" value="<?=$userInfo['userId']?>" />
            First Name: <input type="text" name="firstName" required value="<?=$userInfo['firstName']?>" /> <br>
            Last Name: <input type="text" name="lastName" required value="<?=$userInfo['lastName']?>"/> <br>
            Email: <input type="text" name="email"/> <br>
            University Id: <input type="text" name="universityId"/> <br>
            Phone: <input type="text" name="phone"/> <br>
            Gender: <input type="radio" name="gender" value="F" id="genderF"  <?=($userInfo['gender']=='F')?"checked":"" ?> required/> 
                    <label for="genderF">Female</label>
                    <input type="radio" name="gender" value="M" id="genderM"  required/> 
                    <label for="genderM">Male</label><br>
            Role:   <select name="role">
                        <option value=""> Select One </option>
                        <option>Faculty</option>
                        <option>Student</option>
                        <option>Staff</option>
                    </select>
            <br />
            Department: <select name="deptId">
                            <option value=""> Select One </option>
                            <?php
                            
                                $departments = getDepartmentInfo();
                                foreach ($departments as $record) {
                                    echo "<option value='$record[departmentId]'>$record[deptName]</option>";
                                }
                            
                            ?>
                            
                        </select>
                        <br />
                <input type="submit" name="updateUserForm" value="Update User!"/>
        </form>
        
    </fieldset>


    </body>
</html>