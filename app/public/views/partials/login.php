<main class="container-fluid d-flex flex-column flex-grow-1 align-items-center p-0">
    <section class="card col-md-6 col-lg-5 col-xl-4 p-4 m-4">
        <form class="d-flex flex-column gap-2 needs-validation" id="loginForm" method="post" novalidate>
            <div>
                <img src="/assets/images/ficsit-checkmarktm_64.png" class="img-fluid image-height-40"
                     alt="Logo FICSIT Checkmark">
                <span class="h3 align-middle ms-2 dark-grey-text">Production Planner</span>
            </div>
            <p class="h5 mb-3 medium-grey-text">Log in to your account</p>
            <div class="form-group">
                <label for="loginEmail">Email address</label>
                <input type="email" name="email" class="form-control" id="loginEmail"
                       aria-describedby="emailHelp" placeholder="Enter email"
                    <?php if (isset($loginFormData['email'])): ?>
                       value="<?= htmlspecialchars($loginFormData['email']) ?>" required>
                <div class="invalid-feedback" id="loginEmailPrompt"></div>
                <?php else: ?>
                    required>
                    <div class="invalid-feedback" id="loginEmailPrompt">
                        Email address cannot be empty.
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group mb-2">
                <label for="loginPassword">Password</label>
                <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Password"
                    <?php if (isset($loginFormData['password'])): ?>
                       value="<?= htmlspecialchars($loginFormData['password']) ?>" required>
                <div class="invalid-feedback" id="loginPasswordPrompt">
                    <?= htmlspecialchars($loginError) ?>
                </div>
                <?php else: ?>
                    required>
                    <div class="invalid-feedback" id="loginPasswordPrompt">
                        Password cannot be empty.
                    </div>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center mb-4">
                <input class="checkbox me-2" type="checkbox" value="" id="showPasswordCheck">
                <label class="" for="showPasswordCheck">
                    Show password
                </label>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Log in</button>
            <p>Don't have an account? <a class="link-opacity-75-hover" href="/register">Sign up</a>.</p>
        </form>
    </section>
</main>