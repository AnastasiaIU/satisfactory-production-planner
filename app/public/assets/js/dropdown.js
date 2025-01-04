let isDropdownLoaded = false;

const categoryOrder = [
    "Raw Resources",
    "Tier 0",
    "Tier 2",
    "Tier 3",
    "Tier 4",
    "Tier 5",
    "Tier 6",
    "Tier 7",
    "Tier 8",
    "Tier 9",
    "MAM",
    "Equipment"
];

/**
 * Sets up an event listener to clear the search input and reset the dropdown items when the dropdown is closed.
 */
function addClearDropdownOnClose() {
    const dropdown = document.getElementById("outputsDropdown");
    const searchInput = document.getElementById("dropdownSearch");

    // Add an event listener to clear the search input and reset the dropdown items when the dropdown is closed
    dropdown.addEventListener("hide.bs.dropdown", () => {
        if (searchInput) {
            searchInput.value = "";
            filterDropdown(); // Reset the dropdown items
        }
    });
}

/**
 * Retrieves all items under a given category element.
 *
 * @param {HTMLElement} category - The category element whose items are to be retrieved.
 * @returns {HTMLElement[]} An array of item elements under the specified category.
 */
function getCategoryItems(category) {
    const categoryItems = [];
    let nextElement = category.nextElementSibling;

    // Collect all items under this category
    while (nextElement && nextElement.tagName === "LI") {
        categoryItems.push(nextElement);
        nextElement = nextElement.nextElementSibling;
    }

    return categoryItems;
}

/**
 * Determines the visibility of a dropdown item based on the search input.
 *
 * @param {HTMLElement} item - The dropdown item element to check.
 * @param {string} input - The search input string to filter the items.
 * @returns {boolean} True if the item is visible, false otherwise.
 */
function getItemVisibility(item, input) {
    const text = item.textContent || item.innerText;
    const matchesFilter = text.toLowerCase().includes(input);
    const isInOutputList = item.querySelector(".dropdown-item").style.display === "none";
    const isVisible = matchesFilter && !isInOutputList;

    item.style.display = isVisible ? "" : "none";

    return isVisible;
}

/**
 * Displays or removes a "no results found" message based on the search results.
 *
 * @param {boolean} hasResults - Indicates if there are any matching results.
 * @param {HTMLElement} dropdownItemsContainer - The container element for the dropdown items.
 */
function toggleNoResultsMessage(hasResults, dropdownItemsContainer) {
    const noResultsMessage = document.getElementById("noResultsMessage");
    if (!hasResults) {
        if (!noResultsMessage) {
            const message = document.createElement("div");
            message.id = "noResultsMessage";
            message.className = "text-center text-muted mt-2";
            message.innerText = "No results found.";
            dropdownItemsContainer.appendChild(message);
        }
    } else {
        if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }
}

/**
 * Filters the dropdown items based on the search input and updates their visibility.
 * Also displays or hides a "no results found" message based on the search results.
 */
function filterDropdown() {
    const input = document.getElementById("dropdownSearch").value.toLowerCase();
    const dropdownItemsContainer = document.getElementById("dropdownItems");
    const categories = dropdownItemsContainer.querySelectorAll(".dropdown-header");
    let hasResults = false;

    categories.forEach((category) => {
        const categoryItems = getCategoryItems(category);
        let hasVisibleItems = false;

        categoryItems.forEach((item) => {
            if (getItemVisibility(item, input)) hasVisibleItems = true;
        });

        category.style.display = hasVisibleItems ? "" : "none";

        if (hasVisibleItems) hasResults = true;
    });

    toggleNoResultsMessage(hasResults, dropdownItemsContainer);
}

/**
 * Creates a new list item in the output list with the given item details.
 *
 * @param {string} itemIcon - The URL of the item's icon.
 * @param {string} itemName - The name of the item.
 * @returns {HTMLElement} The created list item element.
 */
function createListItem(itemIcon, itemName) {
    const listItem = document.createElement("li");
    listItem.className = "list-group-item card d-flex flex-column align-items-start border-0";
    listItem.innerHTML = `
        <div class="d-flex align-items-center">
            <img src="${itemIcon}" alt="icon" style="width: 50px; height: 50px; margin-right: 10px;">
            <span>${itemName}</span>
        </div>
        <div class="d-flex align-items-center p-0">
            <input type="number" class="form-control text-center quantity-input" value="1" min="0" step="1" style="width: 120px;">
        </div>
    `;

    return listItem;
}

