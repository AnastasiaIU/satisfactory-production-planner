/**
 * Initializes event listeners and functions when the DOM content is fully loaded.
 */
document.addEventListener("DOMContentLoaded", async () => {
    if (window.location.pathname === '/') {
        setCurrentNavItem('nav-item-planner');
        await initDropdown();
    }

    if (window.location.pathname === '/register') {
        setCurrentNavItem('');
        initRegisterForm();
    }

    if (window.location.pathname === '/login') {
        setCurrentNavItem('');
        initLoginForm();
    }

    if (window.location.pathname === '/plans') {
        setCurrentNavItem('nav-item-plans');
    }

    enableBootstrapFormValidation();
});

/**
 * Enables Bootstrap form validation.
 * Adds an event listener to the window's load event to apply custom Bootstrap validation styles to forms.
 * Prevents form submission if the form is invalid and adds the 'was-validated' class to the form.
 */
function enableBootstrapFormValidation() {
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
}

/**
 * Sets the current navigation item as active based on the provided ID.
 *
 * @param {string} id The ID of the navigation item to set as active.
 */
function setCurrentNavItem(id) {
    const navItems = document.querySelectorAll('.nav-link');
    navItems.forEach(item => {
        if (item.id === id) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}