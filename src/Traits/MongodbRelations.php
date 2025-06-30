<?php

namespace TassawarTech174\MongodbRelations\Traits;

use TassawarTech174\MongodbRelations\Relations\UnidirectionalManyToManyRelation;
use Illuminate\Support\Str;

trait MongodbRelations
{
    /**
     * Custom MongoDB many-to-many (unidirectional).
    */
    public function manyToManyRelation(string $relatedModel, ?string $localKeyField = null)
    {
        $instance = new $relatedModel;
        if (empty($localKeyField)) {
            $classBaseName = class_basename($relatedModel);
            $localKeyField = Str::snake($classBaseName) . '_ids';
        }
        return new UnidirectionalManyToManyRelation($instance, $this, $localKeyField);
    }
}