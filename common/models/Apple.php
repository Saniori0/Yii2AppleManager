<?php

namespace common\models;

use PHPUnit\Util\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property integer $created_at
 */
class Apple extends ActiveRecord
{

    const COLORS = ["green", "red", "yellow", "blue", "brown", "gray"];
    const FRESH_SECONDS = 18000; // Seconds does an apple remain fresh from the moment it is created.

    const STATUS_EATED = 0;
    const STATUS_ON_THE_TREE = 1;
    const STATUS_ON_THE_GROUND = 2;

    public static function tableName()
    {
        return "apple";
    }

    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::class,
                "createdAtAttribute" => "create_time",
                "updatedAtAttribute" => "update_time",
                "attributes" => ["fell_time"],
                "value" => new Expression("NOW()"),
            ],
        ];
    }

    public function rules()
    {
        return [
            ["status", "default", "value" => self::STATUS_ON_THE_TREE],
            ["size", "default", "value" => 100],
            ["color", "default", "value" => self::COLORS[0]],
            ["color", "in", "range" => self::COLORS],
            ["status", "in", "range" => [self::STATUS_ON_THE_TREE, self::STATUS_ON_THE_GROUND, self::STATUS_EATED]],
        ];
    }

    public static function getColorByIndex(int $index): string
    {

        return self::COLORS[$index] ?: self::COLORS[0];

    }

    public static function getRandomColorIndex(): string
    {

        return array_rand(self::COLORS);

    }

    public static function getRandomColor(): string
    {

        return self::getColorByIndex(self::getRandomColorIndex());

    }

    public function pluckAndEat(float $percentToEat = 100): bool
    {

        return $this->pluck() && $this->eat($percentToEat);

    }


    public function eat(float $percentToEat = 100): bool
    {

        if (!$this->canEat()) throw new Exception("Apple on the tree or not fresh");

        $this->size -= abs($percentToEat);

        if ($this->size <= 0) $this->status = self::STATUS_EATED;

        return $this->save();

    }

    public function pluck(): bool
    {

        if ($this->status == self::STATUS_ON_THE_GROUND) return true;

        $this->status = self::STATUS_ON_THE_GROUND;

        $this->touch("fell_time");

        return $this->save();

    }

    public function getCreateTimestamp(): false|int
    {

        return strtotime($this->create_time);

    }

    public function getFellTimestamp(): false|int
    {

        return strtotime($this->fell_time);

    }

    public function daysLived(): int
    {

        return time() - $this->getCreateTimestamp();

    }

    public function daysOnTheGround(): int
    {

        return time() - $this->getFellTimestamp();

    }

    public function setColor(int $colorIndex): bool
    {

        $color = self::getColorByIndex($colorIndex);

        if ($this->color == $color) return true;

        $this->color = $color;

        return $this->save();

    }

    public function setRandomColor(): bool
    {

        return $this->setColor($this->getRandomColorIndex());

    }

    public function isFresh(): bool
    {

        if ($this->isOnTree()) return true;

        return $this->daysOnTheGround() < self::FRESH_SECONDS;

    }

    public function isOnTree(): bool
    {

        return $this->status == self::STATUS_ON_THE_TREE;

    }

    public function canEat(): bool
    {

        return $this->isFresh() && !$this->isOnTree();

    }

}