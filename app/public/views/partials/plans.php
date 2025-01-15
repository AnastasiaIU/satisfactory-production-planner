<main class="container-fluid d-flex flex-column flex-grow-1 align-items-center p-0">
    <?php if (isset($planError)): ?>
        <div class="alert alert-danger col-lg-8 col-xl-8 p-3 m-4 mb-0" role="alert">
            <?= htmlspecialchars($planError) ?>
        </div>
    <?php endif; ?>
    <section class="card col-lg-8 col-xl-8 p-3 m-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="h5 m-0 mb-1 dark-grey-text">Saved plans</p>
            <a class="btn btn-secondary mb-1" id="importBtn">Import from JSON</a>
        </div>
        <ul id="plansList" class="list-group flex-grow-1 border-0 gap-3"></ul>
    </section>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this plan?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="/plans">
                        <input type="hidden" name="deletePlanId" id="deletePlanId">
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    // Pass the PHP variable to JavaScript
    const userId = JSON.parse('<?php echo json_encode($_SESSION['user']); ?>');
</script>