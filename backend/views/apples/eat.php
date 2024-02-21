<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>
<div class="site-index">

    <div>

        <h1>Apples</h1>


        <?php
        $form = ActiveForm::begin([
            "action" => "index.php?r=apples/eat&id={$apple->id}",
            "method" => "post"
        ]);
        print html::input("number", "eat_percent");
        print html::submitButton("Eat Apple", ["class" => "btn btn-primary"]);
        ActiveForm::end();

        ?>

    </div>

</div>
