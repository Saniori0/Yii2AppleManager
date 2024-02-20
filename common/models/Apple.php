<?php

namespace common\models;

use PHPUnit\Util\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $created_at
 */
class Apple extends ActiveRecord
{

    const COLORS = ["green", "red", "yellow", "blue", "brown", "gray"];
    const FRESH_DAYS = 5; // Days does an apple remain fresh from the moment it is created. Wikipedia says 5 - 7 days.

    const STATUS_EATED = 0;
    const STATUS_ON_THE_TREE = 1;
    const STATUS_ON_THE_GROUND = 2;

    public $color;
    public $status;
    public $left; // How many % of apple left

    public static function tableName()
    {
        return '{{%apple}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ON_THE_TREE],
            ['left', 'default', 'value' => 100],
            ['color', 'default', 'value' => self::COLORS[0]],
            ['color', 'in', 'range' => self::COLORS],
            ['status', 'in', 'range' => [self::STATUS_ON_THE_TREE, self::STATUS_ON_THE_GROUND, self::STATUS_EATED]],
        ];
    }

    public static function getColorByIndex(int $index): string
    {

        return self::COLORS[$index] ?: self::COLORS[0];

    }

    public static function getRandomColor(): string
    {

        return array_rand(self::COLORS);

    }

    public function pluckAndEat(float $percentToEat = 100): bool
    {

        return $this->pluck() && $this->eat($percentToEat);

    }


    public function eat(float $percentToEat = 100): bool
    {

        if ($this->status == self::STATUS_ON_THE_TREE) throw new Exception("Apple on the tree");

        $this->left -= abs($percentToEat);

        if ($this->left <= 100) {

            $this->status = self::STATUS_EATED;

        }

        return $this->save();

    }

    public function pluck(): bool
    {

        if ($this->status == self::STATUS_ON_THE_GROUND) return true;

        $this->status = self::STATUS_ON_THE_GROUND;

        return $this->save();

    }

    public function daysLived(): int
    {

        return time() - $this->created_at;

    }

    public function setColorByIndex(int $index = 0): bool
    {

        $color = self::getColorByIndex($index);

        if ($this->color == $color) return true;

        $this->color = $color;

        return $this->save();

    }

    public function isFresh(): bool
    {

        return $this->daysLived() < 84600 * self::FRESH_DAYS;

    }


}