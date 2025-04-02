document.addEventListener('DOMContentLoaded', function() {
    const searchResultsSection = document.getElementById('searchResultsSection');
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const itemContainer = document.getElementById('itemContainer');
    const recentItemsSection = document.querySelector('.row.g-4'); // Section with recently added items

    // Handle form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    // Search as you type with debounce
    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            if (searchInput.value.trim().length > 0) {
                performSearch();
            } else {
                // When search is empty, hide results and show recent items
                searchResultsSection.style.display = 'none';
                if (recentItemsSection) {
                    recentItemsSection.style.display = 'flex';
                }
            }
        }, 300);
    });

    function performSearch() {
        const searchTerm = searchInput.value.trim();

        if (searchTerm.length === 0) {
            searchResultsSection.style.display = 'none';
            if (recentItemsSection) {
                recentItemsSection.style.display = 'flex';
            }
            return;
        }

        // Show loading state
        itemContainer.innerHTML = '<p class="text-center">Searching...</p>';
        searchResultsSection.style.display = 'block';
        if (recentItemsSection) {
            recentItemsSection.style.display = 'none';
        }

        fetch(`search.php?searchTerm=${encodeURIComponent(searchTerm)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(items => {
                console.log('Received items:', items); // Debug log
                itemContainer.innerHTML = '';

                if (items.length === 0) {
                    itemContainer.innerHTML = '<p class="text-center">No items found matching your search.</p>';
                    return;
                }

                // Display items
                items.forEach(item => {
                    const imagePath = item.image_path;
                    
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'col-md-6 col-lg-3 mb-4';
                    itemDiv.innerHTML = `
                        <div class="card">
                            <a href="item.php?id=${item.item_id}" class="text-decoration-none text-dark">
                                <img src="${imagePath}" class="card-img-top" alt="${item.title}" 
                                     
                                <div class="card-body">
                                    <h5 class="card-title">${item.title}</h5>
                                </div>
                            </a>
                        </div>
                    `;
                    itemContainer.appendChild(itemDiv);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                itemContainer.innerHTML = '<p class="text-danger">Error loading search results. Please try again.</p>';
            });
    }
});