<main class="container-fluid d-flex flex-column flex-grow-1 align-items-center p-0">
    <section class="card col-md-6 col-lg-5 col-xl-4 p-4 m-4">
        <form class="d-flex flex-column gap-2 needs-validation" novalidate>
            <div>
                <img src="/assets/images/ficsit-checkmarktm_64.png" class="img-fluid image-height-40"
                     alt="Logo FICSIT Checkmark">
                <span class="h3 align-middle ms-2 dark-grey-text">Production Planner</span>
            </div>
            <p class="h5 mb-3 medium-grey-text">Log in to your account</p>
            <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp"
                       placeholder="Enter email" required>
            </div>
            <div class="form-group mb-2">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control" id="inputPassword" placeholder="Password" required>
                <div class="invalid-feedback">
                    Wrong email or password. Please, try again.
                </div>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" value="" id="showPasswordCheck">
                <label class="form-check-label" for="showPasswordCheck">
                    Show passwords
                </label>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Log in</button>
            <p>Don't have an account? <a class="link-opacity-75-hover" href="/register">Sign up</a>.</p>
        </form>
    </section>
</main>