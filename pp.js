// Home Button
document.getElementById('homeBtn').addEventListener('click', function() {
window.location.href = 'home.html'; // Replace with your actual home page
});

// Cart Button
document.getElementById('cartBtn').addEventListener('click', function() {
    window.location.href = 'cart.html'; // Replace with your cart page
    });

    // Support Button
document.getElementById('supportBtn').addEventListener('click', function() {
    window.location.href = 'support.html'; // Replace with your support page
    });

    // Logout Button
document.getElementById('logoutBtn').addEventListener('click', function() {
    alert('You have successfully logged out!');
    window.location.href = 'index.html'; // Replace with your login page
    });

    // List of items for search
    const items = [
    "Shoes",
    "Winter Jacket",
"Trousers",
"Bags",
"Gowns",
"Jewelry",
"Tops"
];

//Search function
function searchItems() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const results = Items.filter(item => item.toLowerCase().includes(query));

    const resultsDiv = document.getElementById('searchResults');
    resultsDiv.innerHTML ='';

    if (results.length > 0) {
        results.forEach(item => {
            const p = document.createElement('p');
            p.textContent = item;
            resultsDiv.appendChild(p);
        });
    } else {
        resultsDiv.textContent = 'No results found';
    }
    }

    //Add event listener to the search button
    document.getElementById('searchBtn').addEventListener('click',searchItems);