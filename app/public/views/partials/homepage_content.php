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
                <ul id="outputsList" class="list-group flex-grow-1 border-0"></ul>
            </section>
        </aside>
    </section>
</main>