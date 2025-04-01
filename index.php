<?php 

include 'header.php';
// print_r($_SESSION); die();
require_once 'config.php';





?>


	<!-- css -->
	<link rel="stylesheet" type="text/css" href="src/css/home.css">
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
	<!-- slider start -->

	<div class="sliderbox">
		

		<img src="" width="1360" height="450" id="slider">
	</div>


	<!-- slider end -->
	</div>


	<center><img src="src/img/divider.png" style="margin: 30px 0px 10px 0px;"></center>


<!-- item-box-start -->

<!-- box-1 -->


<?php 
	$sql="SELECT * FROM item WHERE size='s'";

	if($result=$conn->query($sql)){

	if($result->num_rows>0){


		while($row=$result->fetch_assoc()){


			echo ("<div class='item-box' style='background-image: url(".$row['image'].");'>");

			echo ("<div class='item-box-overlay'>");

			echo ("<center><a href='itemDetails.php?id=".$row['item_code']."'><button class='item-view-button'>View Item</button></a></center>");


			echo ("</div>");

			echo ("<div class='item-box-disc'><p>" .$row['name']."</p></div>");

			echo ("</div>");


		}



	}else
		echo "no result";
		//no rows







}else
	echo "Failed";;//queryfailed

	
?>
		

<div class="clrfix"></div>


<!-- Bot menue box -->


<div class="bot-menue">

	<!-- box-1-start -->
	
	<div class="bot-menu-box-1">
			

		<div class="item-box-overlay">



			<center><a href="menItem.php"><button class="item-view-button">Mens</button></a></center>


		</div>

	</div>
	<!-- box-1-end -->

	<!-- box-2-start -->
	<div class="bot-menu-box-2">
		
		<div class="item-box-overlay">



			<center><a href="womenItem.php"><button class="cat-view-button">Women</button></a></center>


		</div>

	</div>

	<!-- box-2-end -->
	

	<!-- box-3-start -->
	<div class="bot-menu-box-3">
		

		<div class="item-box-overlay">



			<center><a href="kidItems.php"><button class="cat-view-button">Kids</button></a></center>


		</div>

	</div>

	<!-- box-3-end -->

</div>


		
<!-- scripts -->
	<script type="text/javascript" src="src/js/slider.js"></script>


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
        <input type="text" id="userInput" placeholder="Type your message..." autocomplete="off">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<style>
    .chat-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background-color: #fe4253;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 1000;
    }
    .chat-container {
        display: none;
        flex-direction: column;
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 350px;
        height: 450px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        z-index: 1000;
    }
    .chat-header {
        background-color: #fe4253;
        color: white;
        padding: 10px;
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
    }
    .chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
    }
    .message {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
        max-width: 80%;
    }
    .bot-message {
        background-color: #e9ecef;
    }
    .user-message {
        background-color: #fe4253;
        color: white;
        margin-left: auto;
    }
    .chat-input {
        display: flex;
        padding: 10px;
        border-top: 1px solid #eee;
    }
    .chat-input input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px 0 0 5px;
        outline: none;
    }
    .chat-input button {
        padding: 8px 15px;
        background-color: #fe4253;
        color: white;
        border: none;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
    }
</style>