/**
 * Adds an event listener to the quantity input of a list item to handle changes.
 * Removes the item if the quantity is zero or negative.
 *
 * @param {HTMLElement} listItem - The list item element containing the quantity input.
 * @param {HTMLElement} dropdownItemsContainer - The container element for the dropdown items.
 * @param {string} itemId - The ID of the item.
 */
function addEventListenerToItemQuantity(listItem, dropdownItemsContainer, itemId) {
    const quantityInput = listItem.querySelector(".quantity-input");

    quantityInput.addEventListener("change", () => {
        let currentValue = parseFloat(quantityInput.value) || 0;

        if (currentValue < 0) currentValue = 0;

        if (currentValue === 0) {
            // Remove the item from outputs
            listItem.remove();

            // Show the corresponding item in the dropdown again
            const dropdownItem = dropdownItemsContainer.querySelector(`[data-item-id="${itemId}"]`);
            if (dropdownItem) dropdownItem.style.display = "";

            // Remove the corresponding production graph
            const productionGraphContainer = document.getElementById("productionGraph");
            const graphElement = productionGraphContainer.querySelector(`[data-item-id="${itemId}"]`);
            if (graphElement) graphElement.remove();
        }
    });
}

/**
 * Adds a click event listener to the dropdown items container.
 * Handles the creation of a new list item in the output list and hides the clicked dropdown item.
 *
 * @param {HTMLElement} dropdownItemsContainer - The container element for the dropdown items.
 */
function addOnClickEventToDropdownItems(dropdownItemsContainer) {
    const outputsList = document.getElementById("outputsList");

    dropdownItemsContainer.addEventListener("click", (event) => {
        event.preventDefault();

        const target = event.target.closest(".dropdown-item");

        if (target) {
            const itemId = target.dataset.itemId;
            const itemName = target.innerText.trim();
            const itemIcon = target.querySelector("img")?.src || "";
            const listItem = createListItem(itemIcon, itemName);

            // Hide the dropdown item
            target.style.display = "none";

            // Append the new list item to the output list
            outputsList.appendChild(listItem);

            // Fetch and display the production graph
            displayProductionGraph(itemId);

            addEventListenerToItemQuantity(listItem, dropdownItemsContainer, itemId);
        }
    });
}

function addOnClickToAddItemBtn() {
    const addItemBtn = document.getElementById("addItemBtn");
    const dropdownItemsContainer = document.getElementById("dropdownItems");

    addItemBtn.addEventListener("click", async () => {
        if (isDropdownLoaded) return;

        const response = await fetch('/producibleItems');

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const items = await response.json();

        if (!Array.isArray(items) || items.length === 0) return;

        dropdownItemsContainer.innerHTML = "";
        const groupedItems = groupAndSortItems(items);

        for (const [category, items] of Object.entries(groupedItems)) {
            const header = document.createElement("h6");
            header.className = "dropdown-header";
            header.textContent = category;
            dropdownItemsContainer.appendChild(header);

            items.forEach((item) => {
                const li = document.createElement("li");
                li.innerHTML = `
                    <a class="dropdown-item" href="#" data-item-id="${item.id}">
                        <img src="/assets/images/${item.icon_name}" alt="icon"
                             style="width: 50px; height: 50px; margin-right: 10px;">
                        ${item.display_name}
                    </a>`;
                dropdownItemsContainer.appendChild(li);
            });
        }

        isDropdownLoaded = true;
    });
}

/** 4. Helper Function: Group and Sort Items **/
function groupAndSortItems(items) {
    const groupedItems = {};

    // Group items by category
    items.forEach((item) => {
        const category = item.category || "Uncategorized"; // Default category if missing
        if (!groupedItems[category]) groupedItems[category] = [];
        groupedItems[category].push(item);
    });

    // Sort items within each category by display_order
    for (const category in groupedItems) {
        groupedItems[category].sort((a, b) => a.display_order - b.display_order);
    }

    // Sort the categories based on the custom order
    const sortedGroupedItems = {};
    categoryOrder.forEach((category) => {
        if (groupedItems[category]) {
            sortedGroupedItems[category] = groupedItems[category];
        }
    });

    // Include any remaining categories not in categoryOrder at the end
    Object.keys(groupedItems)
        .filter((category) => !categoryOrder.includes(category))
        .sort()
        .forEach((remainingCategory) => {
            sortedGroupedItems[remainingCategory] = groupedItems[remainingCategory];
        });

    return sortedGroupedItems;
}