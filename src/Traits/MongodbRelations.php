<?php

namespace TassawarTech174\MongodbRelations\Traits;

use TassawarTech174\MongodbRelations\Relations\UnidirectionalManyToManyRelation;

trait MongodbRelations
{
    /**
     * Custom MongoDB-style many-to-many (unidirectional).
     */
    public function manyToManyRelation(string $relatedModel, string $localKeyField)
    {
        $instance = new $relatedModel;
        return new UnidirectionalManyToManyRelation($instance, $this, $localKeyField);
    }
}
