<?php require_once '../auth/partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Create Account</h2>
                    <form id="registerForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="/login">Login here</a></p>
                    </div>
                </div>
            </div>
            <div id="registerAlert" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        
        // Validate passwords match
        if ($('#password').val() !== $('#confirmPassword').val()) {
            showAlert('registerAlert', 'Passwords do not match', 'danger');
            return;
        }

        $.ajax({
            url: '/api/auth/register',
            type: 'POST',
            data: JSON.stringify({
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val()
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.error) {
                    showAlert('registerAlert', response.error, 'danger');
                } else {
                    showAlert('registerAlert', 'Registration successful! Redirecting...', 'success');
                    // Store the API keys securely (in practice, you might want to handle this differently)
                    localStorage.setItem('api_key', response.api_key);
                    localStorage.setItem('secret_key', response.secret_key);
                    // Redirect to player after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/player';
                    }, 2000);
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Registration failed';
                showAlert('registerAlert', error, 'danger');
            }
        });
    });

    function showAlert(containerId, message, type) {
        const alert = $(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);
        $(`#${containerId}`).html(alert);
    }
});
</script>

<?php require_once 'partials/footer.php'; ?>