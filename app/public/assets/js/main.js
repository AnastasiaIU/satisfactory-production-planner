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

document.addEventListener("DOMContentLoaded", () => {
    console.log("JavaScript loaded!");

    /** Clear search input when dropdown is closed **/
    const dropdown = document.querySelector(".dropdown");
    const searchInput = document.getElementById("dropdownSearch");

    dropdown.addEventListener("hide.bs.dropdown", () => {
        if (searchInput) {
            searchInput.value = ""; // Clear the input
            filterDropdown(); // Reset the dropdown items
        }
    });

    /** 1. Filter Dropdown Function **/
    function filterDropdown() {
        const input = document.getElementById("dropdownSearch");
        const filter = input.value.toLowerCase();
        const dropdownItemsContainer = document.getElementById("dropdownItems");
        const categories = dropdownItemsContainer.querySelectorAll(".dropdown-header");
        let hasResults = false;

        categories.forEach((header) => {
            // Get the category's items
            const categoryItems = [];
            let nextElement = header.nextElementSibling;

            // Collect all items under this category
            while (nextElement && nextElement.tagName === "LI") {
                categoryItems.push(nextElement);
                nextElement = nextElement.nextElementSibling;
            }

            // Filter the items within this category
            let hasVisibleItems = false;
            categoryItems.forEach((item) => {
                const text = item.textContent || item.innerText;
                const matchesFilter = text.toLowerCase().includes(filter);
                const isInOutputList = item.querySelector(".dropdown-item").style.display === "none";

                // Show item only if it matches the filter and is not already hidden (added to output)
                if (matchesFilter && !isInOutputList) {
                    item.style.display = ""; // Show item
                    hasVisibleItems = true;
                } else {
                    item.style.display = "none"; // Hide item
                }
            });

            // Show or hide the category header based on visible items
            if (hasVisibleItems) {
                header.style.display = ""; // Show header
                hasResults = true; // At least one result exists
            } else {
                header.style.display = "none"; // Hide header
            }
        });

        // Display a "no results found" message if no items match the filter
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

    // Expose filter function globally for inline `onkeyup`
    window.filterDropdown = filterDropdown;

    /** 2. Handle Dropdown Item Click **/
    const dropdownItemsContainer = document.getElementById("dropdownItems");
    const itemList = document.getElementById("itemList");

    dropdownItemsContainer.addEventListener("click", (event) => {
        event.preventDefault();

        const target = event.target.closest(".dropdown-item");
        if (target) {
            const itemId = target.dataset.itemId;
            const itemName = target.innerText.trim();
            const itemIcon = target.querySelector("img")?.src || "";

            // Create a new list item in the output list
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

            // Hide the dropdown item
            target.style.display = "none";

            // Append the new list item to the output list
            itemList.appendChild(listItem);

            // Fetch and display the production graph
            displayProductionGraph(itemId);

            // Handle quantity change
            const quantityInput = listItem.querySelector(".quantity-input");
            quantityInput.addEventListener("change", () => {
                const currentValue = parseFloat(quantityInput.value) || 0;

                if (currentValue === 0) {
                    // Remove the list item
                    listItem.remove();

                    // Show the corresponding dropdown item again
                    const dropdownItem = dropdownItemsContainer.querySelector(`[data-item-id="${itemId}"]`);
                    if (dropdownItem) {
                        dropdownItem.style.display = ""; // Make it visible again
                    }

                    // Remove the corresponding production graph
                    const productionGraphContainer = document.getElementById("productionGraph");
                    const graphElement = productionGraphContainer.querySelector(`[data-item-id="${itemId}"]`);
                    if (graphElement) {
                        graphElement.remove();
                    }
                }
            });
        }
    });

    /** 3. Populate Dropdown on First Click **/
    let isDropdownLoaded = false;
    const addItemBtn = document.getElementById("addItemBtn");

    addItemBtn.addEventListener("click", () => {
        if (isDropdownLoaded) return;

        const items = window.loadedItems; // Injected from PHP
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

    /** 5. Fetch and Display Production Graph **/
    function displayProductionGraph(itemId, graphRow = null) {
        console.log("Fetching recipe for item:", itemId);

        fetch(`/getRecipeDetails?item_id=${itemId}`)
            .then((response) => {
                if (!response.ok) throw new Error("Failed to load recipe details.");
                return response.json();
            })
            .then((data) => {
                console.log("Recipe data:", data);
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const productionGraphContainer = document.getElementById("productionGraph");

                // If this is the first item in the chain, create a new row
                if (!graphRow) {
                    graphRow = document.createElement("div");
                    graphRow.className = "graph-row d-flex align-items-start align-items-center m-3";
                    graphRow.setAttribute("data-item-id", itemId);
                    productionGraphContainer.appendChild(graphRow);
                }

                // Check if the graph element for this recipe already exists
                if (!graphRow.querySelector(`[data-item-id="${itemId}"]`)) {
                    // Append the graph element for the current recipe
                    appendGraphElement(data, graphRow);

                    // Fetch and append inputs for the current recipe
                    fetch(`/getRecipeInputs?recipe_id=${data.recipe_id}`)
                        .then((response) => {
                            if (!response.ok) throw new Error("Failed to load recipe inputs.");
                            return response.json();
                        })
                        .then((inputs) => {
                            console.log("Recipe inputs:", inputs);

                            inputs.forEach((input) => {
                                if (input.is_raw_material) {
                                    const rawMaterialElement = document.createElement("div");
                                    rawMaterialElement.innerHTML = `<div class="arrow">➔</div>`;
                                    graphRow.insertBefore(rawMaterialElement, graphRow.firstChild);
                                    // If raw material, append it to the same row and stop
                                    appendRawMaterial(input, graphRow);
                                } else {
                                    const rawMaterialElement = document.createElement("div");
                                    rawMaterialElement.innerHTML = `<div class="arrow">➔</div>`;
                                    graphRow.insertBefore(rawMaterialElement, graphRow.firstChild);
                                    // If intermediate item, recursively fetch its recipe
                                    displayProductionGraph(input.item_id, graphRow);
                                }
                            });
                        })
                        .catch((err) => console.error("Error loading recipe inputs:", err));

                    // Fetch and append outputs for the current recipe
                    fetch(`/getRecipeOutputs?recipe_id=${data.recipe_id}`)
                        .then((response) => {
                            if (!response.ok) throw new Error("Failed to load recipe outputs.");
                            return response.json();
                        })
                        .then((outputs) => {
                            console.log("Recipe outputs:", outputs);

                            const outputsColumn = graphRow.querySelector(`[data-item-id="${data.recipe_id}"] .outputs-column`);

                            outputs.forEach((output) => {
                                // Append the output item to the outputs column
                                appendOutput(output, outputsColumn);
                            });
                        })
                        .catch((err) => console.error("Error loading recipe outputs:", err));
                }
            })
            .catch((err) => console.error("Error loading production graph:", err));
    }

    function appendGraphElement(data, graphRow) {
        // Create the graph element for the recipe
        const graphElement = document.createElement("div");
        graphElement.className = "graph-container d-flex align-items-center";
        graphElement.setAttribute("data-item-id", data.recipe_id);

        graphElement.innerHTML = `
        <div class="machine">
            <img src="/assets/images/${data.machine_icon}" alt="Machine" class="circle">
        </div>
        <div class="arrow">➔</div>
        <div class="outputs-column d-flex flex-column align-items-start"></div>
    `;

        graphRow.insertBefore(graphElement, graphRow.firstChild);
    }

    function appendRawMaterial(input, graphRow) {
        const rawMaterialElement = document.createElement("div");
        rawMaterialElement.className = "raw-material-item d-flex align-items-center mx-3";

        rawMaterialElement.innerHTML = `
        <div class="arrow">➔</div>
        <div class="machine">
            <img src="/assets/images/${input.machine_icon}" alt="${input.display_name}" class="circle">
        </div>
        <img src="/assets/images/${input.icon_name}" alt="${input.display_name}" class="square">
    `;

        graphRow.insertBefore(rawMaterialElement, graphRow.firstChild);
    }

    function appendOutput(output, outputsColumn) {
        const outputElement = document.createElement("div");
        outputElement.className = "output-item d-flex align-items-center mb-2";

        outputElement.innerHTML = `
        <img src="/assets/images/${output.icon_name}" alt="${output.display_name}" class="square">
    `;

        outputsColumn.appendChild(outputElement);
    }
});
