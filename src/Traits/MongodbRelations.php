<?php

namespace TassawarTech174\MongodbRelations\Traits;

use TassawarTech174\MongodbRelations\Relations\UnidirectionalManyToManyRelation;

trait MongodbRelations
{
    /**
     * Custom MongoDB many-to-many (unidirectional).
    */
    public function manyToManyRelation(string $relatedModel, string $localKeyField = null)
    {
        $instance = new $relatedModel;
        $localKeyField = $localKeyField ?? config('mongodb-relations.default_local_key', 'related_ids');
        return new UnidirectionalManyToManyRelation($instance, $this, $localKeyField);
    }
}