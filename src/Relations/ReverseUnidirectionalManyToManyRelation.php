<?php

namespace TassawarTech174\MongodbRelations\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Collection;

class ReverseUnidirectionalManyToManyRelation extends Relation
{
    protected $related;
    protected $parent;
    protected string $foreignKey;
    protected string $parentKey;

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

    public function addEagerConstraints(array $models)
    {
        $parentIds = collect($models)->pluck($this->parentKey)->unique()->toArray();

        $this->query->whereIn($this->foreignKey, $parentIds);
    }

    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    public function match(array $models, Collection $results, $relation)
    {
        $foreignKey = $this->foreignKey;

        $resultsByKey = $results->groupBy($foreignKey);

        foreach ($models as $model) {
            $key = $model->getAttribute($this->parentKey);
            $related = $resultsByKey[$key] ?? $this->related->newCollection();
            $model->setRelation($relation, $related);
        }

        return $models;
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

    public function getQualifiedRelatedKeyName()
    {
        return $this->related->getQualifiedKeyName();
    }
}