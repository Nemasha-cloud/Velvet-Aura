<?php 
require_once 'config.php';

// Check if $conn is valid
if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    die("Database connection failed. Please check config.php.");
}

// Initialize default SQL query
$sql = "SELECT * FROM item WHERE category='WOMEN'";

// Check if the form is submitted
if (isset($_GET["search"])) {
    $min = isset($_GET["min"]) ? (int)$_GET["min"] : 0; // Default to 0 if not set
    $max = isset($_GET["max"]) ? (int)$_GET["max"] : 10000; // Default to 10000 if not set
    $categories_arry = isset($_GET["cat"]) && is_array($_GET["cat"]) ? $_GET["cat"] : []; // Default to empty array if not set
    $sizes = isset($_GET["size"]) && is_array($_GET["size"]) ? $_GET["size"] : []; // Get selected sizes

    // Build the SQL query dynamically
    $conditions = [];

    // Add size condition
    if (!empty($sizes)) {
        $sizeArray = array_map(function($size) use ($conn) { 
            return "'".mysqli_real_escape_string($conn, $size)."'"; 
        }, $sizes);
        $sizeList = implode(",", $sizeArray);
        $conditions[] = "size IN ($sizeList)";
    } else {
        // Default to 's' if no size is selected
        $conditions[] = "size = 's'";
    }

    // Add type (category) condition
    if (!empty($categories_arry)) {
        $myArray = array_map(function($cat) use ($conn) { 
            return "'".mysqli_real_escape_string($conn, $cat)."'"; 
        }, $categories_arry);
        $chk = implode(",", $myArray);
        $conditions[] = "type IN ($chk)";
    }

    // Add price range condition
    $conditions[] = "unit_price BETWEEN $min AND $max";

    // Combine conditions into the SQL query
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }
} else {
    // Default query when form is not submitted
    $sql .= " AND size='s'";
}

?>

<?php include 'header.php'?>

<link rel="stylesheet" href="src/css/test1.css" type="text/css">

