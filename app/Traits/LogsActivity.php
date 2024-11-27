<?php

namespace App\Traits;

use App\Classes\Common;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logCreatedEvent($model);
        });
        
        static::updated(function ($model) {
            $model->logUpdatedEvent($model);
        });

        static::deleted(function ($model) {
            $model->logDeletedEvent($model);
        });
    }

     /**
     * Log the created event for a model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected function logCreatedEvent($model)
    {
        $modelAttributes = $this->getAttributes();
        $logType = '';

        $excludedLogFields = ['id', 'individual_id', 'created_at', 'updated_at'];

        if(method_exists($this, 'getExcludedLogFields')) {
            $excludedLogFields = array_merge($excludedLogFields, $this->getExcludedLogFields());
        }

        $logNotes = [];

        foreach ($modelAttributes as $field => $value) {
            // Skip excluded fields
            if (in_array($field, $excludedLogFields)) {
                continue;
            }

            if($this->isForeignKey($field)) {
                $relatedValue = $this->getRelatedModelAttribute($model, $field, 'name');
                $logNotes[$field] = $relatedValue ?? 'Unknown';
            }
            else {
                $logNotes[$field] = $value;
            }
        }

        $individualId = $this->getIndividualId($model);
        $logType = $this->getLogType($model);

        Common::storeIndividualLog(
            $individualId,
            $logType . Str::singular($model->getTable()),
            json_encode($logNotes)
        );
    }

    protected function logUpdatedEvent($model)
    {
        $updatedData = $model->getDirty();
        $originalData = $model->getOriginal();
        $logType = '';

        $excludedLogFields = ['updated_at'];
        if(method_exists($this, 'getExcludedLogFields')) {
            $excludedLogFields = array_merge($excludedLogFields, $this->getExcludedLogFields());
        }

        $changes = [];

        foreach ($updatedData as $field => $newValue) {

            if (in_array($field, $excludedLogFields)) {
                continue;
            }

            if($this->isForeignKey($field)) {
                $relatedValue = $this->getRelatedModelAttribute($model, $field, 'name', $newValue);
                $changes[$field] = [
                    'old_value' => $this->getRelatedModelAttribute($model, $field, 'name', $originalData[$field]),
                    'new_value' => $relatedValue ?? 'Unknown',
                ];
            } else {
                $changes[$field] = [
                    'old_value' => $originalData[$field],
                    'new_value' => $newValue,
                ];
            }
        }

        $individualId = $this->getIndividualId($model);
        $logType = $this->getLogType($model);

        if (!empty($changes)) {
            Common::storeIndividualLog(
                $individualId, 
                'updated_' . $logType . Str::singular($model->getTable()), 
                json_encode($changes)
            );
        }
    }

    protected function logDeletedEvent($model)
    {
        $modelAttributes = $model->getAttributes();
        $logType = '';

        $excludedLogFields = ['id', 'individual_id', 'created_at', 'updated_at'];

        if(method_exists($this, 'getExcludedLogFields')) {
            $excludedLogFields = array_merge($excludedLogFields, $this->getExcludedLogFields());
        }

        $logNotes = [];
        foreach ($modelAttributes as $field => $value) {
            // Skip excluded fields
            if (in_array($field, $excludedLogFields)) {
                continue;
            }
            if($this->isForeignKey($field)) {
                $relatedValue = $this->getRelatedModelAttribute($model, $field, 'name');
                $logNotes[$field] = $relatedValue ?? 'Unknown';
            } else {
                $logNotes[$field] = $value;
            }
        }

        $individualId = $this->getIndividualId($model);
        $logType = $this->getLogType($model);

        Common::storeIndividualLog($individualId, 'deleted_' . $logType . Str::singular($model->getTable()), json_encode($logNotes));
    }

     /**
     * Determine if a field is a foreign key.
     *
     * @param string $field
     * @return bool
     */
    protected function isForeignKey(string $field): bool
    {
        return Str::endsWith($field, '_id');
    }

    /**
     * Retrieve a related model's attribute for logging.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $field
     * @param string $attribute
     * @param mixed|null $relatedId
     * @return mixed|null
     */
    protected function getRelatedModelAttribute(Model $model, string $field, string $attribute = 'name', $relatedId = null)
    {
        // Derive the relationship name by removing '_id'
        $relationName = Str::before($field, '_id');

        // Check if the relationship method exists
        if (!method_exists($model, $relationName)) {
            // Attempt to resolve the related model class dynamically
            $relatedModelClass = $this->resolveRelatedModelClass($relationName);
            if (!$relatedModelClass) {
                return null;
            }

            // Fetch the related model instance
            $relatedModel = $relatedId 
                ? $relatedModelClass::find($relatedId) 
                : $relatedModelClass::find($model->$field);

            return $relatedModel ? $relatedModel->$attribute : null;
        }

        // Utilize the defined relationship
        $relatedModel = $model->$relationName;

        return $relatedModel ? $relatedModel->$attribute : null;
    }

    /**
     * Resolve the related model class based on the relation name.
     *
     * @param string $relationName
     * @return string|null
     */
    protected function resolveRelatedModelClass(string $relationName): ?string
    {
        // Convert relation name to StudlyCase for the model class
        $modelClassName = Str::studly(Str::singular($relationName));

        // Define the default namespace for models
        $defaultNamespace = 'App\\Models\\';

        // Complete model class with namespace
        $fullModelClass = $defaultNamespace . $modelClassName;

        // Check if the class exists
        if (class_exists($fullModelClass)) {
            return $fullModelClass;
        }

        // Optionally, handle cases where models are in different namespaces or need custom resolution
        // Add additional logic here if necessary

        return null;
    }

    /**
    * Mask sensitive fields.
    */
    protected function maskSensitiveField($field, $value)
    {
        if ($field === 'card_number') {
            return str_repeat('*', strlen($value) - 4) . substr($value, -4);
        }

        if ($field === 'CVV') {
            return '***';
        }

        return $value;
    }

    protected function getIndividualId($model)
    {
        return $model->individual_id 
        ?? $model->individual?->id 
        ?? $model->debt?->individual?->id;
    }

    protected function getLogType($model)
    {
        return !$model->individual_id && !$model->individual ? ($model->debt ? 'debt_' : '') : '';
    }
}