<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content auth-modal-content">

            <div class="modal-header auth-modal-header">
                <div class="auth-modal-title-group">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                </div>
                <button type="button" class="auth-close-btn" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body auth-modal-body">
                <form action="login.php" method="POST" id="loginForm">

                    <div class="auth-field">
                        <label class="auth-label">Email</label>
                        <div class="auth-input-wrapper">
                            <input type="email" name="email" class="auth-input has-icon" placeholder="jane@example.com" required>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label class="auth-label">Password</label>
                        <div class="auth-input-wrapper">
                            <input type="password" id="loginPassword" name="password" class="auth-input has-icon has-toggle" placeholder="Enter your password" required autocomplete="current-password">
                            <button type="button" class="auth-pw-toggle" id="loginPwToggle" aria-label="Toggle password visibility">
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn auth-submit-btn">
                        Login
                    </button>
                    <p class="auth-signin-link">
                        Don't have an account?
                        <a href="#"
                            data-bs-dismiss="modal"
                            data-bs-toggle="modal"
                            data-bs-target="#registerModal">
                            Create one
                        </a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>