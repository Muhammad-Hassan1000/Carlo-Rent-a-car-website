<head>
    <script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>
    <script type='text/javascript' src='../node_modules/sweetalert2/dist/sweetalert2.js'></script>
    <link rel='stylesheet' href='../node_modules/sweetalert2/dist/sweetalert2.min.css' media='screen'>
</head>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carlo";

$con = new mysqli($servername, $username, $password, $dbname);
if($con->connect_error){
	die ("Error! Could not connect".$con->connect_error);
}

//Getting post records
$cnic = $_POST["cnic"];
$rentaldate = $_POST["rentaldate"];
$returndate = $_POST["returndate"];
$destination = $_POST["destination"];
$carname = $_POST["carname"];
$model = $_POST["model"];

//Querying available vehicles and retrieving cnic for later checking if the person booking the car has already registered or not
$vehicles = mysqli_query($con, "SELECT `VehicleNo` FROM `cars` WHERE `CarName` LIKE '%{$carname}' AND `Model` = '$model' AND `VehicleNo` NOT IN (SELECT `VehicleNo` FROM `reservations`)");
$registeredcnics = mysqli_query($con, "SELECT `Cnic` FROM `customers` WHERE `Cnic` = '$cnic'");


$vehicleno = $vehicles->fetch_array()[0] ?? '';
$registeredcnic = $registeredcnics->fetch_array()[0] ?? '';

if ($registeredcnic != $cnic)     //checking if person booking has signed up or not
{
    echo "
    <script type='text/javascript'>
    $(document).ready(function(){
        swal.fire({
            title: 'Not registered yet?',
            text: 'Please Signup before booking.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lets Go! Signup.',
            cancelButtonText: 'Later'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../html/index.html#scontent';
            }
            else {
                window.location.href = '../html/book-now.html';
            }
        })
    });
    </script>";
}
elseif (mysqli_num_rows($vehicles) == 0)         //checking if requested vehicles are all unavailable or not
{
    echo "
    <script type='text/javascript'>
    $(document).ready(function(){
        swal.fire({
            icon: 'error',
            title: 'Bad luck!',
            text: 'The vehicle you requested is currently unavailable! You may book another.',
          }).then(function(){
            window.location.href = '../html/book-now.html';
        })
    });
    </script>";
}
else 
{
    //Query for Insertion
    $sql = "INSERT INTO `reservations` (`Cnic`, `RentalDate`, `ReturnDate`, `Destination`, `VehicleNo`) 
    VALUES ('$registeredcnic', '$rentaldate', '$returndate', '$destination', '$vehicleno')";

    //Inserting data in database
    $rs = mysqli_query($con, $sql);
    if($rs)
    {
        echo "
        <script type='text/javascript'>
        $(document).ready(function(){
            swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Booking Successful!',
                text: 'Your vehicle has been booked.',
                showConfirmButton: false,
                timer: 3000
            }).then(function(){
                window.location.href = '../html/index.html';
            })
        });
        </script>";
    }
    else 
    {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}



$con->close();

?>