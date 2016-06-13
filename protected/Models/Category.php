<?php

namespace App\Models;

use T4\Core\Exception;
use T4\Dbal\QueryBuilder;
use T4\Orm\Model;

/**
 * Class Category
 * @package App\Models
 *
 * @property string $title
 *
 * @property \T4\Core\Collection|\App\Models\Product[] $products
 */
class Category
    extends Model
{
    protected static $schema = [
        'table'     => 'categories',
        'columns'   => [
            'title' => ['type' => 'string'],
        ],
        'relations' => [
            'products' => ['type' => self::HAS_MANY, 'model' => Product::class]
        ],
    ];

    static protected $extensions = ['tree'];

    public function countChildProducts()
    {
        return Product::countAllByQuery(
            (new QueryBuilder())
                ->from(Product::getTableName())
                ->join(self::getTableName(), 't1.__category_id = j1.__id', 'left')
                ->where('j1.__lft >= :lft AND j1.__rgt <= :rgt')
                ->order('t1.__id ASC')
                ->params([
                    ':lft' => $this->__lft,
                    ':rgt' => $this->__rgt,
                ])
        );
    }

    protected function validateTitle($value)
    {
        if ('' === trim($value)) {
            throw new Exception('Title not valid');
        }
        return true;
    }

    protected function sanitizeTitle($value)
    {
        return trim($value);
    }
    
    protected function afterDelete()
    {
        foreach ($this->products as $product) {
            $product->delete();
        }
    }
}