<?php

$this->title = 'Error';

?>

<div class="col-md-8 offset-md-2 text-center">
    <h1>Error <?= $exception->getCode(); ?></h1>
    <p class="lead"><?= $exception->getMessage(); ?></p>
</div>