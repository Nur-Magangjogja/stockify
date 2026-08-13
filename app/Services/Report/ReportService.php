<?php

namespace App\Services\Report;

interface ReportService
{
    /**
     * Get stock summary report.
     *
     * @param array $filters
     * @param bool $paginate
     * @param int $perPage
     * @return array
     */
    public function getStockSummary(array $filters = [], bool $paginate = true, int $perPage = 20): array;

    /**
     * Get transaction report.
     *
     * @param array $filters
     * @param bool $paginate
     * @param int $perPage
     * @return array
     */
    public function getTransactionReport(array $filters = [], bool $paginate = true, int $perPage = 20): array;

    /**
     * Get activity report (paginated).
     *
     * @param array $filters
     * @return mixed
     */
    public function getActivityReport(array $filters = [], int $perPage = 20);

    /**
     * Get dashboard summary data.
     *
     * @return array
     */
    public function getDashboardSummary(?int $month = null, ?int $year = null): array;
}
