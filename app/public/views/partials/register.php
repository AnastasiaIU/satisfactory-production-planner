<main class="container-fluid d-flex flex-column flex-grow-1 align-items-center p-0">
    <section class="card col-md-6 col-lg-5 col-xl-4 p-4 m-4">
        <form class="d-flex flex-column gap-2 needs-validation" id="registrationForm" method="post" novalidate>
            <div>
                <img src="/assets/images/ficsit-checkmarktm_64.png" class="img-fluid image-height-40"
                     alt="Logo FICSIT Checkmark">
                <span class="h3 align-middle ms-2 dark-grey-text">Production Planner</span>
            </div>
            <p class="h5 mb-3 medium-grey-text">Create a new account</p>
            <div class="d-flex flex-column gap-3 mb-2">
                <div class="form-group">
                    <label for="inputEmail">Email address</label>
                    <?php if (isset($formData['email'])): ?>
                        <input type="email" name="email" class="form-control" id="inputEmail"
                               aria-describedby="emailHelp"
                               placeholder="Enter email"
                               value="<?= htmlspecialchars($formData['email']) ?>" required>
                        <div class="invalid-feedback" id="inputEmailPrompt">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php else: ?>
                        <input type="email" name="email" class="form-control" id="inputEmail"
                               aria-describedby="emailHelp"
                               placeholder="Enter email" required>
                        <div class="invalid-feedback" id="inputEmailPrompt">
                            Invalid email.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword"
                           placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="inputConfirmPassword">Confirm password</label>
                    <input type="password" name="confirmPassword" class="form-control" id="inputConfirmPassword"
                           placeholder="Confirm password" required>
                    <div class="invalid-feedback" id="confirmPasswordPrompt">
                        Provide passwords.
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center mb-4">
                <input class="checkbox me-2" type="checkbox" value="" id="showPasswordCheck">
                <label class="" for="showPasswordCheck">
                    Show passwords
                </label>
            </div>
            <button type="submit" class="btn btn-primary mb-3" id="registerBtn">Register</button>
        </form>
    </section>
</main>