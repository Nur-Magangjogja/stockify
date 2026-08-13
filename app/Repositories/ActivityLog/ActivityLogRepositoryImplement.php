<?php

namespace App\Repositories\ActivityLog;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ActivityLog;

class ActivityLogRepositoryImplement extends Eloquent implements ActivityLogRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(ActivityLog $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new activity log entry.
     *
     * @param array $data
     * @return mixed
     */
    public function createLog(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Get recent activity logs with user relation.
     *
     * @param int $limit
     * @return mixed
     */
    public function getRecent(int $limit = 20)
    {
        return $this->model->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get paginated activity logs with filters.
     *
     * @param array $filters
     * @param bool $paginate
     * @return mixed
     */
    public function getPaginated(array $filters = [], int $perPage = 20, bool $paginate = true)
    {
        $query = $this->model->with('user')->orderBy('created_at', 'desc');

        if (!empty($filters['user_id'])) {
            $query->byUser($filters['user_id']);
        }

        if (!empty($filters['action'])) {
            $query->byAction($filters['action']);
        }

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('created_at', [
                $filters['date_from'] . ' 00:00:00',
                $filters['date_to'] . ' 23:59:59',
            ]);
        }

        return $paginate ? $query->paginate($perPage)->appends($filters) : $query->cursor();
    }
}
