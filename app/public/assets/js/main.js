document.addEventListener("DOMContentLoaded", () => {
    if (window.location.pathname === '/') {
        // Expose filter function globally for inline `onkeyup`
        window.filterDropdown = filterDropdown;

        const dropdownItemsContainer = document.getElementById("dropdownItems");

        addClearDropdownOnClose();
        addOnClickEventToDropdownItems(dropdownItemsContainer);
        addOnClickToAddItemBtn();
    }
});