<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);  

    $conn->query("ALTER TABLE rooms AUTO_INCREMENT = 1");

    $sql = "INSERT INTO rooms (roomNumber, roomType, roomCapacity) VALUES
            ('101', 'single', 1), ('102', 'single', 1), ('103', 'single', 1), ('104', 'double', 2), 
            ('105', 'double', 2), ('106', 'double', 2), ('107', 'double', 2), ('108', 'king', 3), 
            ('109', 'king', 3), ('110', 'king', 3), ('111', 'king', 3), ('112', 'king', 3),
            
            ('201', 'single', 1), ('202', 'single', 1), ('203', 'double', 2), ('204', 'double', 2), 
            ('205', 'double', 2), ('206', 'studio', 2), ('207', 'studio', 2), ('208', 'king', 3), 
            ('209', 'king', 3), ('210', 'king', 3),
            
            ('301', 'double', 2), ('302', 'double', 2), ('303', 'king', 3), ('304', 'king', 3), 
            ('305', 'suite', 4), ('306', 'suite', 4), ('307', 'suite', 4), ('308', 'studio', 2), 
            ('309', 'studio', 2),
            
            ('401', 'double', 2), ('402', 'king', 3), ('403', 'suite', 4), ('404', 'suite', 4), 
            ('405', 'suite', 4), ('406', 'suite', 4), ('407', 'studio', 2), ('408', 'studio', 2),
            
            ('501', 'king', 3), ('502', 'king', 3), ('503', 'suite', 4), ('504', 'suite', 4), 
            ('505', 'suite', 4), ('506', 'suite', 4), ('507', 'studio', 2),
            
            ('601', 'suite', 4), ('602', 'suite', 4), ('603', 'suite', 4), ('604', 'suite', 4), 
            ('605', 'suite', 4), ('606', 'studio', 2),
            
            ('701', 'pent', 5), ('702', 'pent', 5), ('703', 'pent', 5), ('704', 'pent', 5)";

    $conn->query($sql);   
    $conn->close();   
?>