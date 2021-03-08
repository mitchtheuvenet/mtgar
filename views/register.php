<?php

use app\core\form\Field;
use app\core\form\Form;

?>

<div class="row">
    <div class="col-md-4 offset-md-4 card bg-light mt-4 mb-4">
        <img src="images/logo.png" class="card-img-top p-5" alt="Logo">
        <div class="card-body">
            <h2 class="card-title text-center">Create an account</h2>
            <?php $form = Form::begin('/register', 'post'); ?>
                <?php

                echo $form->field($model, 'username', Field::TYPE_TEXT, 'Username', true, 3, [
                    'description' => 'Must be between 4&ndash;16 characters long, only consisting of alphabetical and/or numerical characters.',
                    'htmlAttrs' => [
                        'pattern' => '[a-zA-Z0-9]{4,16}'
                    ]
                ]);

                echo $form->field($model, 'password', Field::TYPE_PASSWORD, 'Password', true, 2, [
                    'description' => 'Must be at least 8 characters long. It is advised to use a combination of lower-/uppercase letters, numbers and special characters.',
                    'htmlAttrs' => [
                        'minlength' => 8
                    ]
                ]);

                echo $form->field($model, 'passwordConfirm', Field::TYPE_PASSWORD, 'Confirm Password', true);

                echo $form->field($model, 'email', Field::TYPE_EMAIL, 'E-mail Address', true, 2, [
                    'description' => 'Will never be shared with third parties.'
                ]);

                echo $form->field($model, 'emailConfirm', Field::TYPE_EMAIL, 'Confirm E-mail Address', true, 4);

                ?>
                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
            <?php Form::end(); ?>
        </div>
    </div>
</div>