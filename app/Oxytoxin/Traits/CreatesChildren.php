<?php

namespace App\Oxytoxin\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

trait CreatesChildren
{
    /**
     * {@inheritdoc}
     *
     * Use `children` key on `$attributes` to create child nodes.
     */
    public static function create(array $attributes = [], ?self $parent = null)
    {
        $children = Arr::pull($attributes, 'children');

        $instance = new static($attributes);

        if ($parent) {
            $instance->parent_id = $parent->id;
        }

        $instance->save();

        // Now create children
        $relation = new Collection;

        foreach ((array) $children as $child) {
            $relation->add($child = static::create($child, $instance));

            $child->setRelation('parent', $instance);
        }

        return $instance->setRelation('children', $relation);
    }
}
