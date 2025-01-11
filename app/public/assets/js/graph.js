/**
 * Displays the production graph for a given item.
 *
 * @param {string} itemId The ID of the item for which the production graph will be displayed.
 */
async function displayProductionGraph(itemId) {
    const productionGraphContainer = document.getElementById("productionGraph");
    let graphRow = document.querySelector(`[data-item-id="graph ${itemId}"]`);

    // If the graph row does not exist, create it
    if (!graphRow) {
        // Create containers
        graphRow = createContainer(productionGraphContainer, false);
        graphRow.dataset.itemId = `${itemId}`;
        const outputContainer = createContainer(graphRow, true);
        outputContainer.classList.add("output-container");

        // Fetch the recipe for the item from the API
        const recipe = await fetchFromApi(`/getRecipeForItem/${itemId}`);

        // Get user input for the item
        const userInput = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`).value;

        // Calculate the required production power
        let productionPower = 0;
        for (const output of recipe.output) {
            if (output.item_id === itemId) {
                productionPower = calculateProductionPower(userInput, output.amount);
                break;
            }
        }

        // Fetch, create, and append outputs to the output container
        for (const output of recipe.output) {
            const outputItem = await fetchFromApi(`/getItem/${output.item_id}`);
            const amount = output.item_id === itemId ? userInput :
                calculateAmount(output.amount, productionPower);
            const outputElement = await appendItem(outputItem, amount, outputContainer);
            outputElement.querySelector("img").classList.add("output");
        }

        // Append an arrow element to the graph row
        const arrowOutput = appendArrow(graphRow, outputContainer);

        // Fetch and append the machine to the graph row
        const machine = await fetchFromApi(`/getMachine/${recipe.produced_in}`);
        const machineQuantity = Math.ceil(productionPower);
        const machineElement = appendMachine(machine, machineQuantity, graphRow, arrowOutput);

        // Extend the graph by adding input items, arrows, and machines recursively
        await extendGraph(recipe, productionPower, graphRow, machineElement);

        // Add a padding class to the input container if it exists
        const inputContainer = graphRow.querySelector('.input-container');
        if (inputContainer !== null) inputContainer.classList.add("p-3");
    }
}

/**
 * Calculates the required production power for a given output amount and output amount per minute.
 *
 * @param {string} outputAmount Required output amount.
 * @param {string} outputAmountPerMinute Output amount per minute.
 * @returns {number} The required production power as a decimal fraction.
 */
function calculateProductionPower(outputAmount, outputAmountPerMinute) {
    return parseFloat(outputAmount) / parseFloat(outputAmountPerMinute);
}

/**
 * Calculates the amount of an item based on the production power.
 *
 * @param {string} amount Amount of the item produced per minute.
 * @param productionPower The required production power.
 * @returns {number} The required amount of the item produced per minute.
 */
function calculateAmount(amount, productionPower) {
    return parseFloat((parseFloat(amount) * productionPower).toPrecision(3));
}

/**
 * Creates a new container element and appends it to the specified container.
 *
 * @param {HTMLElement} container The container element to which the new container will be appended.
 * @param {boolean} isVertical A flag indicating whether the container should be vertical.
 * @param {HTMLElement|null} insertBeforeElement The element before which the new container will be inserted,
 * or null to append at the end.
 * @returns {HTMLElement} The newly created container element.
 */
function createContainer(container, isVertical, insertBeforeElement = null) {
    const newElement = document.createElement("div");
    newElement.className = "d-flex align-items-start align-items-center gap-2";
    newElement.classList.add(isVertical ? "flex-column" : "flex-row");

    if (insertBeforeElement === null) {
        container.appendChild(newElement);
    } else {
        container.insertBefore(newElement, insertBeforeElement)
    }

    return newElement;
}

/**
 * Appends an item element to the specified container.
 *
 * @param {Object} item The item object containing item details.
 * @param {number} amount The amount of the item produced per minute.
 * @param {ChildNode} container The container element to which the item element will be appended.
 * @param {HTMLElement|null} [insertBeforeElement=null] The element before which the item element will be inserted,
 * or null to append at the end.
 * @returns {HTMLElement} The newly created item element.
 */
function appendItem(item, amount, container, insertBeforeElement = null) {
    const itemElement = document.createElement("div");
    itemElement.className = "d-flex flex-column align-items-center graph-element";
    itemElement.innerHTML = `
        <img src="/assets/images/${item.icon_name}" alt="${item.display_name}" class="graph-image square">
        <p class="text-center text-break h6 m-0 mt-1">${item.display_name}</p>
        <p class="text-center m-0">${amount} p/m</p>
    `;

    if (insertBeforeElement === null) {
        container.appendChild(itemElement);
    } else {
        container.insertBefore(itemElement, insertBeforeElement)
    }

    return itemElement;
}

/**
 * Appends an arrow element to the specified container.
 *
 * @param {HTMLElement} container The container element to which the arrow element will be appended.
 * @param {HTMLElement|null} [insertBeforeElement=null] The element before which the arrow element will be inserted,
 * or null to append at the end.
 * @returns {HTMLElement} The newly created arrow element.
 */
function appendArrow(container, insertBeforeElement = null) {
    const arrowElement = document.createElement("div");
    arrowElement.className = "d-flex align-items-center";
    arrowElement.innerHTML = `
        <div class="arrow">➔</div>
    `;

    if (insertBeforeElement === null) {
        container.appendChild(arrowElement);
    } else {
        container.insertBefore(arrowElement, insertBeforeElement)
    }

    return arrowElement;
}

/**
 * Appends a machine element to the specified container.
 *
 * @param {Object} machine The machine object containing machine details.
 * @param {number} quantity The quantity of the machine.
 * @param {HTMLElement} container The container element to which the machine element will be appended.
 * @param {HTMLElement|null} [insertBeforeElement=null] The element before which the machine element will be inserted,
 * or null to append at the end.
 * @returns {HTMLElement} The newly created machine element.
 */
function appendMachine(machine, quantity, container, insertBeforeElement = null) {
    const machineElement = document.createElement("div");
    machineElement.className = "d-flex flex-column align-items-center graph-element";
    machineElement.innerHTML = `
        <img src="/assets/images/${machine.icon_name}" alt="${machine.display_name}" class="graph-image circle">
        <p class="text-center h6 m-0 mt-1">${quantity} x ${machine.display_name}</p>
    `;

    if (insertBeforeElement === null) {
        container.appendChild(machineElement);
    } else {
        container.insertBefore(machineElement, insertBeforeElement)
    }

    return machineElement;
}

/**
 * Extends the production graph by adding input items, arrows, and machines recursively.
 *
 * @param {Object} recipe The recipe object containing input items and other details.
 * @param {number} productionPower The production power required for the output items.
 * @param {HTMLElement} container The container element to which the graph elements will be appended.
 * @param {HTMLElement|null} insertBeforeElement The element before which the new elements will be inserted,
 * or null to append at the end.
 */
async function extendGraph(recipe, productionPower, container, insertBeforeElement) {
    if (recipe.input.length !== 0) {
        // Append an arrow element to the container
        const arrowInput = appendArrow(container, insertBeforeElement);

        // Create containers
        const wrapContainer = createContainer(container, true, arrowInput);
        wrapContainer.classList.add("input-container");
        const inputContainer = createContainer(wrapContainer, true);
        inputContainer.classList.add("input-inner-container");

        for (const input of recipe.input) {
            const innerGraphContainer = createContainer(inputContainer, false);

            // Fetch and append the input item details from the API
            const inputItem = await fetchFromApi(`/getItem/${input.item_id}`);
            const amount = calculateAmount(input.amount, productionPower);

            const appendedItem = await appendItem(inputItem, amount, innerGraphContainer);

            if (inputItem.category !== 'Collectable' && !(inputItem.display_name).includes('Waste')) {
                // Append an arrow element to the container
                const appendedArrow = await appendArrow(innerGraphContainer, appendedItem);

                // Fetch the recipe and machine
                const inputRecipe = await fetchFromApi(`/getRecipeForItem/${input.item_id}`);
                const machineToAppend = await fetchFromApi(`/getMachine/${inputRecipe.produced_in}`);

                // Calculate the new production power
                let newProductionPower = 0;
                for (const output of inputRecipe.output) {
                    if (output.item_id === input.item_id) {
                        newProductionPower = calculateProductionPower(amount, output.amount);
                        break;
                    }
                }

                // Append the machine to the container
                const machineQuantity = Math.ceil(newProductionPower);
                const appendedMachine = await appendMachine(machineToAppend, machineQuantity, innerGraphContainer, appendedArrow);

                // Recursively extend the graph if the input item is not a raw resource
                if (inputItem.category !== 'Raw Resources') await extendGraph(inputRecipe, newProductionPower, innerGraphContainer, appendedMachine);
            }
        }
    }
}