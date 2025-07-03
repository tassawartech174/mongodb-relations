<?php

namespace TassawarTech174\MongodbRelations\Traits;

use TassawarTech174\MongodbRelations\Relations\UnidirectionalManyToManyRelation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

trait MongodbRelations
{
    /**
     * Custom MongoDB many-to-many (unidirectional).
    */
    public function manyToManyRelation(
        string $relatedModel,
        ?string $collection = null,
        ?string $foreignPivotKey = null,
        ?string $relatedPivotKey = null,
        ?string $parentKey = null,
        ?string $relatedKey = null,
        ?string $relation = null
    ) {
        $instance = new $relatedModel;

        $foreignPivotKey ??= Str::snake(class_basename($relatedModel)) . '_ids';
        $relatedPivotKey ??= '_id';
        $parentKey ??= $this->getKeyName();
        $relatedKey ??= $instance->getKeyName();
        return new UnidirectionalManyToManyRelation(
            related: $instance,
            parent: $this,
            localKey: $foreignPivotKey,
            relatedKey: $relatedPivotKey,
            parentKey: $parentKey
        );
    }
    /**
     * Reverse MongoDB many-to-many (array lookup on parent side).
    */
    public function reverseManyToManyRelation(string $inverseModelClass, string $foreignKey, string $primaryKey)
    {
        $instance = new $inverseModelClass;
        return new hasMany($instance, null, $foreignKey, $primaryKey);
    }
}