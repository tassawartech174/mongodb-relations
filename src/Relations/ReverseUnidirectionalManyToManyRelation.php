<?php

namespace TassawarTech174\MongodbRelations\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ReverseUnidirectionalManyToManyRelation extends Relation
{
    protected $related;
    protected $parent;
    protected $foreignKey;
    protected $parentKey;

    public function __construct(Model $related, Model $parent, $foreignKey, $parentKey)
    {
        $this->related = $related;
        $this->parent = $parent;
        $this->foreignKey = $foreignKey;
        $this->parentKey = $parentKey;

        parent::__construct($related->newQuery(), $parent);
    }

    public function addConstraints()
    {
        if (static::$constraints) {
            $this->query->where($this->foreignKey, $this->parent->{$this->parentKey});
        }
    }

    public function getResults()
    {
        return $this->query->get();
    }

    public function getRelated()
    {
        return $this->related;
    }

    public function getLocalKey()
    {
        return $this->foreignKey;
    }
}