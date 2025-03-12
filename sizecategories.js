// Function to filter categories by parent ID
function filterByParent(parentId) {
    return categories.filter(cat => cat.parent_category_id == parentId);
}

function populateDropdown(id, data, defaultText){
    const dropdown = document.getElementById(id);
    dropdown.innerHTML = `<option value="">${defaultText}</option>`;
    data.forEach(d => dropdown.innerHTML += `<option value="${d.category_id}">${d.category_name}</option>`);
    dropdown.disabled = data.length === 0;
}

document.getElementById('grandparent_category').addEventListener('change', function () {
    populateDropdown('parent_category', filterByParent(this.value), 'Select Parent Category');
    populateDropdown('child_category', [], 'Select Child Category');
    populateDropdown('size', [], 'Select Size');   
});

document.getElementById('parent_category').addEventListener('change', function () {
    populateDropdown('child_category', filterByParent(this.value), 'Select Child Category');
    populateDropdown('size', [], 'Select Size');
});

document.getElementById('child_category').addEventListener('change', function () {
    const childId = this.value;
    const sizeDropdown = document.getElementById('size');
   
    if (childId) {
        // Find size IDs linked to the selected category
        const linkedSizeIds = sizecategories
            .filter(sc => sc.category_id == childId)
            .map(sc => sc.size_id);

        // Find matching sizes
        const matchingSizes = sizes.filter(size => linkedSizeIds.includes(size.size_id));

        populateDropdown('size', matchingSizes, 'Select Size');
    } else {
        sizeDropdown.innerHTML = '<option value="">Select Size</option>';
        sizeDropdown.disabled = true;
    }
});




