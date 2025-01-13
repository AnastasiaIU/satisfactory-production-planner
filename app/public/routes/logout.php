<?php

// Logout route
Route::add('/logout', function () {
    unset($_SESSION['user']);
    header('Location: /');
});