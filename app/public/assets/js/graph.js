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