document.addEventListener('DOMContentLoaded', function () {
    const grandparentDropdown = document.getElementById('grandparent_category');
    const parentDropdown = document.getElementById('parent_category');
    const childDropdown = document.getElementById('child_category');
    const sizeDropdown = document.getElementById('size');

    // Function to populate a dropdown
   
    function populateDropdown(dropdown, data, defaultText, isSize = false) {
        dropdown.innerHTML = `<option value="">${defaultText}</option>`;
        data.forEach(item => {
            if (isSize) {
                dropdown.innerHTML += `<option value="${item.size_id}">${item.size_name}</option>`;
            } else {
                dropdown.innerHTML += `<option value="${item.category_id}">${item.category_name}</option>`;
            }
        });
    }

    // Function to filter categories by parent ID
    function filterByParent(parentId) {
        return categories.filter(cat => cat.parent_category_id == parentId);
    }

    // Event listener for grandparent dropdown
    grandparentDropdown.addEventListener('change', function () {
        const parentCategories = filterByParent(this.value);
        populateDropdown(parentDropdown, parentCategories, 'Select Parent Category');
        populateDropdown(childDropdown, [], 'Select Child Category');
        populateDropdown(sizeDropdown, [], 'Select Size');
    });

    // Event listener for parent dropdown
    parentDropdown.addEventListener('change', function () {
        const childCategories = filterByParent(this.value);
        populateDropdown(childDropdown, childCategories, 'Select Child Category');
        populateDropdown(sizeDropdown, [], 'Select Size');
    });

    // Event listener for child dropdown
    childDropdown.addEventListener('change', function () {
        const childId = this.value;
        if (childId) {
            // Find size IDs linked to the selected category
            const linkedSizeIds = sizecategories
                .filter(sc => sc.category_id == childId)
                .map(sc => sc.size_id);

                console.log("Linked Size IDs:", linkedSizeIds);
                

            // Find matching sizes
            const matchingSizes = sizes.filter(size => linkedSizeIds.includes(size.size_id));

            console.log("Matching Sizes:", matchingSizes);

            // Populate the size dropdown
            populateDropdown(sizeDropdown, matchingSizes, 'Select Size',true);
        } else {
            sizeDropdown.innerHTML = '<option value="">Select Size</option>';
            sizeDropdown.disabled = true;
        }
    });
});