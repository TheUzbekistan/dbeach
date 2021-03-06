<?php




$host = "us-cdbr-iron-east-05.cleardb.net";
$dbname="heroku_c95c62d6327a93c";
$username="bc1caf57472822";
$password="55c0d40e";
$dbConn = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
$dbConn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


function getDeviceTypes()
{
    global $dbConn;
    $sql= "SELECT DISTINCT(deviceType) FROM tc_device ORDER BY deviceType";
    
    $stmt = $dbConn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($records as $record){
        echo "<option> ".$record['deviceType']."</option>";
    }
}

function displayDevices(){
    global $dbConn;
    
    $sql = "SELECT * FROM tc_device WHERE 1 ";
    
    
    if (isset($_GET['submit'])){
        
        $namedParameters = array();
        
        
        if (!empty($_GET['deviceName'])) {
            
  
            $sql .= " AND deviceName LIKE :deviceName";
            $namedParameters[':deviceName'] = "%" . $_GET['deviceName'] . "%";

         }
         
        if (!empty($_GET['deviceType'])) {
            
  
            $sql .= " AND deviceType = :dType";
            $namedParameters[':dType'] =   $_GET['deviceType'] ;

         }     
         
         if (isset($_GET['available'])) {
             $sql .= " AND status = 'A'";

         }
        if(isset($_GET['orderBy']))
        {
             $sql .= " ORDER BY ".$_GET['orderBy']." ASC";
        }
       
        
    }
    else{
        $sql .= " ORDER BY deviceName ASC";
    }
    
    //$namedParameters[':orderBy'] =   $_GET['orderBy'] ;
    $stmt = $dbConn->prepare($sql);
    $stmt->execute($namedParameters);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
     foreach ($records as $record) {
        
        echo  "NAME: ".$record['deviceName'] . " | TYPE:" . $record['deviceType'] . " | PRICE: $" .
              $record['price'] .  " | STATUS:" . $record['status'] . 
              " <a href='checkoutHistory.php?deviceId=".$record['deviceId']."'> Checkout History </a> <br />";
        
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Device Search </title>
        <style>
            @import url("css/styles.css");
        </style>
    </head>
    <body>
    <h1> Technology Center: Checkout System</h1>
    
    <form>
        Device: <input type="text" name ="deviceName" placeholder="Device Name"/>
        Type: <select name="deviceType"><option>Select One</option><?=getDeviceTypes()?></select>
        <input type="checkbox"name="available" id= "available">
        <label for="available">Available</label>
        <br>
         <input type="radio" name="orderBy" id="orderByPrice" value="deviceName"/>
        <label for="orderByName"> Name </label>
        <input type="radio" name="orderBy" id="orderByPrice" value="price"/>
        <label for="orderByPrice"> Price </label>
        
        
        
        
        <input type="submit" value="Search!" name="submit">
        
        
    </form>
    
    <hr>
    
    <?=displayDevices()?>
    </body>
</html>







