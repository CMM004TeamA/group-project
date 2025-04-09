document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
    element.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default link behavior
        e.stopPropagation(); // Stop the event from bubbling up
        const submenu = this.nextElementSibling;
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    });
});

// Close dropdowns when clicking outside
document.addEventListener('click', function (e) {
    document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function (submenu) {
        if (!submenu.parentElement.contains(e.target)) {
            submenu.style.display = 'none';
        }
    });
});