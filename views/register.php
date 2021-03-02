<div class="row">
    <div class="col-md-4 offset-md-4 card bg-light mt-4 mb-4">
        <img src="images/logo.png" class="card-img-top p-5" alt="Logo">
        <div class="card-body">
            <h2 class="card-title text-center">Create an account</h2>
            <form class="card-text" action="/register" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" pattern="[a-zA-Z0-9]{4,16}" class="form-control" name="username" id="username" aria-describedby="username_desc" required>
                    <div id="username_desc" class="form-text">Must be between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters.</div>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" minlength="8" class="form-control" name="password" id="password" aria-describedby="password_desc" required>
                    <div id="password_desc" class="form-text">Must be at least 8 characters long. It is advised to use a combination of lower-/uppercase letters, numbers and special characters.</div>
                </div>
                <div class="mb-3">
                    <label for="passwordConfirm" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="passwordConfirm" id="passwordConfirm" required>
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">E-mail Address</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="email_desc" required>
                    <div id="email_desc" class="form-text">Will never be shared with third parties.</div>
                </div>
                <div class="mb-5">
                    <label for="emailConfirm" class="form-label">Confirm E-mail Address</label>
                    <input type="email" class="form-control" name="emailConfirm" id="emailConfirm" required>
                </div>
                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>