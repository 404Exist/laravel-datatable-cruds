<?php

namespace Exist404\DatatableCruds\QueryBuilder;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HasRelation
{
    private array $joins = [];

    private function isRelatedToModel(string $field): bool
    {
        return isset(explode('.', $field)[1]);
    }

    private function leftJoinRelation(string $relationName): Builder
    {
        $relations = explode('.', $relationName);

        foreach ($relations as $index => $relationName) {
            $childRelationName = "";

            for ($i = 0; $i <= $index; $i++) {
                $childRelationName .= $relations[$i] . ".";
            }

            if ($index > 0) {
                $parentTableName = $this->relatedTableName($relations[$index - 1]);
            }

            $this->leftJoin($childRelationName, $parentTableName ?? $this->tableName);
        }

        return $this->query;
    }

    private function leftJoin(string $childRelationName, string $parentTableName): Builder
    {
        $childRelation = $this->relatedRelation($childRelationName);
        $relatedTableName = $this->relatedTableName($childRelationName);

        if (in_array($relatedTableName, $this->joins)) {
            return $this->query;
        }

        $this->joins[] = $relatedTableName;

        return $this->query
            ->leftJoin($relatedTableName, function ($join) use ($parentTableName, $childRelation, $relatedTableName) {
                $relatedForeign = $childRelation->getForeignKeyName();
                $relatedOwnerKey = $childRelation->getOwnerKeyName();
                if (method_exists($childRelation, "getMorphType")) {
                    $join->where(
                        "$relatedTableName.{$childRelation->getMorphType()}",
                        "{$childRelation->getMorphClass()}"
                    );
                }
                if ($childRelation instanceof BelongsTo) {
                    $join->on("$parentTableName.$relatedForeign", '=', "$relatedTableName.$relatedOwnerKey");
                } else if ($childRelation instanceof HasOne) {
                    $relatedLocal = $childRelation->getLocalKeyName();
                    $join->on("$relatedTableName.$relatedForeign", '=', "$parentTableName.$relatedLocal");
                } else {
                    $join->on("$relatedTableName.$relatedForeign", '=', "$parentTableName.$relatedOwnerKey");
                }
            });
    }

    private function relatedRelation(string $relationName): Relation
    {
        $relationName = trim($relationName, ".");
        $relationNames = explode('.', $relationName);
        $relatedModel = $this->model;
        $relation = $relationName;

        if (count($relationNames) > 1) {
            $relation = array_pop($relationNames);

            foreach ($relationNames as $relationName) {
                $relatedModel = $relatedModel->$relationName()->getModel();
            }
        }

        return $relatedModel->$relation();
    }

    private function relatedModel(string $relationName): Model
    {
        return $this->relatedRelation($relationName)->getModel();
    }

    private function relatedTableName(string $relationName): string
    {
        $model = $this->relatedModel($relationName);
        return $this->tableName($model->getQuery(), $model->getTable());
    }

    private function listRelationAndColumn(string $field): array
    {
        $fields = explode('.', $field);

        $column = array_pop($fields);

        $relations = "";

        foreach ($fields as $relation) {
            $relations .= ".$relation";
        }

        return [ltrim($relations, "."), $column];
    }
}