<style>
		/* Chat Button */
	.chat-button {
		position: fixed;
		bottom: 20px;
		right: 20px;
		background-color: #fe4253;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		box-shadow: 0 2px 10px rgba(0,0,0,0.2);
		transition: transform 0.3s;
		z-index: 1000;
	}

	.chat-button:hover {
		transform: scale(1.1);
		background-color: #EE2A34;
	}

	/* Chat Container */
	.chat-container {
		position: fixed;
		bottom: 90px;
		right: 20px;
		width: 350px;
		height: 450px;
		background-color: white;
		border-radius: 10px;
		box-shadow: 0 0 20px rgba(0,0,0,0.1);
		display: none;
		flex-direction: column;
		z-index: 1000;
	}

	/* Chat Header */
	.chat-header {
		background-color: #fe4253;
		color: white;
		padding: 10px 15px;
		border-radius: 10px 10px 0 0;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.close-chat {
		background: none;
		border: none;
		color: white;
		font-size: 24px;
		cursor: pointer;
		padding: 0;
		line-height: 1;
	}

	/* Chat Messages */
	.chat-messages {
		flex: 1;
		padding: 15px;
		overflow-y: auto;
		background-color: #f9f9f9;
	}

	.message {
		margin: 10px 0;
		padding: 10px;
		border-radius: 5px;
		max-width: 80%;
	}

	.bot-message {
		background-color: #fe4253;
		color: white;
		align-self: flex-start;
	}

	.user-message {
		background-color: #e0e0e0;
		align-self: flex-end;
		margin-left: auto;
	}

	/* Chat Input */
	.chat-input {
		padding: 15px;
		display: flex;
		border-top: 1px solid #eee;
	}

	.chat-input input {
		flex: 1;
		padding: 8px;
		border: 1px solid #ddd;
		border-radius: 5px;
		margin-right: 10px;
	}

	.chat-input button {
		background-color: #fe4253;
		color: white;
		border: none;
		padding: 8px 15px;
		border-radius: 5px;
		cursor: pointer;
	}

	.chat-input button:hover {
		background-color: #EE2A34;
	}
</style>

</head>
<body>



<!--    End of header-->
   

    <!-- box-1 -->
    
   <div class="wrapper">
     <div class="left-side">
       <form method="get" name="selection-box" style="background-color: #9e9e9e59;justify-content: center;margin: 48px 0;">
           <label><h2>SHOP BY</h2></label>
                  
       <div class="categories">
           <label><h3>Categories</h3></label>
		
        <input type="checkbox" name="cat[]" value="BLOUSE" checked >
        <label >Blouse</label><br>
        <input type="checkbox" name="cat[]" value="SKIRTS" >
        <label >Skirts</label><br>
        <input type="checkbox" name="cat[]" value="DRESS" >
        <label >Dresses</label><br>
        <input type="checkbox" name="cat[]" value="PANTS" >
        <label >Pants</label><br>
        <input type="checkbox"  name="cat[]" value="SHORTS">
        <label >Shorts</label><br>

         </div>
      
    <div class="priceSlider">
         <label><h3>Price</h3></label>
    
       <div class="min-max">
        <div class="min">
         <label>Min</label><span id="min-value"></span>
        </div>
       <div class="max">
         <label>Max</label><span id="max-value"></span>
       </div>     
       </div> 
    
       <div class="min-max-range">
         <input type="range" min="0" max="5000" value="2000" class="range" id="min" name="min">
         <input type="range" min="5001" max="10000" value="8000" class="range" id="max" name="max">      
       </div>    
    
       <div style="clear: both;"></div>    
   </div> 
         
   <div class="size-box">
        <label><h3>Size</h3></label>
       <div class="size">
         <input type="checkbox" name="size[]" value="XS">
         <label>XS</label>
         <input type="checkbox" name="size[]" value="S">
         <label>S</label>  
         <input type="checkbox" name="size[]" value="M">
         <label>M</label><br>
         <input type="checkbox" name="size[]" value="L">
         <label>L</label>
         <input type="checkbox" name="size[]" value="XL">
         <label>XL</label>
       
        </div> 
   </div>
        <input type="submit" name="search" value="Search" style="  
     display: block;
    height: 30px;
    width: 120px;
    margin: 30px 0;
    text-align: center;
    background-color:deepskyblue;
    font-size: 16px;
    border:none;
    color: white;
    margin-left: auto;
    margin-right: auto;">
         </form>
         
         
       </div>

        <div class="right-side">
                    
     <?php 
     if ($result = $conn->query($sql)) {
         if ($result->num_rows > 0) {
             while ($row = $result->fetch_assoc()) {
                 echo ("<div class='item-box' style='background-image: url(".htmlspecialchars($row['image']).");'>");
                 echo ("<div class='item-box-overlay'>");
                 echo ("<center><a href='itemDetails.php?id=".$row['item_code']."'><button class='item-view-button'>View Item</button></a></center>");
                 echo ("</div>");
                 echo ("<div class='item-box-disc'><p>" .htmlspecialchars($row['name'])."</p></div>");
                 echo ("</div>");
             }
         } else {
             echo "no result";
         }
     } else {
         echo "Failed: " . $conn->error;
     }
     $conn->close();
     ?>
            </div>
    </div> 
    
  

 <script type="text/javascript" src="src/Js/priceSlider.js"></script>

<!-- Chat Button and Container -->
<div class="chat-button" onclick="toggleChat()">
    <i class="fa fa-comment" style="font-size: 30px; color: white;"></i>
</div>

<div class="chat-container" id="chatContainer">
    <div class="chat-header">
        <span>AI Assistant</span>
        <button class="close-chat" onclick="toggleChat()">×</button>
    </div>
    <div class="chat-messages" id="chatMessages">
        <!-- Default Messages -->
        <div class="message bot-message">
            Hello! How can I assist you today?
        </div>
        <div class="message bot-message">
            You can ask about products, prices, or anything else!
        </div>
    </div>
    <div class="chat-input">
        <input type="text" id="userInput" placeholder="Type your message...">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
// State to track if the bot is waiting for an item name
let waitingForItemName = false;

function toggleChat() {
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.style.display = 
        chatContainer.style.display === 'flex' ? 'none' : 'flex';
}

function sendMessage() {
    const input = document.getElementById('userInput');
    const messages = document.getElementById('chatMessages');
    const message = input.value.trim();

    if (message) {
        // Add user message
        const userMsg = document.createElement('div');
        userMsg.className = 'message user-message';
        userMsg.textContent = message;
        messages.appendChild(userMsg);

        // Get bot response
        const botResponse = getBotResponse(message);
        if (botResponse) {
            // If botResponse is a string, display it directly
            const botMsg = document.createElement('div');
            botMsg.className = 'message bot-message';
            botMsg.textContent = botResponse;
            messages.appendChild(botMsg);
        }

        // Clear input and scroll to bottom
        input.value = '';
        messages.scrollTop = messages.scrollHeight;
    }
}

function getBotResponse(message) {
    message = message.toLowerCase();

    // If the bot is waiting for an item name
    if (waitingForItemName) {
        waitingForItemName = false; // Reset the state
        fetchItemPrice(message); // Fetch the price for the item
        return null; // Return null to avoid displaying a response immediately
    }

    // Handle other messages
    if (message.includes('price')) {
        waitingForItemName = true;
        return 'Could you please specify which product you want the price for?';
    } else if (message.includes('hello') || message.includes('hi')) {
        return 'Hi there! How can I help you today?';
    } else if (message.includes('thanks') || message.includes('thank you')) {
        return 'You’re welcome! Anything else I can assist with?';
    } else {
        return 'I’m here to help! Could you please provide more details?';
    }
}

function fetchItemPrice(itemName) {
    const messages = document.getElementById('chatMessages');

    // Make an AJAX request to get the item price
    fetch('getItemPrice.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'item_name=' + encodeURIComponent(itemName)
    })
    .then(response => response.json())
    .then(data => {
        const botMsg = document.createElement('div');
        botMsg.className = 'message bot-message';
        
        if (data.success) {
            botMsg.textContent = `The price of ${data.item_name} is ${data.price} ${data.currency}.`;
        } else {
            botMsg.textContent = data.error || 'Sorry, I couldn’t find that item.';
        }
        
        messages.appendChild(botMsg);
        messages.scrollTop = messages.scrollHeight;
    })
    .catch(error => {
        const botMsg = document.createElement('div');
        botMsg.className = 'message bot-message';
        botMsg.textContent = 'Sorry, there was an error fetching the price. Please try again later.';
        messages.appendChild(botMsg);
        messages.scrollTop = messages.scrollHeight;
    });
}

// Allow Enter key to send message
document.getElementById('userInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
</script>

<?php include 'footer.php'?>