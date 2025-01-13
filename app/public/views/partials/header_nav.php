<nav class="navbar nav-underline navbar-expand-lg bg-ficsit-orange justify-content-end" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand text-white ms-3" href="/">
            <img src="/assets/images/ficsit-checkmarktm_64.png" class="img-fluid image-height-32"
                 alt="Logo FICSIT Checkmark">
            <span class="h5 align-middle">Production Planner</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3 gap-2">
                <li class="nav-item">
                    <a class="nav-link fs-6 fw-bold text-white active" id="nav-item-planner" aria-current="page"
                       href="/">Planner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 fw-bold text-white
                    <?php if (isset($_SESSION['user'])): ?>
                   " id="nav-item-plans" href="/">My Plans</a>
                    <?php else: ?>
                        disabled" id="nav-item-plans" href="/">My Plans</a>
                    <?php endif; ?>
                </li>
            </ul>
            <div>
                <a type="button" class="btn btn-primary ms-3" id="loginButton"
                    <?php if (isset($_SESSION['user'])): ?>
                   href="/logout">Log out</a>
                <?php else: ?>
                    href="/login">Log in</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>