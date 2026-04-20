<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content register-modal-content">

            <div class="modal-header register-modal-header">
                <div class="register-modal-title-group">
                    <h5 class="modal-title" id="registerModalLabel">Create account</h5>
                </div>
                <button type="button" class="register-close-btn" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body register-modal-body">
                <form action="register.php" method="POST" id="registerForm">

                    <div class="register-field">
                        <label class="register-label">Full name</label>
                        <input type="text" name="full_name" class="register-input" placeholder="Jane Smith" required>
                    </div>

                    <div class="register-field">
                        <label class="register-label">Username</label>
                        <input type="text" name="username" class="register-input" placeholder="janesmith" required>
                    </div>

                    <div class="register-field">
                        <label class="register-label">Email</label>
                        <div class="register-input-wrapper">
                            <input type="email" name="email" class="register-input has-icon" placeholder="jane@example.com" required>
                        </div>
                    </div>

                    <div class="register-field">
                        <label class="register-label">Password</label>
                        <div class="register-input-wrapper">
                            <input type="password" id="registerPassword" name="password" class="register-input has-icon has-toggle" placeholder="Min. 8 characters" required autocomplete="new-password">
                            <button type="button" class="register-pw-toggle" id="registerPwToggle" aria-label="Toggle password visibility">
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn register-submit-btn">
                        Create account
                    </button>
                    <p class="register-signin-link">Already have an account? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#loginModal">Sign in</a></p>
                </form>
            </div>

        </div>
    </div>
</div>