/**
 * Initializes the registration form by setting up event listeners and initial validation.
 */
function initRegisterForm() {
    const form = document.getElementById("registrationForm");
    const email = document.getElementById("inputEmail");
    const password = document.getElementById("inputPassword");
    const confirmPassword = document.getElementById("inputConfirmPassword");
    const confirmPasswordPrompt = document.getElementById("confirmPasswordPrompt");

    // If the email input is not empty, set the custom validity message
    if (email.value !== '') {
        email.setCustomValidity('User already exists. Please use another email.');
        resetPasswordValidation(password, confirmPassword, confirmPasswordPrompt);
        form.classList.add('was-validated');
    }

    addEventListenerToForm(form, email, password, confirmPassword, confirmPasswordPrompt);
    addEventListenerToCheckbox(password, confirmPassword);
    addEventListenerToCredentials(email, password, confirmPassword);
}

/**
 * Adds an event listener to the credentials input.
 * Resets the custom validity message on change.
 *
 * @param {HTMLInputElement} inputEmail The email input field.
 * @param {HTMLInputElement} inputPassword The password input field.
 * @param {HTMLInputElement} inputConfirmPassword The confirm password input field.
 */
function addEventListenerToCredentials(inputEmail, inputPassword, inputConfirmPassword) {
    inputEmail.addEventListener('change', () => {
        inputEmail.setCustomValidity('');
        inputPassword.setCustomValidity('');
        inputConfirmPassword.setCustomValidity('');
    });
    inputPassword.addEventListener('change', () => {
        inputEmail.setCustomValidity('');
        inputPassword.setCustomValidity('');
        inputConfirmPassword.setCustomValidity('');
    });
    inputConfirmPassword.addEventListener('change', () => {
        inputEmail.setCustomValidity('');
        inputPassword.setCustomValidity('');
        inputConfirmPassword.setCustomValidity('');
    });
}

/**
 * Adds an event listener to the show password checkbox to toggle the visibility of the password fields.
 *
 * @param {HTMLInputElement} password - The password input field.
 * @param {HTMLInputElement} confirmPassword - The confirmation password input field.
 */
function addEventListenerToCheckbox(password, confirmPassword) {
    const showPasswordCheck = document.getElementById('showPasswordCheck');
    showPasswordCheck.addEventListener('change', function () {
        const type = showPasswordCheck.checked ? 'text' : 'password';
        password.setAttribute('type', type);
        confirmPassword.setAttribute('type', type);
    });
}

/**
 * Adds an event listener to the form to handle form submission and validate the inputs.
 *
 * @param {HTMLFormElement} form - The registration form element.
 * @param {HTMLInputElement} email - The email input field.
 * @param {HTMLInputElement} password - The password input field.
 * @param {HTMLInputElement} confirmPassword - The confirmation password input field.
 * @param {HTMLElement} confirmPasswordPrompt - The element to display confirm password validation messages.
 */
function addEventListenerToForm(form, email, password, confirmPassword, confirmPasswordPrompt) {
    form.addEventListener('submit', function (event) {
        resetPasswordValidation(password, confirmPassword, confirmPasswordPrompt);
        email.setCustomValidity('');

        // Check if the passwords are matching
        if (password.value !== confirmPassword.value) {
            password.setCustomValidity("Passwords do not match.");
            confirmPassword.setCustomValidity("Passwords do not match.");
            confirmPasswordPrompt.innerHTML = confirmPassword.validationMessage;

            // Stop the form submission
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });
}

/**
 * Resets the custom validity messages for the password fields and updates the prompt.
 *
 * @param {HTMLInputElement} password - The password input field.
 * @param {HTMLInputElement} confirmPassword - The confirmation password input field.
 * @param {HTMLElement} confirmPasswordPrompt - The element to display confirm password validation messages.
 */
function resetPasswordValidation(password, confirmPassword, confirmPasswordPrompt) {
    password.setCustomValidity('');
    confirmPassword.setCustomValidity('');
    confirmPasswordPrompt.innerHTML = 'Provide passwords.';
}