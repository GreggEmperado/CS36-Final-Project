<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);  

    $roomNum = $roomType = $roomCap = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (empty($_POST['roomNum']))
            echo "Room number is required.";
        else            
            $roomNum = $_POST['roomNum'];

        if (empty($_POST['type']))
            echo "Type is required.";
        else            
            $roomType = $_POST['type'];

        if (empty($_POST['capacity']))
            echo "Capacity is required.";
        else            
            $roomCap = $_POST['capacity'];

        if (!empty($roomNum) && !empty($roomType) && !empty($roomCap)){
            $add = $conn->prepare("INSERT INTO rooms (roomNumber, roomType, roomCapacity) VALUES (?,?,?)");
            $add->bind_param("ssi", $roomNum, $roomType, $roomCap);
            $add->execute();
            echo "Success";
        }
        
    }
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sample book</title>
    </head>
    <body>        
        <form method="POST" action="">
            <table>                
                <tr>
                    <td>Room Number</td>
                </tr>
                <tr>
                    <td><input type="text" name="roomNum"></td>
                </tr>          
               
                <tr>
                    <td>Type</td>
                </tr>
                <tr>
                    <td>
                        <select id="roomType" name="type" onchange="maxCap()">
                            <option value="" disabled selected>Select a Room Type</option>
                            <option value="single">Single Bedoom</option>
                            <option value="double">Double BedRoom</option>
                            <option value="suite">Suite</option>
                            <option value="king">King Bedroom</option>
                            <option value="studio">Studio Bedroom</option>
                            <option value="pent">Penthouse</option>
                    </td>
                </tr>  
                <tr>
                    <td>Capacity</td>
                </tr>
                <tr>
                    <td><input type="text" id="capacity" name="capacity" value=""></td>                    
                </tr>                 
                <tr>
                    <td><input type="submit" name="Add" value="Add"></td>
                </tr>

            </table>  
        </form>    
        
        <script>
            function maxCap(){
                const type = document.getElementById('roomType');
                const cap = document.getElementById('capacity');
                
                if (type.value == "single")
                    cap.value = 2;                
                else if (type.value == "double")
                    cap.value = 4;
                else if (type.value == "suite")
                    cap.value = 6;
                else if (type.value == "king")
                    cap.value = 8;
                else if (type.value == "studio")
                    cap.value = 10;
                else if (type.value == "pent")
                    cap.value = 12;
            }
        </script>
    </body>
</html>