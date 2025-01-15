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
}

function createPlanElement(plan, planList) {
    const planElement = document.createElement('div');
    planElement.classList.add('card');
    planElement.innerHTML = `
        <div class="card-body d-flex flex-wrap align-items-center gap-2 justify-content-between">
            <p class="h6 m-0">${plan.display_name}</p>
            <div>
                <a class="btn btn-primary">View/Edit</a>
                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-plan-id="${plan.id}">Delete</a>
            </div>
        </div>
    `;
    planList.appendChild(planElement);
    addOnClickEventToDeleteBtn(plan, planElement);
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