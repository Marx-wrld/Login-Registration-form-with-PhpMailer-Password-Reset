<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Result</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="script.js"></script>
</head>
<body>
<?php
	if(ISSET($_POST['search'])){
		$keyword = $_POST['keyword'];
?>
<div>
	<h2 style="margin-left: 10px; margin-top: 10px;">Result</h2>
	<hr style="border-top:2px dotted #ccc;"/>

	<?php
		require 'connection.php';
		$query = mysqli_query($conn, "SELECT * FROM `members` WHERE `username` LIKE '%$keyword%' ORDER BY `username`") or die(mysqli_error());
		while($fetch = mysqli_fetch_array($query)){
	?>
	<div>
        <h3 style="color:blue; font-size: 20px; margin-left: 10px;">Name: </h3> <p style="margin-left: 10px;"><?php echo $fetch['Username']?></p>
		<h3 style="color:blue; font-size: 20px; margin-left: 10px;">Email: </h3> <p style="margin-left: 10px;"><?php echo substr($fetch['Email'], 0, 100)?></p>
        <h3 style="color:blue; font-size: 20px; margin-left: 10px;">Phone Number: </h3> <p style="margin-left: 10px;"><?php echo substr($fetch['contactno'], 0, 100)?></p>
        <h3 style="color:blue; font-size: 20px; margin-left: 10px;">Registration Number: </h3> <p style="margin-left: 10px;"><?php echo substr($fetch['Registration_no'], 0, 100)?></p>
	</div>
	<hr style="border-bottom:1px solid #ccc;"/>
    <div class="form-group">
    <a href="home.php" style="margin-left: 10px;">Back</a>
    </div>
	<?php
		}
	?>
</div>
<?php
	}
?>
</body>
</html>