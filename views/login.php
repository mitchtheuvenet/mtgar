<div class="row align-items-center h-100">
    <div class="col-md-4 offset-md-4 card bg-light">
        <img src="images/logo.png" class="card-img-top p-5" alt="Logo">
        <div class="card-body">
            <h2 class="card-title text-center">Login to proceed</h2>
            <form class="card-text" action="/login" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-5 form-check">
                    <input type="checkbox" class="form-check-input" name="rememberMe" id="rememberMe">
                    <label for="rememberMe" class="form-check-label">Remember me</label>
                </div>
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                </div>
                <div class="text-center mb-2">
                    <a href="#">Forgot password</a>?
                </div>
                <div class="text-center">
                    New here? <a href="/register">Create an account</a>.
                </div>
            </form>
        </div>
    </div>
</div>