<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content auth-modal-content">

            <div class="modal-header auth-modal-header">
                <div class="auth-modal-title-group">
                    <h5 class="modal-title" id="registerModalLabel">Create account</h5>
                </div>
                <button type="button" class="auth-close-btn" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body auth-modal-body">
                <form action="register.php" method="POST" id="registerForm">

                    <div class="form-field">
                        <label class="form-label-upper">Full name</label>
                        <input type="text" name="full_name" class="form-input" placeholder="Jane Smith" required>
                    </div>

                    <div class="form-field">
                        <label class="form-label-upper">Username</label>
                        <input type="text" name="username" class="form-input" placeholder="janesmith" required>
                    </div>

                    <div class="form-field">
                        <label class="form-label-upper">Email</label>
                        <div class="form-input-wrapper">
                            <input type="email" name="email" class="form-input has-icon" placeholder="jane@example.com" required>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label-upper">Password</label>
                        <div class="form-input-wrapper">
                            <input type="password" id="registerPassword" name="password" class="form-input has-icon has-toggle" placeholder="Min. 8 characters" required autocomplete="new-password">
                            <button type="button" class="form-pw-toggle" id="registerPwToggle" aria-label="Toggle password visibility">
                            </button>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label-upper">Confirm Password</label>
                        <div class="form-input-wrapper">
                            <input type="password" id="registerConfirmPassword" name="confirm_password" class="form-input has-icon has-toggle" placeholder="Confirm your password" required autocomplete="new-password">
                            <button type="button" class="form-pw-toggle" id="registerConfirmPwToggle" aria-label="Toggle password visibility">
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-platform btn-primary-solid btn-block">
                        Create account
                    </button>
                    <p class="auth-signin-link">
                        Already have an account?
                        <a href="#"
                            data-bs-dismiss="modal"
                            data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            Sign in
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>