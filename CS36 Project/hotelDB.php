<?php

$servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
$conn = new mysqli($servername, $username, $password, $database);   
if ($conn->connect_error)   
    die("Connection failed ".$conn->connect_error);        
   
$sqlDatabase = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sqlDatabase) === TRUE)
    $msg = "Database created successfully!";               
else
    $msgErr = "Error creating database: ".$conn->error;

$conn = new mysqli($servername, $username, $password, $database);

$sqlRoomTable = "CREATE TABLE IF NOT EXISTS rooms(
    roomID INT AUTO_INCREMENT PRIMARY KEY, 
    roomNumber TEXT NOT NULL,
    roomType TEXT NOT NULL, 
    roomCapacity INT NOT NULL,
    isAvailable BOOLEAN DEFAULT TRUE
);"; 

$sqlMemberTable = "CREATE TABLE IF NOT EXISTS members(
    memberID INT AUTO_INCREMENT PRIMARY KEY, 
    firstName VARCHAR(40) NOT NULL,
    lastName VARCHAR(40) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phoneNumber VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL
);";  

$sqlBookingTable = "CREATE TABLE IF NOT EXISTS bookings(
    bookingID INT AUTO_INCREMENT PRIMARY KEY,
    memberID INT DEFAULT NULL,
    roomID INT NOT NULL,
    checkInDate DATE NOT NULL,
    checkOutDate DATE NOT NULL,
    bookingDate DATETIME NOT NULL,
    status VARCHAR(25) NOT NULL,
    FOREIGN KEY (memberID) REFERENCES members(memberID),
    FOREIGN KEY (roomID) REFERENCES rooms(roomID)
);"; 

$sqlAvailability = "CREATE TABLE IF NOT EXISTS roomAvailability(
        id INT AUTO_INCREMENT PRIMARY KEY,
        roomID INT NOT NULL,
        date DATE NOT NULL,
        UNIQUE (roomID, date),
        FOREIGN KEY (roomID) REFERENCES rooms(roomID) ON DELETE CASCADE
);";

if ($conn->query($sqlRoomTable) === TRUE) {
    echo "Rooms table created successfully.<br>";
} else {
    echo "Error creating Rooms table: " . $conn->error . "<br>";
}

if ($conn->query($sqlMemberTable) === TRUE) {
    echo "Members table created successfully.<br>";
} else {
    echo "Error creating Customers table: " . $conn->error . "<br>";
}

if ($conn->query($sqlBookingTable) === TRUE) {
    echo "Bookings table created successfully.<br>";
} else {
    echo "Error creating Bookings table: " . $conn->error . "<br>";
}

if ($conn->query($sqlAvailability) === TRUE) {
    echo "Availability table created successfully.<br>";
} else {
    echo "Error creating Bookings table: " . $conn->error . "<br>";
}

$conn->close();  

?>