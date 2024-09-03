<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
//include database connection
include 'db.php';
// read current record's data
try {
    // prepare select query
    $query = "SELECT id, name, description, price, quantity FROM product WHERE id = ? LIMIT 0,1";
    $stmt = $con->prepare( $query );
    // this is the first question mark
    $stmt->bindParam(1, $id);
    // execute our query
    $stmt->execute();
    // store retrieved row to a variable
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // values to fill up our form
    $name = $row['name'];
    $description = $row['description'];
    $price = $row['price'];
    $quantity = $row['quantity'];
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]. "?id={$id}")?>" method="post">
	
	<label for="name">Name</label>
	<input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);?>" /></td>
		
	<label for="description">Description</td>
	<input type="text" name='description' value="<?php echo htmlspecialchars($description, ENT_QUOTES);?>"></input>
		
	<label for="price">Price</label>
	<input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);?>"/>

    <label for="quantity">Quantity</label>
	<input type='text' name='quantity' value="<?php echo htmlspecialchars($quantity, ENT_QUOTES);?>"/>
		
	<input type='submit' value='Save' />
</form>

<?php
// check if form was submitted
include'db.php';

if($_POST){
    try{
        // write update query
        // in this case, it seemed like we have so many fields to pass and
        // it is better to label them and not use question marks
        $query = "UPDATE product
                    SET name=:name, description=:description, price=:price, quantity=:quantity, updated_at=:updated_at
                    WHERE id = :id";
        // prepare query for excecution
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
        $stmt->bindParam(':id', $id);

        $updated_at=date('Y-m-d H:i:s');
        $stmt->bindParam(':updated_at', $updated_at);
        // Execute the query
        if($stmt->execute()){
            echo "<div class='alert alert-success'>Record was updated.</div>";
            header("Location: index.php");
            exit();
        }else{
            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
        }
    }
    // show errors
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }
}
?>
</body>
</html>