<?php

namespace App\Services\Report;

interface ReportService
{
    /**
     * Get stock summary report.
     *
     * @param array $filters
     * @return array
     */
    public function getStockSummary(array $filters = []): array;

    /**
     * Get transaction report.
     *
     * @param array $filters
     * @return array
     */
    public function getTransactionReport(array $filters = []): array;

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
    public function getDashboardSummary(): array;
}
