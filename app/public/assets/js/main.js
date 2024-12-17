document.addEventListener("DOMContentLoaded", function () {
    function filterDropdown() {
        const input = document.getElementById('dropdownSearch');
        const filter = input.value.toLowerCase();
        const items = document.querySelectorAll('#dropdownItems .dropdown-item');

        items.forEach(item => {
            const text = item.textContent || item.innerText;
            if (text.toLowerCase().includes(filter)) {
                item.parentElement.style.display = ""; // Show item
            } else {
                item.parentElement.style.display = "none"; // Hide item
            }
        });
    }

    // Expose the function globally if needed (for the inline `onkeyup` handler)
    window.filterDropdown = filterDropdown;
});

document.addEventListener("DOMContentLoaded", function () {
    const dropdownItemsContainer = document.getElementById('dropdownItems');
    const itemList = document.getElementById('itemList');

    // Event delegation for dropdown items
    dropdownItemsContainer.addEventListener('click', function (event) {
        event.preventDefault();

        // Find the closest anchor tag
        const target = event.target.closest('.dropdown-item');
        if (target) {
            // Get the item name and image source
            const itemName = target.innerText.trim();
            const itemIcon = target.querySelector('img') ? target.querySelector('img').src : '';

            // Create a new list item
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex align-items-center justify-content-between border-0';

            // Add image and name to the list item
            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${itemIcon}" alt="icon" style="width: 50px; height: 50px; margin-right: 10px;">
                    <span>${itemName}</span>
                </div>
                <button class="btn btn-sm btn-danger">Delete</button>
            `;

            // Add delete functionality
            listItem.querySelector('.btn-danger').addEventListener('click', function () {
                listItem.remove();
            });

            // Append the new item to the list
            itemList.appendChild(listItem);
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    let isDropdownLoaded = false;
    const addItemBtn = document.getElementById("addItemBtn");
    const dropdownItemsContainer = document.getElementById("dropdownItems");

    addItemBtn.addEventListener("click", () => {
        if (isDropdownLoaded) return;

        // PHP Array Loaded on Page Render (Inline)
        const items = window.loadedItems; // Injected from PHP

        if (!Array.isArray(items) || items.length === 0) return;

        dropdownItemsContainer.innerHTML = "";

        // Group and sort the items
        const groupedItems = groupAndSortItems(items);

        // Fill the dropdown
        for (const [type, items] of Object.entries(groupedItems)) {
            const header = document.createElement("h6");
            header.className = "dropdown-header";
            header.textContent = type;
            dropdownItemsContainer.appendChild(header);

            items.forEach(item => {
                const li = document.createElement("li");
                li.innerHTML = `
                    <a class="dropdown-item" href="#">
                        <img src="/assets/images/${item.icon_name}" alt="icon" 
                             style="width: 50px; height: 50px; margin-right: 10px;">
                        ${item.display_name}
                    </a>`;
                dropdownItemsContainer.appendChild(li);
            });
        }

        // Mark dropdown as loaded
        isDropdownLoaded = true;
    });

    /**
     * Groups and sorts items by type and name.
     * @param {Array} items - List of items from the server
     * @returns {Object} - Grouped and sorted items
     */
    function groupAndSortItems(items) {
        const groupedItems = {};

        // Group by type
        items.forEach(item => {
            if (!groupedItems[item.type]) {
                groupedItems[item.type] = [];
            }
            groupedItems[item.type].push(item);
        });

        // Sort each group alphabetically by display_name
        for (const type in groupedItems) {
            groupedItems[type].sort((a, b) => a.display_name.localeCompare(b.display_name));
        }

        return groupedItems;
    }
});
