let shoppingCart = document.querySelector('#shopping-cart');
let cartContainer = document.querySelector('.cart-container');
let body = document.querySelector('body');
let listCart = document.querySelector('.list-cart');
let total = document.querySelector('.total');
let quantity = document.querySelector('.quantity');
let listWishlist = document.querySelector('.list-wishlist');


// Toggle the cart visibility when the shopping cart icon is clicked
shoppingCart.addEventListener('click', () => {
    body.classList.toggle('active');
}); 

// Initialize an empty cart array
let listCards = [];

// Add product to the cart and send data to the server
function addToCart(productId, name, price, image, stockQty) {
    fetch('../customer/add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            name: name,
            price: price,
            image: image,
            stock_qty:stockQty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update local cart state
            let index = listCards.findIndex(item => item.id === productId);
            if (index === -1) {
                listCards.push({ id: productId, name: name, price: price, quantity: 1, image: image });
            } else if (data.error === "stock_limit"){
                alert("Can't add more! Stock Limit Reached!");
            }else {
                listCards[index].quantity += 1;
            }
            //reloadcart();
            loadCart();
        } else {
            alert('Could not add to cart!');
        }
    });
}

// Reload the cart UI with updated data



function loadCart() {
    fetch('../customer/load_cart.php')  // Call the PHP script to fetch cart items
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error(data.error); // Handle errors
            return;
        }
        
        // Populate the cart with fetched data
        listCart.innerHTML = ''; // Clear current cart
        let count = 0;
        let totalPrice = 0;

        // If no cart items, display a message
        if (data.items.length === 0) {
            listCart.innerHTML = `<p class="empty-cart">Your cart is empty!</p>`;
            total.innerText = '0 ৳';
            document.querySelector('.checkout-container').style.display = 'none';
            return;
        }

        document.querySelector('.checkout-container').style.display = 'grid';  // Show checkout options

        data.items.forEach((item) => {
            totalPrice += item.price * item.quantity;
            count += item.quantity;

            let isPlusDisabled = item.quantity >= item.stock_qty ? 'disabled' : '';

            let cartItem = document.createElement('li');
            cartItem.innerHTML = `
                <div><img src="${item.image}" alt="${item.name}" class="cart-item-image"/></div>
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-price">${item.price} BDT</div>
                <div class="cart-actions">
                    <button class="qty-minus" data-id="${item.id}" data-stock="${item.stock_qty}">-</button>
                    <div class="count">${item.quantity}</div>
                    <button class="qty-plus" data-id="${item.id}" data-stock="${item.stock_qty}" ${isPlusDisabled}>+</button>
                </div>
            `;
            listCart.appendChild(cartItem);
        });

        total.innerText = `${totalPrice.toLocaleString()} BDT`;
        quantity.innerText = count;

        bindQuantityButtons();
    })
    .catch(err => console.error('Error loading cart:', err));  // Handle any errors during the fetch call
}




function bindQuantityButtons() {
    document.querySelectorAll('.qty-minus').forEach(button => {
        button.onclick = () => {
            const productId = parseInt(button.dataset.id);
            const stockQty = parseInt(button.dataset.stock);

            const countDiv = button.parentElement.querySelector('.count');
            const currentQty = parseInt(countDiv.textContent);

            if (currentQty > 1) {
                changeQuantity(productId, currentQty - 1, stockQty);
            }
        };
    });

    document.querySelectorAll('.qty-plus').forEach(button => {
        button.onclick = () => {
            const productId = parseInt(button.dataset.id);
            const stockQty = parseInt(button.dataset.stock);
            const currentQty = parseInt(button.parentElement.querySelector('.count').textContent);

            if (currentQty >= stockQty) {
                alert("Stock limit reached! You can't add more.");
                return;
            }

            changeQuantity(productId, currentQty + 1, stockQty);
        };
    });
}




// Change the quantity of an item in the cart
function changeQuantity(productId, newQuantity, stockQty = null) {

    if (newQuantity <= 0 || (stockQty && newQuantity > stockQty)) {
        alert("You can't add more than available stock!");
        return;
    }
    
        fetch('../customer/update-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                new_quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If the update was successful, reload the cart to show updated data
                //reloadCart();
                loadCart();
            } else {
                alert('Failed to update cart!');
            }
        });
}


document.addEventListener('DOMContentLoaded', function () {
    loadCart();
});



























