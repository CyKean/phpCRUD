<?php
if($_POST){
    // include database connection
    include 'db.php';
    try{
        // insert query
        $query = "INSERT INTO product SET name=:name, description=:description, price=:price, quantity=:quantity, created_at=:created_at";
        // prepare query for execution
        $stmt = $con->prepare($query);
        // posted values
        $name=htmlspecialchars(strip_tags($_POST['name']));
        $description=htmlspecialchars(strip_tags($_POST['description']));
        $price=htmlspecialchars(strip_tags($_POST['price']));
        $quantity=htmlspecialchars(strip_tags($_POST['quantity']));
        // bind the parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        // specify when this record was inserted to the database
        $created_at=date('Y-m-d H:i:s');
        $stmt->bindParam(':created_at', $created_at);
        // Execute the query
        if($stmt->execute()){
            echo "Record was saved.";
        }else{
            echo "Unable to save record.";
        }
    }
    // show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	
	<label for="name">Name</label>
	<input type='text' name='name'/></td>
		
	<label for="description">Description</td>
	<textarea name='description'></textarea>
		
	<label for="price">Price</label>
	<input type='text' name='price'/>

    <label for="quantity">Quantity</label>
	<input type='text' name='quantity'/>
		
	<input type='submit' value='Save' />
</form>

<?php
// include database connection
include 'db.php';
// delete message prompt will be here
// select all data
$query = "SELECT id, name, description, price, quantity FROM product ORDER BY id DESC";
$stmt = $con->prepare($query);
$stmt->execute();
// this is how to get number of rows returned
$num = $stmt->rowCount();
// link to create record form

//start table
echo "<table>";
    //creating our table heading
    echo "<tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Action</th>
    </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['firstname'] to
        // just $firstname only
        extract($row);
        // creating new table row per record
        echo "<tr>
            <td>{$id}</td>
            <td>{$name}</td>
            <td>{$description}</td>
            <td>{$price}</td>
            <td>";
                // read one record
                // we will use this links on next part of this post
                echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";
                // we will use this links on next part of this post
                echo "<a href='delete.php?id={$id}' class='btn btn-danger'>Delete</a>";
            echo "</td>";
        echo "</tr>";
    }
    // table body will be here
// end table
echo "</table>";
//check if more than 0 record found
if($num>0){
    // data from database will be here
}
// if no records found
else{
    echo "<div class='alert alert-danger'>No records found.</div>";
}
?>
</body>
</html>