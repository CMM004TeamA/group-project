/*
 * function to handle add to cart button
 */
$(function () {
    // Set up the click handler for the add to cart button.
    $("#addToCart").click(function () {
        var itemId = $(this).data("item-id"); // Get the item ID from the button's data attribute

        if (confirm("Add this item to your cart?")) {
            var url = "addtocart.php"; // Request URL
            var data = { id: itemId };   // Request parameters as a map

            // Send Ajax request
            $.post(
                url,
                data,
                function (result) {
                    if (result.success) {
                        alert("Item added to cart successfully!");
                        // Update the cart counter
                        updateCartCounter(result.cartCount);
                    } else {
                        alert("Failed to add item to cart: " + (result.message || "Unknown error"));
                    }
                },
                "json" // Expected response type
            ).fail(function () {
                alert("An error occurred while processing your request.");
            });
        }
    });

    /*
    * Function to fetch cart count
    */
    function fetchCartCount() {
        $.get("getcartcount.php",
            function (result) {
                if (result.success) {
                    updateCartCounter(result.cartCount); // Update cart count
                } else {
                    if (result.message !== "You must be logged in to view your cart.") {
                        console.error("Failed to fetch cart count:", (result.message || "Unknown error"));
                    }
                }
            },
            "json" //Expected response type
        );
    }

    /*
     * Function to update the cart counter
     */
    function updateCartCounter(cartCount) {
        var cartCounter = $("#cart-counter"); // jQuery selector
        if (cartCounter.length) {
            cartCounter.text(cartCount); // Update the cart counter text
        }
    }

    /*
     * Function to handle the "Remove" button click.
     */
    $(document).on("click", ".remove-from-cart", function () {
        var itemId = $(this).data("item-id"); // Get the item ID from the button's data attribute
       
        if (confirm("Are you sure you want to remove this item from your cart?")) {
            // Send AJAX request
            $.post(
                "removefromcart.php", // URL of the PHP script
                { id: itemId }, // Data to send
                function (response) {
                    if (response.success) {
                        
                        alert("Item removed from cart successfully!");
                        
                        // Refresh the cart page
                        window.location.reload();
                    } else {
                        alert("Failed to remove item from cart: " + (result.message || "Unknown error"));
                    }
                },
                "json" // Expected response type
            ).fail(function () {
                alert("An error occurred while processing your request.");
                // Refresh the cart page
                window.location.reload();
            });
        }
    });

    $(function () {
        // Fetch the cart count when the page loads
        fetchCartCount();
    });
});

/*
* Function to handle the reserve all button
*/
$(function () {
    // Set up the click handler for the "Reserve All" button.
    $("#reserveAll").click(function () {
        if (confirm("Are you sure you want to reserve all items in your cart?")) {
            // Send AJAX request to reserve all items
            $.post(
                "reserveall.php", // URL of the PHP script
                function (result) {
                    if (result.success) {
                        alert("All items reserved successfully!");
                        // Optionally, refresh the cart page
                        window.location.reload();
                    } else {
                        alert("Failed to reserve items: " + (result.message || "Unknown error"));
                    }
                },
                "json" // Expected response type
            ).fail(function () {
                alert("An error occurred while processing your request.");
            });
        }
    });
});