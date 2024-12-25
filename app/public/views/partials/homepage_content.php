<main class="container-fluid d-flex flex-column flex-grow-1 p-0">
    <section class="row flex-grow-1 column-gap-3 gap-3 m-3 p-0">
        <section class="col d-flex flex-column p-0 flex-grow-1 overflow-scroll">
            <section id="productionGraph" class="card d-flex flex-column flex-grow-1"></section>
        </section>
        <aside class="col-md-4 d-flex flex-column p-0">
            <section class="card d-flex flex-column flex-grow-1">
                <div class="dropdown d-flex justify-content-between align-items-center p-2">
                    <p class="h5 m-0">Outputs</p>
                    <a id="addItemBtn" class="btn btn-secondary dropdown-toggle" href="#" role="button"
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
                <ul id="itemList" class="list-group flex-grow-1 border-0"></ul>
            </section>
        </aside>
    </section>
</main>
<?php if (isset($items)): ?>
<script>
    window.loadedItems = <?php echo json_encode($items, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>
<?php endif; ?>