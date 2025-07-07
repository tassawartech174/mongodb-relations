<?php

namespace TassawarTech174\MongodbRelations\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class UnidirectionalManyToManyRelation extends Relation
{
    protected $parent;
    protected $related;
    protected string $localKey;
    protected string $relatedKey;
    protected string $parentKey;

    public function __construct(
        Model $related,
        Model $parent,
        string $localKey,
        string $relatedKey,
        string $parentKey
    ) {
        $this->related = $related;
        $this->parent = $parent;
        $this->localKey = $localKey;
        $this->relatedKey = $relatedKey;
        $this->parentKey = $parentKey;

        parent::__construct($related->newQuery(), $parent);
    }

    /**
     * Base relation constraints are not applied here.
    */
    public function addConstraints()
    {
        // No global constraints
    }

    /**
     * Return related models for the current parent.
    */
    public function getResults()
    {
        $ids = $this->parent->{$this->localKey} ?? [];

        if (empty($ids)) {
            return $this->related->newCollection();
        }

        return $this->related
            ->whereIn($this->relatedKey, $ids)
            ->get();
    }

    /**
     * Apply constraints for eager loading.
    */
    public function addEagerConstraints(array $models)
    {
        $allIds = collect($models)
            ->pluck($this->localKey)
            ->flatten()
            ->unique()
            ->toArray();

        if (!empty($allIds)) {
            $this->query->whereIn($this->relatedKey, $allIds);
        }
    }

    /**
     * Initialize relation on all models to an empty collection.
    */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
    */
    public function match(array $models, Collection $results, $relation)
    {
        $resultDict = $results->keyBy($this->relatedKey);

        foreach ($models as $model) {
            $relatedIds = $model->{$this->localKey} ?? [];

            $relatedItems = collect($relatedIds)->map(function ($id) use ($resultDict) {
                return $resultDict[$id] ?? null;
            })->filter()->values()->all();

            $model->setRelation($relation, $this->related->newCollection($relatedItems));
        }

        return $models;
    }

    /**
     * Sync the related IDs into the parent document field.
    */
    public function sync(array $ids, bool $detaching = true)
    {
        $ids = array_values(array_unique($ids));
        $current = $this->parent->{$this->localKey} ?? [];

        $attached = array_diff($ids, $current);
        $detached = $detaching ? array_diff($current, $ids) : [];

        $final = $detaching
            ? $ids
            : array_values(array_unique(array_merge($current, $ids)));

        $this->parent->{$this->localKey} = $final;
        $this->parent->save();

        return [
            'attached' => array_values($attached),
            'detached' => array_values($detached),
            'updated'  => [],
        ];
    }

    /**
     * Attach one or more IDs to the parent document.
    */
    public function attach($ids)
    {
        $ids = array_values((array) $ids);
        $existing = $this->parent->{$this->localKey} ?? [];

        $this->parent->{$this->localKey} = array_values(array_unique(array_merge($existing, $ids)));
        $this->parent->save();
    }

    /**
     * Detach one or more IDs from the parent document.
    */
    public function detach($ids = null)
    {
        $existing = $this->parent->{$this->localKey} ?? [];

        if (is_null($ids)) {
            $this->parent->{$this->localKey} = [];
        } else {
            $ids = array_values((array) $ids);
            $this->parent->{$this->localKey} = array_values(array_diff($existing, $ids));
        }

        $this->parent->save();
    }
}