<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Orm\Model;

/**
 * Class Product
 * @package App\Models
 *
 * @property string $title
 * @property float $price
 * @property \App\Models\Category $category
 */
class Product
    extends Model
{
    protected static $schema = [
        'columns'   => [
            'title' => ['type' => 'string'],
            'price' => ['type' => 'float'],
        ],
        'relations' => [
            'category' => ['type' => self::BELONGS_TO, 'model' => Category::class]
        ],
    ];

    protected function validateTitle($value)
    {
        if ('' === trim($value)) {
            throw new Exception('Title not valid');
        }
        return true;
    }

    protected function validatePrice($value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_FLOAT)) {
            throw new Exception('Price not valid');
        }
        return true;
    }

    protected function sanitizeTitle($value)
    {
        return trim($value);
    }
    
    protected function sanitizePrice($value)
    {
        return (float)$value;
    }
}
