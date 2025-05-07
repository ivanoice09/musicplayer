<?php require_once 'partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Login</h2>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Don't have an account? <a href="/register">Register here</a></p>
                    </div>
                </div>
            </div>
            <div id="loginAlert" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/api/auth/login',
            type: 'POST',
            data: JSON.stringify({
                email: $('#email').val(),
                password: $('#password').val()
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.error) {
                    showAlert('loginAlert', response.error, 'danger');
                } else {
                    // Store the JWT token
                    localStorage.setItem('jwt_token', response.token);
                    
                    // Set cookie if "Remember me" is checked
                    if ($('#rememberMe').is(':checked')) {
                        document.cookie = `jwt_token=${response.token}; max-age=${7 * 24 * 60 * 60}; path=/; secure; samesite=strict`;
                    }
                    
                    // Redirect to player
                    window.location.href = '/player';
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Login failed';
                showAlert('loginAlert', error, 'danger');
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