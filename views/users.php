<?php

use app\core\table\Table;

use app\models\DbUser;

$this->title = 'Users';

?>

<div class="col-md-6 offset-md-3">
    <h1 class="text-center mb-4">Users</h1>
    <div class="d-flex mb-4">
        <div class="align-self-start mr-auto">
            <a class="btn btn-primary<?= $index <= 0 ? ' disabled' : '' ?>" href="/users?index=<?= $index - 1; ?>" role="button"><i class="bi-arrow-left"></i></a>
        </div>
        <div class="align-self-center mx-auto">
            <p class="lead mb-0">Page <?= $index + 1; ?></p>
        </div>
        <div class="align-self-end ml-auto">
            <a class="btn btn-primary align-self-end<?= $rowsLeft <= 0 ? ' disabled' : '' ?>" href="/users?index=<?= $index + 1; ?>" role="button"><i class="bi-arrow-right"></i></a>
        </div>
    </div>
    <?php Table::print(['#', 'Username', 'E-mail address', 'Account status', 'Registered at'], DbUser::class, $users); ?>
</div>