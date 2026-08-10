<?php

namespace App\Services\ActivityLog;

use App\Repositories\ActivityLog\ActivityLogRepository;
use Illuminate\Support\Facades\Auth;

class ActivityLogServiceImplement implements ActivityLogService
{
    protected ActivityLogRepository $repository;

    public function __construct(ActivityLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Log an activity.
     *
     * @param string $action
     * @param string|null $modelType
     * @param int|null $modelId
     * @param string $description
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    public function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        string $description = '',
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        $this->repository->createLog([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
            'old_values'  => $oldValues ? json_encode($oldValues) : null,
            'new_values'  => $newValues ? json_encode($newValues) : null,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    /**
     * Get recent activity logs.
     *
     * @param int $limit
     * @return mixed
     */
    public function getRecentActivities(int $limit = 20)
    {
        return $this->repository->getRecent($limit);
    }

    /**
     * Get paginated activity logs with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getActivitiesPaginated(array $filters = [], int $perPage = 20)
    {
        return $this->repository->getPaginated($filters, $perPage);
    }
}