<script>
    // Item data from the `item` table (unchanged)
    const items = [
        { item_code: 1, name: "BLACK AMANI WOMEN TROUSER", unit_price: 3500, type: "PANTS", category: "WOMEN", item_desc: "Upgrade your formal trouser collection with our wide range of Womens formal trousers. Item features in a stretch jersey with elasticated waist band and side pockets.", stock: 50, size: "XL" },
        { item_code: 2, name: "WHITE JOBBS PRINTED SHIRT", unit_price: 4990, type: "SHIRT", category: "MEN", item_desc: "A basic Mens bold striped casual shirt with short sleeves, shirt collar and full front button fastening.", stock: 27, size: "S" },
        { item_code: 3, name: "RED GIVO SHELLEY CUT AWAY DRES", unit_price: 3500, type: "DRESS", category: "WOMEN", item_desc: "Get this effortlessly stylist look. Inspired with vintage floral bold prints made in a cut away neckline silhouette.", stock: 50, size: "XL" },
        { item_code: 4, name: "WHITE JOBBS PRINTED SHIRT", unit_price: 3500, type: "SHIRT", category: "MEN", item_desc: "A basic Mens bold striped casual shirt with short sleeves, shirt collar and full front button fastening.", stock: 50, size: "XL" },
        { item_code: 5, name: "RED GIVO SHELLEY CUT AWAY DRES", unit_price: 3500, type: "DRESS", category: "WOMEN", item_desc: "Get this effortlessly stylist look. Inspired with vintage floral bold prints made in a cut away neckline silhouette.", stock: 49, size: "S" },
        { item_code: 6, name: "BLACK AMANI WOMEN TROUSER", unit_price: 3500, type: "PANTS", category: "WOMEN", item_desc: "Upgrade your formal trouser collection with our wide range of Womens formal trousers. Item features in a stretch jersey with elasticated waist band and side pockets.", stock: 22, size: "S" },
        { item_code: 7, name: "BLUE BUG JUNIOR SHORTS", unit_price: 1500, type: "PANTS", category: "KIDS", item_desc: "A comfy twill pant to pair with absolutely anything. From shirts to t-shirt this casual twill shorts will give them ease of movement.", stock: 3, size: "S" },
        { item_code: 8, name: "BLUE BUG JUNIOR SHORTS", unit_price: 1500, type: "PANTS", category: "KIDS", item_desc: "A comfy twill pant to pair with absolutely anything. From shirts to t-shirt this casual twill shorts will give them ease of movement.", stock: 20, size: "XL" },
        { item_code: 9, name: "WHITE VANTAGE UBER MENS FORMAL", unit_price: 4990, type: "SHIRT", category: "MEN", item_desc: "This versatile shirt from Vantage Uber is made with an comfortable 100% cotton stripe fabric. and finished with quality fine finishing. complement it with a matching tie.", stock: 35, size: "S" },
        { item_code: 10, name: "WHITE VANTAGE UBER MENS FORMAL", unit_price: 4990, type: "SHIRT", category: "MEN", item_desc: "This versatile shirt from Vantage Uber is made with an comfortable 100% cotton stripe fabric. and finished with quality fine finishing. complement it with a matching tie.", stock: 35, size: "M" },
        { item_code: 11, name: "WHITE VANTAGE UBER MENS FORMAL", unit_price: 4990, type: "SHIRT", category: "MEN", item_desc: "This versatile shirt from Vantage Uber is made with an comfortable 100% cotton stripe fabric. and finished with quality fine finishing. complement it with a matching tie.", stock: 35, size: "XL" },
        { item_code: 13, name: "AMANI ESTHER FRILL DETAILED PE", unit_price: 1890, type: "BLOUSE", category: "WOMEN", item_desc: "Frill panel detailed peplum inspired sun top with adjustable spaghetti straps.", stock: 20, size: "S" },
        { item_code: 14, name: "GIVO HEYL FRONT TWIST TIE SLEE", unit_price: 1390, type: "BLOUSE", category: "WOMEN", item_desc: "Front twist tie up detailed top in a sleeveless silhouette, collar. and front button fastening", stock: 20, size: "S" },
        { item_code: 15, name: "AMANI AARYA GATHERED NECK DETA", unit_price: 2490, type: "BLOUSE", category: "WOMEN", item_desc: "Elegant smart casual blouse with gathered neck detail and short puff sleeves", stock: 100, size: "M" },
        { item_code: 16, name: "AMANI DIXIE SMOCKED SHORT SLEE", unit_price: 2490, type: "BLOUSE", category: "WOMEN", item_desc: "Inspired with ditsy florals this pretty smoked top features short puff sleeves and a frill hemline", stock: 50, size: "L" },
        { item_code: 17, name: "AMANI BLESSICA PLEATED RUFFLE", unit_price: 1450, type: "BLOUSE", category: "WOMEN", item_desc: "Add a splash of solid colors to your smart casual wardrobe item featuring a pleated ruffle neckline along with a front keyhole detail and pretty frill hem.", stock: 56, size: "M" },
        { item_code: 18, name: "CRISET FRONT TWIST TIE CROP TO", unit_price: 1450, type: "BLOUSE", category: "WOMEN", item_desc: "Simple elegance with a minimal look featured in a breathable in a breathable georgette this crop top features a front twist tie.", stock: 45, size: "L" },
        { item_code: 22, name: "LADIES COTTON TROUSER - BLACK", unit_price: 1890, type: "PANTS", category: "WOMEN", item_desc: "Ladies slim fit stretchable cotton pants for the stylish diva for you", stock: 1, size: "S" },
        { item_code: 23, name: "AMANI TIE UP SLEEVE LINEN JUMP", unit_price: 2590, type: "DRESS", category: "WOMEN", item_desc: "Perfect for holidays and warm weekend, our linen blend jumpsuit is perfect for dressing up or down whatever the occasion. Item featuring a tie up sleeveless pattern in a flattering shape, curved hem and side pockets.", stock: 20, size: "S" },
        { item_code: 25, name: "AMANI SOORYA FLORAL PRINTED SL", unit_price: 2890, type: "DRESS", category: "WOMEN", item_desc: "Pretty floral inspired maxi length sun dress with adjustable spaghetti straps, side pockets and self fabric waist belt.", stock: 19, size: "S" },
        { item_code: 26, name: "JOBBS SELECT MENS GRAPHIC PRIN", unit_price: 1390, type: "T-SHIRT", category: "MEN", item_desc: "Inspired with range of pattern in tie dye concept this casual crew neck t-shirt is perfect for that summer heat", stock: 17, size: "S" },
        { item_code: 27, name: "AMY GIRLS LINEN SHIRT DRESS(AG", unit_price: 1930, type: "T-SHIRT", category: "KIDS", item_desc: "Delighful long sleeve shirt dress in a cotton linen blend with decorative self fabric waist bow tie in front. Available in a range of colors to choose form.", stock: 60, size: "S" },
        { item_code: 28, name: "JOBBS SELECT MENS CONTRAST PAN", unit_price: 2650, type: "T-SHIRT", category: "MEN", item_desc: "Upgrade your polo collection with our wide range of mens polo shirts from Jobbs Select. Featured in a soft and breathable pique fabric with ribbed contrast panel polo collar along with two button fastening placket, short sleeves and ribbed sleeve hem panel.", stock: 33, size: "S" },
        { item_code: 29, name: "BOYS PRINTED CASUAL SHIRT", unit_price: 1100, type: "T-SHIRT", category: "KIDS", item_desc: "Dress them up in our selection of casual printed shirt range. It features a classic shirt style with short sleeves, a full front button down placket and basic shirt collar. Style it up in shorts and a cute hat for that weekend getaway.", stock: 4, size: "S" },
        { item_code: 30, name: "AMANI AARYA GATHERED NECK DETA", unit_price: 2490, type: "BLOUSE", category: "WOMEN", item_desc: "Elegant smart casual blouse with gathered neck detail and short puff sleeves", stock: 100, size: "S" },
        { item_code: 31, name: "AMANI DIXIE SMOCKED SHORT SLEE", unit_price: 2490, type: "BLOUSE", category: "WOMEN", item_desc: "Inspired with ditsy florals this pretty smoked top features short puff sleeves and a frill hemline", stock: 50, size: "S" },
        { item_code: 32, name: "AMANI BLESSICA PLEATED RUFFLE", unit_price: 1450, type: "BLOUSE", category: "WOMEN", item_desc: "Add a splash of solid colors to your smart casual wardrobe item featuring a pleated ruffle neckline along with a front keyhole detail and pretty frill hem.", stock: 56, size: "S" },
        { item_code: 33, name: "CRISET FRONT TWIST TIE CROP TO", unit_price: 1450, type: "BLOUSE", category: "WOMEN", item_desc: "Simple elegance with a minimal look featured in a breathable in a breathable georgette this crop top features a front twist tie.", stock: 45, size: "S" },
        { item_code: 34, name: "BUG JUNIOR BOYS LINEN SHIRT", unit_price: 2000, type: "T-SHIRT", category: "KID", item_desc: "Even kids love the comfort of a linen shirt. Made with a rich blend of cotton linen this trendy shirt features an basic shirt collar along with a full front button fastening and long shirt sleeves with cuffs", stock: 12, size: "S" }
    ];

    function toggleChat() {
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.style.display = 
            chatContainer.style.display === 'flex' ? 'none' : 'flex';
        if (chatContainer.style.display === 'flex') {
            document.getElementById('userInput').focus();
        }
    }

    function sendMessage() {
        const input = document.getElementById('userInput');
        const messages = document.getElementById('chatMessages');
        const message = input.value.trim();

        if (!message) return;

        // Add user message
        const userMsg = document.createElement('div');
        userMsg.className = 'message user-message';
        userMsg.textContent = message;
        messages.appendChild(userMsg);

        // Add bot response
        const botMsg = document.createElement('div');
        botMsg.className = 'message bot-message';
        botMsg.textContent = getBotResponse(message);
        messages.appendChild(botMsg);

        // Clear input and scroll to bottom
        input.value = '';
        messages.scrollTop = messages.scrollHeight;
    }

    // Fixed bot response logic
    function getBotResponse(message) {
        // Remove quotes and normalize message
        const cleanMessage = message.replace(/['"]/g, '').toLowerCase();

        // Greetings
        if (cleanMessage.includes('hello') || cleanMessage.includes('hi')) {
            return 'Hi there! How can I help you today?';
        }
        if (cleanMessage.includes('thanks') || cleanMessage.includes('thank you')) {
            return 'You’re welcome! Anything else I can assist with?';
        }

        // Price queries
        if (cleanMessage.includes('price') || cleanMessage.includes('cost')) {
            let productQuery = cleanMessage.split(/price|cost/).pop().trim();
            if (!productQuery) {
                return 'Please specify which product you want the price for!';
            }
            const matches = items.filter(item => item.name.toLowerCase().includes(productQuery));
            if (matches.length === 0) {
                return `Sorry, I couldn’t find any product matching "${productQuery}". Try asking about something like "BLACK AMANI WOMEN TROUSER"!`;
            }
            if (matches.length === 1) {
                return `The price of ${matches[0].name} (Size ${matches[0].size}) is RS.${matches[0].unit_price}.00. Stock: ${matches[0].stock} available.`;
            }
            return `I found multiple matches for "${productQuery}":\n` + 
                   matches.map(item => `${item.name} (Size ${item.size}): RS.${item.unit_price}.00, Stock: ${item.stock}`).join('\n');
        }

        // Stock queries
        if (cleanMessage.includes('stock') || cleanMessage.includes('availability')) {
            let productQuery = cleanMessage.split(/stock|availability/).pop().trim();
            if (!productQuery) {
                return 'Please specify which product you want to check stock for!';
            }
            const matches = items.filter(item => item.name.toLowerCase().includes(productQuery));
            if (matches.length === 0) {
                return `Sorry, I couldn’t find any product matching "${productQuery}".`;
            }
            if (matches.length === 1) {
                return `${matches[0].name} (Size ${matches[0].size}) has ${matches[0].stock} items in stock.`;
            }
            return `Stock for "${productQuery}":\n` + 
                   matches.map(item => `${item.name} (Size ${item.size}): ${item.stock} available`).join('\n');
        }

        // Description queries
        if (cleanMessage.includes('about') || cleanMessage.includes('details') || cleanMessage.includes('description')) {
            let productQuery = cleanMessage.split(/about|details|description/).pop().trim();
            if (!productQuery) {
                return 'Please specify which product you want details about!';
            }
            const matches = items.filter(item => item.name.toLowerCase().includes(productQuery));
            if (matches.length === 0) {
                return `Sorry, I couldn’t find any product matching "${productQuery}".`;
            }
            if (matches.length === 1) {
                return `${matches[0].name} (Size ${matches[0].size}): ${matches[0].item_desc}`;
            }
            return `Details for "${productQuery}":\n` + 
                   matches.map(item => `${item.name} (Size ${item.size}): ${item.item_desc}`).join('\n');
        }

        // Category/Type queries
        if (cleanMessage.includes('show') || cleanMessage.includes('list')) {
            if (cleanMessage.includes('women')) {
                const womenItems = items.filter(item => item.category.toLowerCase() === 'women');
                return `Here are some women’s items:\n` + 
                       womenItems.slice(0, 5).map(item => `${item.name} (RS.${item.unit_price}.00)`).join('\n') + 
                       (womenItems.length > 5 ? '\n...and more!' : '');
            }
            if (cleanMessage.includes('men')) {
                const menItems = items.filter(item => item.category.toLowerCase() === 'men');
                return `Here are some men’s items:\n` + 
                       menItems.slice(0, 5).map(item => `${item.name} (RS.${item.unit_price}.00)`).join('\n') + 
                       (menItems.length > 5 ? '\n...and more!' : '');
            }
            if (cleanMessage.includes('kids') || cleanMessage.includes('kid')) {
                const kidsItems = items.filter(item => item.category.toLowerCase() === 'kid' || item.category.toLowerCase() === 'kids');
                return `Here are some kids’ items:\n` + 
                       kidsItems.slice(0, 5).map(item => `${item.name} (RS.${item.unit_price}.00)`).join('\n') + 
                       (kidsItems.length > 5 ? '\n...and more!' : '');
            }
        }

        // Check if the message matches a product name directly
        const directMatches = items.filter(item => item.name.toLowerCase().includes(cleanMessage));
        if (directMatches.length > 0) {
            if (directMatches.length === 1) {
                return `Here’s info on ${directMatches[0].name} (Size ${directMatches[0].size}): Price: RS.${directMatches[0].unit_price}.00, Stock: ${directMatches[0].stock}, Description: ${directMatches[0].item_desc}`;
            }
            return `I found multiple matches for "${cleanMessage}":\n` + 
                   directMatches.map(item => `${item.name} (Size ${item.size}): RS.${item.unit_price}.00, Stock: ${item.stock}`).join('\n');
        }

        return 'I’m here to help! You can ask about product prices, stock, or details. Try something like "price of BLACK AMANI WOMEN TROUSER"!';
    }

    // Allow Enter key to send message
    document.getElementById('userInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
</script>

<?php include 'footer.php';?>
