<?php

namespace TassawarTech174\MongodbRelations\Traits;

use TassawarTech174\MongodbRelations\Relations\UnidirectionalManyToManyRelation;
use TassawarTech174\MongodbRelations\Relations\ReverseUnidirectionalManyToManyRelation;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait MongodbRelations
{
    /**
     * Custom MongoDB many-to-many (unidirectional).
    */
    public function manyToManyRelation($related, $collection = null, $foreignKey = null, $otherKey = null, $parentKey = null, $relatedKey = null, $relation = null) 
    {
        $instance = new $related;
        $foreignKey ??= Str::snake(class_basename($related)) . '_ids';
        $otherKey ??= '_id';
        $parentKey ??= $this->getKeyName();
        $relatedKey ??= $instance->getKeyName();
        if (is_array($this->{$foreignKey} ?? null)) {
            return new UnidirectionalManyToManyRelation(
                related: $instance,
                parent: $this,
                localKey: $foreignKey,
                relatedKey: $otherKey,
                parentKey: $parentKey
            );
        } else {
            return new ReverseUnidirectionalManyToManyRelation(
                related: $instance,
                parent: $this,
                foreignKey: $foreignKey,
                parentKey: $parentKey
            );
        }
    }
    /**
     * MongoDB-compatible whereRelation (replacement for native Laravel whereRelation).
     *
     * @param Builder $query
     * @param string $relationName
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return Builder
    */
    public function scopeWhereRelationField(Builder $query, string $relationName, string $field, string $operator = '=', $value = null)
    {
        $relation = $this->$relationName();

        if (!method_exists($relation, 'getRelated')) {
            throw new \Exception("Relation [$relationName] does not support getRelated()");
        }

        $relatedModel = $relation->getRelated();

        $localKey = method_exists($relation, 'getLocalKey') ? $relation->getLocalKey() : Str::snake(class_basename($relatedModel)) . '_ids';

        $matchedIds = $relatedModel
            ->where($field, $operator, $value)
            ->pluck($relatedModel->getKeyName())
            ->toArray();

        if (empty($matchedIds)) {
            return $query->whereNull('_id'); // ensures no match
        }

        return $query->whereIn($localKey, $matchedIds);
    }
    /**
     * MongoDB-compatible orWhereRelation.
     *
     * @param Builder $query
     * @param string $relationName
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return Builder
    */
    public function scopeOrWhereRelationField(Builder $query, string $relationName, string $field, string $operator = '=', $value = null)
    {
        $relation = $this->$relationName();

        if (!method_exists($relation, 'getRelated')) {
            throw new \Exception("Relation [$relationName] does not support getRelated()");
        }

        $relatedModel = $relation->getRelated();

        $localKey = method_exists($relation, 'getLocalKey') ? $relation->getLocalKey() : Str::snake(class_basename($relatedModel)) . '_ids';

        $matchedIds = $relatedModel
            ->where($field, $operator, $value)
            ->pluck($relatedModel->getKeyName())
            ->toArray();

        if (empty($matchedIds)) {
            return $query->orWhereNull('_id');
        }

        return $query->orWhereIn($localKey, $matchedIds);
    }
}