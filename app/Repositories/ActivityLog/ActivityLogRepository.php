<?php

namespace App\Repositories\ActivityLog;

use LaravelEasyRepository\Repository;

interface ActivityLogRepository extends Repository
{
    /**
     * Create a new activity log entry.
     *
     * @param array $data
     * @return mixed
     */
    public function createLog(array $data);

    /**
     * Get recent activity logs with user relation.
     *
     * @param int $limit
     * @return mixed
     */
    public function getRecent(int $limit = 20);

    /**
     * Get paginated activity logs with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginated(array $filters = [], int $perPage = 20);
}
