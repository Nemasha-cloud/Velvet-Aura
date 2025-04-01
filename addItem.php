<?php require_once 'config.php'; ?>
<?php include 'adminHeader.php'; ?>

<?php 
	if(isset($_SESSION['log_user'])){
		$user=$_SESSION['log_user'];
		$sql="SELECT admin_id FROM admin WHERE  admin_name='$user'";
		if($result=$conn->query($sql)){
			if($result->num_rows>0){

				$admin=$row['admin_id'];
			}
		}else{

			echo "no results";
		}
		
	}else{
		header("Location:adminLog.php");
	}
?>



	
<?php 
    require 'config.php';
    

    if(!isset($_SESSION['log_user'])){
        header('adminLog.php');
    }else{
        $adminName=$_SESSION['log_name'];
    }
?>

<link rel="stylesheet" href="src/css/addItem.css" type="text/css">

</header>
<body>

    <form id="addProductForm">
        <div class="container-1">
            <div class="left-side">
                <label>Product Name</label>
                <input class="product-name" name="productName" type="text" required>
                <div class="wrapper">
                    <div class="category">
                        <label>Category</label>
                        <select class="category-select" name="category" required>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                            <option value="kid">Kid</option>
                        </select>
                    </div>
                    <div class="type">
                        <label>Type</label>
                        <select class="type-select" name="type" required>
                            <optgroup label="Men">
                                <option value="long-shirt">Long sleeves shirt</option>
                                <option value="short-shirt">Short sleeves shirt</option>
                                <option value="t-shirt">Short sleeves T-shirt</option>
                                <option value="long-tshirt">Long sleeves T-shirt</option>
                                <option value="trousers">Trousers</option>
                                <option value="shorts">Shorts</option>
                            </optgroup>
                            <optgroup label="Women">
                                <option value="blouse">Blouse</option>
                                <option value="skirts">Skirts</option>
                                <option value="dress">Dresses</option>
                                <option value="pants">Pants</option>
                                <option value="shorts">Shorts</option>
                            </optgroup>
                            <optgroup label="Kids">
                                <option value="girls">Girls</option>
                                <option value="boys">Boys</option>
                                <option value="baby">Baby collection</option>
                            </optgroup>   
                        </select>
                    </div>
                </div>
                <label>Description</label>
                <input class="description" name="description" type="text" required>
            </div>
            
            <div class="middle">
                <div class="product-images">
                    <label>Product Images</label>
                    <input type="text" name="imageAddress" required>
                    <p>*Image must not exceed the size of 4MB</p>
                </div>
                <div class="add-size">
                    <label>Add Size</label>
                    <div class="size-toplayer">
                        <label><input type="radio" value="XS" name="Size" required>XS</label>
                        <label><input type="radio" value="S" name="Size">S</label>
                        <label><input type="radio" value="M" name="Size">M</label>                   
                    </div>
                    <div class="size-bottomlayer">
                        <label><input type="radio" value="L" name="Size">L</label>
                        <label><input type="radio" value="XL" name="Size">XL</label>
                    </div>
                </div>
            </div>

            <div class="right-side">
                <label>Price</label>
                <input type="number" name="price" step="0.01" required>
                <label>Qty</label>
                <input class="qty-input" type="number" min="1" value="1" name="qty" required>
                <div class="btn-grp">
                    <button type="submit">Add Product</button>
                    <button type="reset">Cancel</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById("addProductForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);
            let jsonData = {};

            formData.forEach((value, key) => { jsonData[key] = value; });

            fetch("addItemApi.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Item added successfully!");
                    document.getElementById("addProductForm").reset();
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    </script>

</body>
</html>
