/**
 * Initializes the login form by setting up event listeners and initial validation.
 */
function initLoginForm() {
    const form = document.getElementById("loginForm");
    const email = document.getElementById("loginEmail");
    const password = document.getElementById("loginPassword");
    const inputEmailPrompt = document.getElementById("loginEmailPrompt");
    const inputPasswordPrompt = document.getElementById("loginPasswordPrompt");

    // If the email or password inputs are not empty, set the custom validity message
    if (email.value !== '' || password.value !== '') {
        email.setCustomValidity('Wrong email or password. Please, try again.');
        password.setCustomValidity('Wrong email or password. Please, try again.');
        inputEmailPrompt.innerHTML = '';
        inputPasswordPrompt.innerHTML = password.validationMessage;
        form.classList.add('was-validated');
    }

    addEventListenerToLoginForm(form, email, password, inputEmailPrompt, inputPasswordPrompt);
    addEventListenerToLoginCheckbox(password);
    addEventListenerToCredentials(email, password);
}

/**
 * Adds an event listener to the credentials input.
 * Resets the custom validity message on change.
 *
 * @param {HTMLInputElement} loginEmail The email input field.
 * @param {HTMLInputElement} loginPassword The password input field.
 */
function addEventListenerToCredentials(loginEmail, loginPassword) {
    loginEmail.addEventListener('change', () => {
        resetCredentialsValidation(loginEmail, loginPassword);
    });
    loginPassword.addEventListener('change', () => {
        resetCredentialsValidation(loginEmail, loginPassword);
    });
}

/**
 * Resets the custom validity message on the email and password input fields.
 *
 * @param {HTMLInputElement} loginEmail The email input field.
 * @param {HTMLInputElement} loginPassword The password input field.
 */
function resetCredentialsValidation(loginEmail, loginPassword) {
    loginEmail.setCustomValidity('');
    loginPassword.setCustomValidity('');
}

/**
 * Adds an event listener to the show password checkbox to toggle the visibility of the password field.
 *
 * @param {HTMLInputElement} password - The password input field.
 */
function addEventListenerToLoginCheckbox(password) {
    const showPasswordCheck = document.getElementById('showPasswordCheck');
    showPasswordCheck.addEventListener('change', function () {
        const type = showPasswordCheck.checked ? 'text' : 'password';
        password.setAttribute('type', type);
    });
}

/**
 * Adds an event listener to the form to handle form submission and validate the inputs.
 *
 * @param {HTMLFormElement} form - The registration form element.
 * @param {HTMLInputElement} email - The email input field.
 * @param {HTMLInputElement} password - The password input field.
 * @param {HTMLElement} inputEmailPrompt - The element to display email validation messages.
 * @param {HTMLElement} inputPasswordPrompt - The element to display password validation messages.
 */
function addEventListenerToLoginForm(form, email, password, inputEmailPrompt, inputPasswordPrompt) {
    form.addEventListener('submit', function () {
        email.setCustomValidity('');
        password.setCustomValidity('');
        inputPasswordPrompt.innerHTML = 'Password address cannot be empty.';

        console.log(email.value);
        if (email.value === '') {
            inputEmailPrompt.innerHTML = 'Email address cannot be empty.';
        } else {
            inputEmailPrompt.innerHTML = 'Invalid email.';
        }

        form.classList.add('was-validated');
    });
}