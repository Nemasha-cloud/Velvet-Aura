<?php require 'config.php';
include 'adminHeader.php';
?>

<link rel="stylesheet" href="src/css/editItem.css" type="text/css">

</head>
<body>

<div class="cart-table">
    <center>
        <table id="itemsTable">
            <th>Description</th>
            <th>Size</th>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Actions</th>
        </table>
    </center>
</div>

<script>
    // Fetch items from API and populate the table
    function fetchItems() {
        fetch("itemApi.php")
            .then(response => response.json())
            .then(data => {
                let table = document.getElementById("itemsTable");
                table.innerHTML = `<th>Description</th>
                                   <th>Size</th>
                                   <th>Item</th>
                                   <th>Price</th>
                                   <th>Quantity</th>
                                   <th>Actions</th>`;

                data.forEach(item => {
                    let row = `<tr>
                        <td>${item.name}</td>
                        <td>${item.size}</td>
                        <td><img src="${item.image}" width="50px" height="70px"></td>
                        <td>${item.unit_price}</td>
                        <td><input type="number" name="stock[]" min="1" value="${item.stock}" data-id="${item.item_code}"></td>
                        <td>
                            <button onclick="deleteItem('${item.item_code}')">Delete</button>
                        </td>
                    </tr>`;
                    table.innerHTML += row;
                });
            })
            .catch(error => console.error("Error fetching items:", error));
    }

    // Update item stock
    function updateItems() {
        let inputs = document.querySelectorAll("input[name='stock[]']");
        let itemsToUpdate = { itms_code: [], stock: [] };

        inputs.forEach(input => {
            itemsToUpdate.itms_code.push(input.getAttribute("data-id"));
            itemsToUpdate.stock.push(input.value);
        });

        fetch("itemApi.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(itemsToUpdate)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            fetchItems(); // Refresh list after update
        })
        .catch(error => console.error("Error updating items:", error));
    }

    // Delete an item
    function deleteItem(itemCode) {
        if (!confirm("Are you sure you want to delete this item?")) return;

        fetch("itemApi.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ item_code: itemCode })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            fetchItems(); // Refresh list after deletion
        })
        .catch(error => console.error("Error deleting item:", error));
    }

    // Fetch items on page load
    fetchItems();
</script>

<button onclick="updateItems()">Update</button>

</body>
</html>
