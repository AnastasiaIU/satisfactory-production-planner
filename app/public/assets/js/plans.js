async function initPlans() {
    const plans = await fetchFromApi(`/getPlans/${userId}`);
    const plansList = document.getElementById('plansList');

    if (plans.length === 0) {
        plansList.innerHTML = '<div class="alert alert-light m-0" role="alert">No plans found.</div>';
    } else {
        for (const plan of plans) {
            createPlanElement(plan, plansList);
        }
    }

    addOnClickEventToImportBtn();
}

function createPlanElement(plan, planList) {
    const planElement = document.createElement('div');
    planElement.classList.add('card');
    planElement.innerHTML = `
        <div class="card-body d-flex flex-wrap align-items-center gap-2 justify-content-between pb-15">
            <p class="h6 m-0 mb-1">${plan.display_name}</p>
            <div>
                <a class="btn btn-primary mb-1" id="viewBtn" data-view-plan-id="${plan.id}">View/Edit</a>
                <a class="btn btn-success mb-1" id="exportBtn" data-export-plan-id="${plan.id}">Export in JSON</a>
                <a class="btn btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-plan-id="${plan.id}">Delete</a>
            </div>
        </div>
    `;
    planList.appendChild(planElement);
    addOnClickEventToDeleteBtn(plan, planElement);
    addOnClickEventToViewBtn(planElement);
    addOnClickEventToExportBtn(planElement);
}

function addOnClickEventToDeleteBtn(plan, planElement) {
    const deleteBtn = planElement.querySelector('.btn-danger');
    deleteBtn.addEventListener('click', () => {
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.onclick = () => {
            document.getElementById('deletePlanId').value = plan.id;
            document.getElementById('deleteForm').submit();
        }
    });
}

function addOnClickEventToViewBtn(planElement) {
    const viewBtn = planElement.querySelector('.btn-primary');
    viewBtn.addEventListener('click', () => {
        const planId = viewBtn.getAttribute('data-view-plan-id');
        window.location.href = `/plan/${planId}`;
    });
}

function addOnClickEventToExportBtn(planElement) {
    const exportBtn = planElement.querySelector('.btn-success');
    exportBtn.addEventListener('click', async () => {
        const planId = exportBtn.getAttribute('data-export-plan-id');
        const plan = await fetchFromApi(`/getPlan/${planId}`);
        console.log(plan);
        const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(plan));
        const downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute("href", dataStr);
        downloadAnchorNode.setAttribute("download", `plan_${planId}.json`);
        document.body.appendChild(downloadAnchorNode);
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
    });
}

function addOnClickEventToImportBtn() {
    const importBtn = document.getElementById('importBtn');
    importBtn.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'application/json';
        input.onchange = async (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = async (e) => {
                    const content = e.target.result;
                    try {
                        const plan = JSON.parse(content);
                        await importPlan(plan);
                    } catch (error) {
                        await sendErrorToBackend('Invalid JSON file. Please upload a valid JSON file.');
                    }
                };
                reader.readAsText(file);
            }
        };
        input.click();
    });
}

async function importPlan(plan) {
    try {
        const response = await fetch('/importPlan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(plan)
        });
        if (response.ok) {
            window.location.href = '/plans';
        } else {
            await sendErrorToBackend('Invalid JSON file. Please upload a valid JSON file.');
        }
    } catch (error) {
        await sendErrorToBackend('Error importing plan. Please try again.');
    }
}

async function sendErrorToBackend(errorMessage) {
    await fetch('/logImportError', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ error: errorMessage })
    });

    window.location.href = '/plans';
}