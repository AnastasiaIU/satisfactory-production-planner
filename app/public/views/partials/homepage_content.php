<main class="container-fluid d-flex flex-column flex-grow-1 p-0">
    <section class="row flex-grow-1 column-gap-3 gap-3 m-3 p-0">
        <section class="card col d-flex flex-column m-0 p-0 flex-grow-1 overflow-scroll">
            <section class="p-3 d-flex flex-column flex-grow-1">
                <section id="productionGraph" class="d-flex flex-column flex-grow-1 p-0 pb-3 gap-5"></section>
            </section>
        </section>
        <aside class="col-md-4 d-flex flex-column p-0">
            <section class="card d-flex flex-column flex-grow-1">
                <div id="outputsDropdown" class="dropdown d-flex justify-content-between align-items-center p-2">
                    <p class="h5 m-0">Outputs</p>
                    <a id="addItemBtn" class="btn btn-secondary dropdown-toggle" role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Add item
                    </a>
                    <ul id="dropdownMenu" class="dropdown-menu dropdown-menu-end overflow-auto">
                        <li>
                            <form class="px-3 py-2">
                                <input type="text" class="form-control" id="dropdownSearch"
                                       placeholder="Search items..." onkeyup="filterDropdown()"
                                       aria-label="Search items">
                            </form>
                        </li>
                        <li>
                            <hr class="dropdown-divider mb-0">
                        </li>
                        <div id="dropdownItems"></div>
                    </ul>
                </div>
                <hr class="mb-2 mt-0">
                <form class="d-flex flex-column needs-validation" id="planForm" method="post" novalidate>
                    <div id="savePlan" <?php if (!isset($_SESSION['user'])): ?>
                        class="disabled"
                    <?php endif; ?>
                    >
                        <div class="d-flex flex-wrap p-2 pt-0 gap-2 align-items-center justify-content-between">
                            <input type="hidden" name="createPlanId" id="createPlanId">
                            <div class="form-group">
                                <input type="text" name="planName" class="form-control" id="planName"
                                       placeholder="Enter name for the plan" aria-label="Plan name" required>
                                <div class="invalid-feedback" id="planNamePrompt">
                                    <?php if (isset($planError)): ?>
                                        <?= htmlspecialchars($planError) ?>
                                    <?php else: ?>
                                        Name cannot be empty.
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="savePlanBtn">
                                <?php if (isset($plan)): ?>
                                    Update plan
                                <?php else: ?>
                                    Create new plan
                                <?php endif; ?>
                            </button>
                        </div>
                        <hr class="mb-2 mt-0">
                    </div>
                    <ul id="outputsList" class="list-group flex-grow-1 border-0"></ul>
                </form>
            </section>
        </aside>
    </section>
</main>
<?php if (isset($plan)): ?>
    <script>
        // Pass the PHP variable to JavaScript
        const plan = JSON.parse('<?php echo json_encode($plan); ?>');
    </script>
<?php endif; ?>