<?php

namespace App\Repository;

use App\Models\WorkOrder;
use Illuminate\Support\Collection;

interface IWorkOrderRepository
{
    function addWorkOrder(array $request): ?WorkOrder;
    function getWorkOrderById(string $post_id): Collection;
    function getWorkOrderLoadBy(string $year, string $month, string $idsWellName, bool $isRig): Collection;
    function getWorkOrderNameByMonth(string $year, string $month): Collection;
    function searchWorkOrderByWell(
        string $wellNumber, ?string $wbsNumber, ?string $createdDate, ?string $createdTime): Collection;

    // function searchWorkOrderBySize(int $page, int $size): Collection;
    function updateWorkOrder(string $workOrderId, array $request): ?WorkOrder;
    function removeWorkOrder(string $workOrderId): bool;
    function removeWorkOrderBy(string $postId): bool;
}
