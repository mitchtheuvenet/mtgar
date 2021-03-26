<?php

use app\core\Application;

$this->title = 'Profile';

?>

<div class="col-md-4 offset-md-4">
    <h1 class="text-center mb-4">Profile</h1>
    <div class="row">
        <div class="col-md-6 text-end">
            <p class="lead fw-bold">Username</p>
            <p class="lead fw-bold">E-mail address</p>
            <p class="lead fw-bold">Registered since</p>
        </div>
        <div class="col-md-6">
            <p class="lead"><?= $user->username; ?></p>
            <p class="lead"><?= $user->email; ?></p>
            <p class="lead"><?= strtok($user->created_at, ' '); ?></p>
        </div>
    </div>
</div>