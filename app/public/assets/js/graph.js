function appendOutput(item, output, container, refElement = null) {
    const outputElement = document.createElement("div");
    outputElement.className = "graph-col output-item d-flex align-items-center";
    outputElement.setAttribute("data-item-id", `${output.recipe_id} ${output.item_id} output`);

    outputElement.innerHTML = `
        <img src="/assets/images/${item.icon_name}" alt="${item.display_name}" class="square">
        <p class="item-name h6 m-0 mt-1">${item.display_name}</p>
        <p class="item-amount m-0">${output.amount} p/m</p>
    `;

    if (refElement === null) {
        container.appendChild(outputElement);
    } else {
        container.insertBefore(outputElement, refElement);
    }

    return outputElement;
}

function appendInput(item, input, container, refElement = null) {
    const inputElement = document.createElement("div");
    inputElement.className = "graph-col output-item d-flex align-items-center";
    inputElement.setAttribute("data-item-id", `${input.recipe_id} ${input.item_id} input`);

    inputElement.innerHTML = `
        <img src="/assets/images/${item.icon_name}" alt="${item.display_name}" class="square">
        <p class="item-name h6 m-0 mt-1">${item.display_name}</p>
        <p class="item-amount m-0">${input.amount} p/m</p>
    `;

    container.insertBefore(inputElement, refElement);

    return inputElement;
}

function appendArrow(container, refElement) {
    const arrowElement = document.createElement("div");
    arrowElement.className = "d-flex align-items-center";

    arrowElement.innerHTML = `
        <div class="arrow">➔</div>
    `;

    container.insertBefore(arrowElement, refElement);

    return arrowElement;
}

function appendMachine(recipe_id, machine, container, refElement) {
    const machineElement = document.createElement("div");
    machineElement.className = "graph-col machine d-flex align-items-center";
    machineElement.setAttribute("data-item-id", recipe_id);

    machineElement.innerHTML = `
        <div class="machine d-flex align-items-center">
            <img src="/assets/images/${machine.icon_name}" alt="Machine" class="circle">
            <p class="machine-name h6 m-0 mt-1">1 x ${machine.display_name}</p>
        </div>
    `;

    container.insertBefore(machineElement, refElement);

    return machineElement;
}

/**
 * Creates a new row element for the production graph and appends it to the container.
 *
 * @param {HTMLElement|null} graphRow - The current graph row element, or null if creating a new row.
 * @param {string} tag - The tag to associate with the new row.
 * @param {HTMLElement} container - The container element to which the new row will be appended.
 * @returns {HTMLElement} The newly created graph row element.
 */
function createRow(graphRow, tag, container, refElement = null) {
    graphRow = document.createElement("div");
    graphRow.className = "graph-row d-flex align-items-start align-items-center";
    graphRow.setAttribute("data-item-id", tag);

    if (refElement === null) {
        container.appendChild(graphRow);
    } else {
        container.insertBefore(graphRow, refElement)
    }

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
function createColumn(graphColumn, tag, container, refElement = null) {
    graphColumn = document.createElement("div");
    graphColumn.className = "graph-col d-flex align-items-start align-items-center gap-2";
    graphColumn.setAttribute("data-item-id", tag);

    if (refElement === null) {
        container.appendChild(graphColumn);
    } else {
        container.insertBefore(graphColumn, refElement)
    }

    return graphColumn;
}

async function displayProductionGraph(itemId) {
    let graphRow = document.querySelector(`[data-item-id="graph ${itemId}"]`);
    const productionGraphContainer = document.getElementById("productionGraph");

    const responseRecipe = await fetch(`/getRecipeForItem/${itemId}`);
    if (!responseRecipe.ok) throw new Error(`HTTP error! status: ${responseRecipe.status}`);
    const recipe = await responseRecipe.json();

    if (!graphRow) {
        graphRow = createRow(graphRow, `graph ${itemId}`, productionGraphContainer);

        let outputContainer = null;
        if (recipe.output.length > 1) {
            outputContainer = createColumn(outputContainer, `${itemId} recipe_output`, graphRow)
        } else {
            outputContainer = createRow(outputContainer, `${itemId} recipe_output`, graphRow)
        }

        for (const output of recipe.output) {
            const response = await fetch(`/getItem/${output.item_id}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const item = await response.json();

            await appendOutput(item, output, outputContainer);
        }

        const responseMachine = await fetch(`/getMachine/${recipe.produced_in}`);
        if (!responseMachine.ok) throw new Error(`HTTP error! status: ${responseMachine.status}`);
        const machine = await responseMachine.json();

        const arrowOutput = appendArrow(graphRow, outputContainer);
        const machineElement = appendMachine(recipe.recipe_id, machine, graphRow, arrowOutput);

        if (recipe.input.length !== 0) {
            const arrowInput = appendArrow(graphRow, machineElement);

            let inputContainer = null;
            if (recipe.input.length > 1) {
                inputContainer = createColumn(inputContainer, `${itemId} recipe_input`, graphRow, arrowInput)
            } else {
                inputContainer = createRow(inputContainer, `${itemId} recipe_input`, graphRow, arrowInput)
            }

            for (const input of recipe.input) {
                const response = await fetch(`/getItem/${input.item_id}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const item = await response.json();

                await appendInput(item, input, inputContainer);
            }
        }
    }
}