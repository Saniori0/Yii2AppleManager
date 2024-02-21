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
            "action" => "index.php?r=apples/create",
            "method" => "post"
        ]);
        print html::submitButton("Create Apple", ["class" => "btn btn-primary"]);
        ActiveForm::end();

        ?>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Color</th>
            <th scope="col">Size</th>
            <th scope="col">Status</th>
            <th scope="col">Is Fresh</th>
            <th scope="col">Create Time</th>
            <th scope="col">Fell Time</th>
            <th scope="col">Delete</th>
            <th scope="col">Pluck</th>
            <th scope="col">Eat</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($apples as $apple) {

            $status = ($apple->status == 1) ? "On Tree" : "On Ground";
            $freshColor = $apple->isFresh() ? "lime" : "red";
            $fell_time = $apple->status == 2 ? $apple->fell_time : "On Tree";
            $canEat = $apple->canEat();

            $buttons = [
                    "pluck" => $apple->isOnTree() ? "<a href=\"index.php?r=apples/pluck&id={$apple->id}\" class=\"btn btn-primary\">pluck</a>" : "-",
                    "eat" => $canEat ? "<a href=\"index.php?r=apples/eat&id={$apple->id}\" class=\"btn btn-success\">eat</a>" : "-"
            ];


            print("<tr>
                   <th scope=\"row\">{$apple->id}</th>
                   <td><div style=\"width:25px;height:25px;background-color:{$apple->color};border-radius:100px;\"></div></td>
                   <td>{$apple->size}%</td>
                   <td>$status</td>
                   <td><div style=\"width:15px;height:15px;background-color:{$freshColor};border-radius:100px;\"></div></td>
                   <td>{$apple->create_time}</td>
                   <td>$fell_time</td>
                   <td><a href=\"index.php?r=apples/delete&id={$apple->id}\" class=\"btn btn-danger\">delete</a></td>
                   <td>{$buttons['pluck']}</td>
                   <td>{$buttons['eat']}</td>
                   </tr>");

        }
        ?>
        </tbody>
    </table>

</div>
