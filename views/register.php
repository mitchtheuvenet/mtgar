<?php

use app\core\form\Field;
use app\core\form\Form;

?>

<div class="row">
    <div class="col-md-4 offset-md-4 card bg-light mt-4 mb-4">
        <a href="/" class="p-5">
            <img src="images/logo.png" class="card-img-top" alt="Logo">
        </a>
        <div class="card-body">
            <h2 class="card-title text-center">Create an account</h2>
            <?php $form = Form::begin('/register', 'post'); ?>
                <?php

                echo $form->field($model, 'username', Field::TYPE_TEXT, 3,
                        'Must be between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters.');

                echo $form->field($model, 'password', Field::TYPE_PASSWORD, 2,
                        'Must be at least 8 characters long. It is advised to use a combination of lower-/uppercase letters, numbers and special characters.');

                echo $form->field($model, 'passwordConfirm', Field::TYPE_PASSWORD);

                echo $form->field($model, 'email', Field::TYPE_EMAIL, 2, 'Will never be shared with third parties.');

                echo $form->field($model, 'emailConfirm', Field::TYPE_EMAIL, 5);

                ?>
                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
            <?php Form::end(); ?>
        </div>
    </div>
</div>