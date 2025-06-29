<?php

namespace TassawarTech174\MongodbRelations\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class UnidirectionalBelongsToMany extends Relation
{
    protected $parent;
    protected $related;
    protected $localKey;

    public function __construct(Model $related, Model $parent, $localKey)
    {
        $this->related = $related;
        $this->parent = $parent;
        $this->localKey = $localKey;

        parent::__construct($related->newQuery(), $parent);
    }

    /**
     * Required by base Relation class.
     */
    public function addConstraints()
    {
        // We don't add any default constraints here.
    }

    /**
     * Get the results of the relationship.
     */
    public function getResults()
    {
        $ids = $this->parent->{$this->localKey} ?? [];

        if (empty($ids)) {
            return $this->related->newCollection();
        }

        return $this->related->whereIn('_id', $ids)->get();
    }

    /**
     * Eager load constraints.
     */
    public function addEagerConstraints(array $models)
    {
        $allIds = collect($models)->pluck($this->localKey)->flatten()->unique()->toArray();

        $this->query->whereIn('_id', $allIds);
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
        $resultDict = $results->keyBy('_id');

        foreach ($models as $model) {
            $relatedIds = $model->{$this->localKey} ?? [];

            $relatedItems = collect($relatedIds)->map(function ($id) use ($resultDict) {
                return $resultDict[$id] ?? null;
            })->filter()->values()->all(); // 🔧 convert to array with ->all()

            $model->setRelation(
                $relation,
                $this->related->newCollection($relatedItems)
            );
        }

        return $models;
    }
    public function sync(array $ids, $detaching = true)
    {
        $ids = array_values(array_unique($ids)); // Clean & unique

        $current = $this->parent->{$this->localKey} ?? [];

        $attached = array_diff($ids, $current);
        $detached = $detaching ? array_diff($current, $ids) : [];

        // Final set of IDs to save
        $final = $detaching ? $ids : array_unique(array_merge($current, $ids));

        // Save updated IDs to the parent model
        $this->parent->{$this->localKey} = $final;
        $this->parent->save();

        return [
            'attached' => array_values($attached),
            'detached' => array_values($detached),
            'updated' => [],
        ];
    }
    public function attach($ids)
    {
        $ids = array_values((array) $ids);
        $existing = $this->parent->{$this->localKey} ?? [];

        $this->parent->{$this->localKey} = array_values(array_unique(array_merge($existing, $ids)));
        $this->parent->save();
    }

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