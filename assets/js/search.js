function searchItems(){
    const searchTerm=document.getElementById('searchInput').value.toLowerCase();
    const items=document.querySelectorAll('#itemContainer .col-md-4');
    items.forEach(item => {
        const itemText=item.textContent.toLowerCase();
        if (itemText.includes(searchTerm)) {
            item.style.display='';
        }
        else {
            item.style.display='none';
        }
    });
}