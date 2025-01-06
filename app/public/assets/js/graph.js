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

async function appendOutput(itemId, output, outputContainer) {
    let outputRow = null;
    outputRow = createRow(outputRow, `${output.recipe_id} ${output.item_id}`, outputContainer);
    outputContainer.appendChild(outputRow);

    const response = await fetch(`/getItem/${itemId}`);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    const item = await response.json();

    const outputElement = document.createElement("div");
    outputElement.className = "graph-col output-item d-flex align-items-center";

    outputElement.innerHTML = `
        <img src="/assets/images/${item.icon_name}" alt="${item.display_name}" class="square">
        <p class="item-name h6 m-0 mt-1">${item.display_name}</p>
        <p class="item-amount m-0">${output.amount} p/m</p>
    `;

    outputRow.appendChild(outputElement);
}

function appendArrow(container, refElement) {
    const outputElement = document.createElement("div");
    outputElement.className = "d-flex align-items-center";

    outputElement.innerHTML = `
        <div class="arrow">➔</div>
    `;

    container.insertBefore(outputElement, refElement);
}

/**
 * Creates a new row element for the production graph and appends it to the container.
 *
 * @param {HTMLElement|null} graphRow - The current graph row element, or null if creating a new row.
 * @param {string} tag - The tag to associate with the new row.
 * @param {HTMLElement} container - The container element to which the new row will be appended.
 * @returns {HTMLElement} The newly created graph row element.
 */
function createRow(graphRow, tag, container) {
    graphRow = document.createElement("div");
    graphRow.className = "graph-row d-flex align-items-start align-items-center";
    graphRow.setAttribute("data-item-id", tag);
    container.appendChild(graphRow);
    return graphRow;
}

/**
 * Creates a new column element for the production graph and appends it to the container.
 *
 * @param {HTMLElement|null} graphColumn - The current graph column element, or null if creating a new column.
 * @param {string} tag - The tag to associate with the new column.
 * @param {HTMLElement} container - The container element to which the new column will be appended.
 * @returns {HTMLElement} The newly created graph column element.
 */
function createColumn(graphColumn, tag, container) {
    graphColumn = document.createElement("div");
    graphColumn.className = "graph-col d-flex align-items-start align-items-center gap-2";
    graphColumn.setAttribute("data-item-id", tag);
    container.appendChild(graphColumn);
    return graphColumn;
}

async function displayProductionGraph(itemId) {
    let graphRow = document.querySelector(`[data-item-id="graph ${itemId}"]`);
    const productionGraphContainer = document.getElementById("productionGraph");

    const response = await fetch(`/getRecipeForItem/${itemId}`);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    const recipe = await response.json();

    if (!graphRow) {
        graphRow = createRow(graphRow, `graph ${itemId}`, productionGraphContainer);

        let outputContainer = null;
        if (recipe.output.length > 1) {
            outputContainer = createColumn(outputContainer, `${itemId} recipe_output`, graphRow)
        } else {
            outputContainer = createRow(outputContainer, `${itemId} recipe_output`, graphRow)
        }

        for (const output of recipe.output) {
            await appendOutput(output.item_id, output, outputContainer);
        }

        appendArrow(graphRow, outputContainer);
    }


    // // Check if the graph element for this recipe already exists
    // if (!graphRow.querySelector(`[data-item-id="${itemId}"]`)) {
    //
    //
    //     // Append the graph element for the current recipe
    //     appendGraphElement(recipe, graphRow);
    //
    //     // Fetch and append inputs for the current recipe
    //     fetch(`/getRecipeInputs?recipe_id=${data.recipe_id}`)
    //         .then((response) => {
    //             if (!response.ok) throw new Error("Failed to load recipe inputs.");
    //             return response.json();
    //         })
    //         .then((inputs) => {
    //             console.log("Recipe inputs:", inputs);
    //
    //             inputs.forEach((input) => {
    //                 if (input.is_raw_material) {
    //                     const rawMaterialElement = document.createElement("div");
    //                     rawMaterialElement.innerHTML = `<div class="arrow">➔</div>`;
    //                     graphRow.insertBefore(rawMaterialElement, graphRow.firstChild);
    //                     // If raw material, append it to the same row and stop
    //                     appendRawMaterial(input, graphRow);
    //                 } else {
    //                     const rawMaterialElement = document.createElement("div");
    //                     rawMaterialElement.innerHTML = `<div class="arrow">➔</div>`;
    //                     graphRow.insertBefore(rawMaterialElement, graphRow.firstChild);
    //                     // If intermediate item, recursively fetch its recipe
    //                     displayProductionGraph(input.item_id, graphRow);
    //                 }
    //             });
    //         })
    //         .catch((err) => console.error("Error loading recipe inputs:", err));
    //
    //     // Fetch and append outputs for the current recipe
    //     fetch(`/getRecipeOutputs?recipe_id=${data.recipe_id}`)
    //         .then((response) => {
    //             if (!response.ok) throw new Error("Failed to load recipe outputs.");
    //             return response.json();
    //         })
    //         .then((outputs) => {
    //             console.log("Recipe outputs:", outputs);
    //
    //             const outputsColumn = graphRow.querySelector(`[data-item-id="${data.recipe_id}"] .outputs-column`);
    //
    //             outputs.forEach((output) => {
    //                 // Append the output item to the outputs column
    //                 appendOutput(output, outputsColumn);
    //             });
    //         })
    //         .catch((err) => console.error("Error loading recipe outputs:", err));
    // }
}