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
$name = $_POST["name"];
$dob = $_POST["dob"];
$email = $_POST["email"];
$address = $_POST["address"];
$phone = $_POST["phone"];

//Query for Insertion
$sql = "INSERT INTO `customers` (`Cnic`, `Name`, `Dob`, `Email`, `Address`, `PhoneNo`) 
VALUES ('$cnic', '$name', '$dob', '$email', '$address', '$phone')";

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
            title: 'Successfully Registered!',
            showConfirmButton: true,
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

$con->close();

?>