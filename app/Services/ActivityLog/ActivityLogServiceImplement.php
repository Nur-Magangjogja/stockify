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
    public function getActivitiesPaginated(array $filters = [], int $perPage = 20, bool $paginate = true)
    {
        return $this->repository->getPaginated($filters, $perPage, $paginate);
    }

    /**
     * Delete activity logs based on specific date range or month.
     *
     * @param string $mode 'range' or 'month'
     * @param string|array $value Array [start, end] or Month (Y-m)
     * @return void
     */
    public function deleteActivityLogs(string $mode, string|array $value): void
    {
        $query = \App\Models\ActivityLog::query();
        $logMessage = '';
        
        if ($mode === 'range') {
            // Need to cover the entire day of the end_date by appending time
            $startDate = $value[0] . ' 00:00:00';
            $endDate = $value[1] . ' 23:59:59';
            $query->whereBetween('created_at', [$startDate, $endDate]);
            $logMessage = "Menghapus data aktivitas pengguna dari {$value[0]} hingga {$value[1]}.";
        } elseif ($mode === 'month') {
            $parts = explode('-', $value);
            $query->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            $monthName = \Carbon\Carbon::createFromDate($parts[0], $parts[1])->translatedFormat('F Y');
            $logMessage = "Menghapus data aktivitas pengguna pada bulan $monthName.";
        }

        $query->delete();
        
        // Log this action as a system action, not deleted!
        $this->log('delete_activities', 'Setting', 1, $logMessage);
    }
}
