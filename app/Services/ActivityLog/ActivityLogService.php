<?php

namespace App\Services\ActivityLog;

interface ActivityLogService
{
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
    ): void;

    /**
     * Get recent activity logs.
     *
     * @param int $limit
     * @return mixed
     */
    public function getRecentActivities(int $limit = 20);

    /**
     * Get paginated activity logs with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getActivitiesPaginated(array $filters = [], int $perPage = 20);

    /**
     * Delete activity logs based on specific date range or month.
     *
     * @param string $mode 'range' or 'month'
     * @param string|array $value Array [start, end] or Month (Y-m)
     * @return void
     */
    public function deleteActivityLogs(string $mode, string|array $value): void;
}
