<?php

$this->title = 'Profile';

?>

<div class="col-md-8 offset-md-2">
    <h1 class="text-center mb-4">Profile</h1>
    <div class="row mb-2">
        <div class="col-md-6 text-end">
            <p class="lead fw-bold">Username</p>
            <p class="lead fw-bold">E-mail address</p>
            <p class="lead fw-bold">Registered since</p>
        </div>
        <div class="col-md-6">
            <p class="lead"><?= $user->username; ?></p>
            <p class="lead"><?= $user->email; ?><a href="/profile/change/email"><i class="bi bi-pencil-square mx-2"></i></a></p>
            <p class="lead"><?= strtok($user->created_at, ' '); ?></p>
        </div>
    </div>
    <div class="d-flex gap-3 justify-content-center">
        <a class="btn btn-primary" href="/profile/change/password" role="button"><i class="bi bi-pencil-square"></i> Change password</a>
        <a class="btn btn-outline-danger" href="/profile/delete" role="button"><i class="bi bi-trash-fill"></i> Delete account</a>
    </div>
</div>