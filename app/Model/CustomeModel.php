<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CustomeModel extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();
        // throw new \Exception(class_basename(get_called_class()));

        $user = \Auth::user();

        if (!$user) {
            return 0;
        }
        // auto-sets values on creation
        static::creating(function ($builder) use ($user) {

            $conn = $builder->getModel()->getConnection();
            $table = $builder->getModel()->getTable();

            // Set created_by column if exists
            try {
                $conn->getDoctrineColumn($table, 'created_by');
                $builder->created_by = $user->id;
            } catch (\Throwable $th) {
                //throw $th;
            }

        });

        // auto-sets values on update
        static::updating(function ($builder) use ($user) {
            $conn = $builder->getModel()->getConnection();
            $table = $builder->getModel()->getTable();
            // Set updated_by column if exists
            try {
                $conn->getDoctrineColumn($table, 'updated_by');
                $builder->updated_by = $user->id;
            } catch (\Throwable $th) {
                //throw $th;
            }
        });

        // auto-sets values on delete
        static::deleting(function ($builder) use ($user) {
            $conn = $builder->getModel()->getConnection();
            $table = $builder->getModel()->getTable();
            // Set deleted_by column if exists
            try {
                $conn->getDoctrineColumn($table, 'deleted_by');
                $builder->deleted_by = $user->id;

            } catch (\Throwable $th) {
                // throw $th;
            }
        });

    }

}
