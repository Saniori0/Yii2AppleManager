<?php

namespace backend\controllers;

use common\models\Apple;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ApplesController extends Controller
{


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "actions" => ["index", "create", "eat", "pluck", "delete"],
                        "allow" => true,
                        "roles" => ["@"],
                    ],
                ],
            ],
            "verbs" => [
                "class" => VerbFilter::class,
                "actions" => [
                    "eat" => ["post", "get"],
                    "pluck" => ["get"],
                    "delete" => ["get"],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            "error" => [
                "class" => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function actionIndex()
    {

        return $this->goHome();

    }

    private function getAppleByGetParamAppleId(){

        $AppleID = Yii::$app->request->get("id", 0);

        $Apple = Apple::findOne($AppleID);

        if (!$Apple) return $this->render("@backend/views/site/error.php", ["name" => "Not Found", "message" => "Apple not exist"]);

        return $Apple;

    }

    public function actionCreate()
    {

        $apple = new Apple();

        $apple->color = Apple::getRandomColor();

        $apple->save();

        return $this->goHome();

    }
    public function actionEat()
    {

        $Apple = $this->getAppleByGetParamAppleId();

        if(is_string($Apple)) return $Apple;

        if ($Apple->canEat()) {

            if (Yii::$app->request->getMethod() == "POST") {

                $EatPercent = (float) Yii::$app->request->post("eat_percent", 0);

                $Apple->eat($EatPercent);

                return $this->actionIndex();

            }

            return $this->render("eat", [
                "apple" => $Apple
            ]);

        }

        return $this->render("error", ["name" => "Cant Eat", "message" => "Apple not fresh or on the tree"]);

    }


    public function actionPluck()
    {

        $Apple = $this->getAppleByGetParamAppleId();

        if(is_string($Apple)) return $Apple;

        $Apple->pluck();

        return $this->actionIndex();

    }

    public function actionDelete()
    {

        $Apple = $this->getAppleByGetParamAppleId();

        if(is_string($Apple)) return $Apple;

        $Apple->delete();

        return $this->actionIndex();

    }

}