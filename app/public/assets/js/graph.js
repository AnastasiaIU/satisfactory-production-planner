async function displayProductionGraph(itemId) {
    const productionGraphContainer = document.getElementById("productionGraph");
    let graphRow = document.querySelector(`[data-item-id="graph ${itemId}"]`);

    if (!graphRow) {
        graphRow = createContainer(productionGraphContainer, false);
        const recipe = await fetchFromApi(`/getRecipeForItem/${itemId}`);
        const outputContainer = createContainer(graphRow, true);
        outputContainer.classList.add("output-container");

        for (const output of recipe.output) {
            const outputItem = await fetchFromApi(`/getItem/${output.item_id}`);
            const outputElement = await appendItem(outputItem, output, outputContainer);
            outputElement.querySelector("img").classList.add("output");
        }

        const machine = await fetchFromApi(`/getMachine/${recipe.produced_in}`);
        const arrowOutput = appendArrow(graphRow, outputContainer);
        const machineElement = appendMachine(machine, graphRow, arrowOutput);

        await extendGraph(recipe, graphRow, machineElement);
    }
}

/**
 * Creates a new container element and appends it to the specified container.
 *
 * @param {HTMLElement} container - The container element to which the new container will be appended.
 * @param {boolean} isVertical - A flag indicating whether the container should be vertical.
 * @param {HTMLElement|null} insertBeforeElement - The element before which the new container will be inserted, or null to append at the end.
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
 * @param {Object} item - The item object containing item details.
 * @param {Object} amountObject - The object containing amount information. Either input or output.
 * @param {HTMLElement} container - The container element to which the item element will be appended.
 * @param {HTMLElement|null} [insertBeforeElement=null] - The element before which the item element will be inserted, or null to append at the end.
 * @returns {HTMLElement} The newly created item element.
 */
function appendItem(item, amountObject, container, insertBeforeElement = null) {
    const itemElement = document.createElement("div");
    itemElement.className = "d-flex flex-column align-items-center graph-element";
    itemElement.innerHTML = `
        <img src="/assets/images/${item.icon_name}" alt="${item.display_name}" class="graph-image square">
        <p class="text-center h6 m-0 mt-1">${item.display_name}</p>
        <p class="text-center m-0">${amountObject.amount} p/m</p>
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
 * @param {HTMLElement} container - The container element to which the arrow element will be appended.
 * @param {HTMLElement|null} [insertBeforeElement=null] - The element before which the arrow element will be inserted,
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
 * @param {Object} machine - The machine object containing machine details.
 * @param {HTMLElement} container - The container element to which the machine element will be appended.
 * @param {HTMLElement|null} [insertBeforeElement=null] - The element before which the machine element will be inserted,
 * or null to append at the end.
 * @returns {HTMLElement} The newly created machine element.
 */
function appendMachine(machine, container, insertBeforeElement = null) {
    const machineElement = document.createElement("div");
    machineElement.className = "d-flex flex-column align-items-center graph-element";
    machineElement.innerHTML = `
        <img src="/assets/images/${machine.icon_name}" alt="${machine.display_name}" class="graph-image circle">
        <p class="text-center h6 m-0 mt-1">1 x ${machine.display_name}</p>
    `;

    if (insertBeforeElement === null) {
        container.appendChild(machineElement);
    } else {
        container.insertBefore(machineElement, insertBeforeElement)
    }

    return machineElement;
}

async function extendGraph(recipe, container, insertBeforeElement) {
    if (recipe.input.length !== 0) {
        const arrowInput = appendArrow(container, insertBeforeElement);
        const wrapContainer = createContainer(container, true, arrowInput);
        wrapContainer.classList.add("input-container");
        const inputContainer = createContainer(wrapContainer, true);
        inputContainer.classList.add("input-inner-container");

        for (const input of recipe.input) {
            const innerGraphContainer = createContainer(inputContainer, false);
            const inputItem = await fetchFromApi(`/getItem/${input.item_id}`);
            const appendedItem = await appendItem(inputItem, input, innerGraphContainer);
            const appendedArrow = await appendArrow(innerGraphContainer, appendedItem);
            const inputRecipe = await fetchFromApi(`/getRecipeForItem/${input.item_id}`);
            const machineToAppend = await fetchFromApi(`/getMachine/${inputRecipe.produced_in}`);
            const appendedMachine = await appendMachine(machineToAppend, innerGraphContainer, appendedArrow);
            if (inputItem.category !== 'Raw Resources') await extendGraph(inputRecipe, innerGraphContainer, appendedMachine);
        }
    }
}