let isDropdownLoaded = false;

// The order in which categories should be displayed in the dropdown
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
 * @param {HTMLElement} category The category element whose items are to be retrieved.
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
 * @param {HTMLElement} item The dropdown item element to check.
 * @param {string} input The search input string to filter the items.
 * @returns {boolean} True if the item is visible, false otherwise.
 */
function getItemVisibility(item, input) {
    const text = item.textContent || item.innerText;
    const matchesFilter = text.toLowerCase().includes(input);
    const isInOutputList = item.querySelector(".dropdown-item").style.display === "none";
    const isVisible = matchesFilter && !isInOutputList;

    changeVisibility(item, isVisible);

    return isVisible;
}

/**
 * Changes the visibility of an element based on the specified condition.
 *
 * @param {HTMLElement} element The element whose visibility is to be changed.
 * @param {boolean} isVisible Indicates if the element should be visible or hidden.
 */
function changeVisibility(element, isVisible) {
    if (isVisible) {
        element.classList.remove("hide-element");
        element.classList.add("show-element");
    } else {
        element.classList.remove("show-element");
        element.classList.add("hide-element");
    }
}

/**
 * Displays or removes a "no results found" message based on the search results.
 *
 * @param {boolean} hasResults Indicates if there are any matching results.
 * @param {HTMLElement} dropdownItemsContainer The container element for the dropdown items.
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

        changeVisibility(category, hasVisibleItems);

        if (hasVisibleItems) hasResults = true;
    });

    toggleNoResultsMessage(hasResults, dropdownItemsContainer);
}

/**
 * Creates a new list item in the output list with the given item details.
 *
 * @param {string} itemId The ID of the item.
 * @param {string} itemIcon The URL of the item's icon.
 * @param {string} itemName The name of the item.
 * @returns {HTMLElement} The created list item element.
 */
function createListItem(itemId, itemIcon, itemName) {
    const listItem = document.createElement("li");
    listItem.className = "list-group-item card d-flex flex-column align-items-start border-0";
    listItem.innerHTML = `
        <div class="d-flex align-items-center">
            <img src="${itemIcon}" alt="icon" style="width: 50px; height: 50px; margin-right: 10px;">
            <span>${itemName}</span>
        </div>
        <div class="d-flex align-items-center p-0">
            <input type="number" class="form-control text-center quantity-input mt-1" value="1" min="0" step="1" data-item-id="${itemId}">
        </div>
    `;

    return listItem;
}

/**
 * Adds an event listener to the quantity input of a list item to handle changes.
 * Removes the item if the quantity is zero or negative.
 *
 * @param {HTMLElement} listItem The list item element containing the quantity input.
 * @param {HTMLElement} dropdownItemsContainer The container element for the dropdown items.
 * @param {string} itemId The ID of the item.
 */
function addEventListenerToItemQuantity(listItem, dropdownItemsContainer, itemId) {
    const quantityInput = listItem.querySelector(".quantity-input");

    quantityInput.addEventListener("change", async () => {
        let currentValue = parseFloat(quantityInput.value) || 0;

        if (currentValue < 0) currentValue = 0;

        // Remove the corresponding production graph
        const productionGraphContainer = document.getElementById("productionGraph");
        const graphElement = productionGraphContainer.querySelector(`[data-item-id="${itemId}"]`);
        graphElement.remove();

        if (currentValue === 0) {
            // Remove the item from outputs
            listItem.remove();

            // Show the corresponding item in the dropdown again
            const dropdownItem = dropdownItemsContainer.querySelector(`[data-item-id="${itemId}"]`);
            changeVisibility(dropdownItem, true);
        } else {
            await displayProductionGraph(itemId);
        }
    });
}

/**
 * Adds a click event listener to the dropdown items container.
 * Handles the creation of a new list item in the output list and hides the clicked dropdown item.
 *
 * @param {HTMLElement} dropdownItemsContainer The container element for the dropdown items.
 */
function addOnClickEventToDropdownItems(dropdownItemsContainer) {
    const outputsList = document.getElementById("outputsList");

    dropdownItemsContainer.addEventListener("click", async (event) => {
        event.preventDefault();

        const target = event.target.closest(".dropdown-item");

        if (target) {
            const itemId = target.dataset.itemId;
            const itemName = target.innerText.trim();
            const itemIcon = target.querySelector("img")?.src || "";
            const listItem = createListItem(itemId, itemIcon, itemName);

            // Hide the dropdown item
            changeVisibility(target, false);

            // Append the new list item to the output list
            outputsList.appendChild(listItem);

            await displayProductionGraph(itemId);

            addEventListenerToItemQuantity(listItem, dropdownItemsContainer, itemId);
        }
    });
}

/**
 * Adds a category header element to the dropdown items container.
 *
 * @param {string} category The name of the category to be added as a header.
 * @param {HTMLElement} dropdownItemsContainer The container element for the dropdown items.
 */
function addCategoryHeader(category, dropdownItemsContainer) {
    const header = document.createElement("h6");
    header.className = "dropdown-header";
    header.textContent = category;
    dropdownItemsContainer.appendChild(header);
}

/**
 * Creates a dropdown item element and appends it to the dropdown items container.
 *
 * @param {Object} item The item object containing details for the dropdown item.
 * @param {HTMLElement} dropdownItemsContainer The container element for the dropdown items.
 */
function createDropdownItem(item, dropdownItemsContainer) {
    const li = document.createElement("li");
    li.innerHTML = `
                    <a class="dropdown-item" href="#" data-item-id="${item.id}">
                        <img src="/assets/images/${item.icon_name}" alt="icon"
                             style="width: 50px; height: 50px; margin-right: 10px;">
                        ${item.display_name}
                    </a>`;
    dropdownItemsContainer.appendChild(li);
}

/**
 * Adds an event listener to the "Add Item" button to fetch and display producible items in the dropdown.
 * Groups and sorts the items by category and display order.
 */
function addOnClickToAddItemBtn() {
    const addItemBtn = document.getElementById("addItemBtn");
    const dropdownItemsContainer = document.getElementById("dropdownItems");

    addItemBtn.addEventListener("click", async () => {
        if (isDropdownLoaded) return;

        const items = await fetchFromApi('/producibleItems');

        if (!Array.isArray(items) || items.length === 0) return;

        dropdownItemsContainer.innerHTML = "";
        const groupedItems = groupAndSortItems(items);

        for (const [category, items] of Object.entries(groupedItems)) {
            addCategoryHeader(category, dropdownItemsContainer);

            items.forEach((item) => {
                createDropdownItem(item, dropdownItemsContainer);
            });
        }

        isDropdownLoaded = true;
    });
}

/**
 * Groups and sorts items by category and display order.
 *
 * @param {Object[]} items The array of items to be grouped and sorted.
 * @returns {Object} An object where keys are category names and values are arrays of items sorted by display order.
 */
function groupAndSortItems(items) {
    const groupedItems = {};

    // Group items by category
    items.forEach((item) => {
        const category = item.category || "Uncategorized";
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