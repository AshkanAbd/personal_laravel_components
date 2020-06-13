<?php

namespace App\Component\Extensions;

use Illuminate\Database\Eloquent\Builder;

trait PaginationExtension
{
    /**
     * Normalize $page and $pageSize from request
     *
     * @param $pageSize
     * @param $page
     */
    private function normalizePagination(&$pageSize, &$page)
    {
        $defaultPageSize = components('DEFAULT_PAGE_SIZE', '10');
        if (!is_numeric($page)) {
            $page = '1';
        }
        if ((int)$page <= 0) {
            $page = '1';
        }
        if (!is_numeric($pageSize)) {
            $pageSize = $defaultPageSize;
        }
        if ((int)$pageSize == 0 || (int)$pageSize < -1) {
            $pageSize = $defaultPageSize;
        }
    }

    /**
     * Perform pagination on given $query
     *
     * @param Builder $query
     * @param array $hiddenArray
     * @param string $dataName
     * @param bool $withGet
     * @param array $visibleArray
     * @return array
     */
    protected function usePagination($query, $hiddenArray = [], $visibleArray = [], $dataName = 'list', $withGet = false)
    {
        $pageSize = request()->get('page_size', components('DEFAULT_PAGE_SIZE', '10'));
        $page = request()->get('page', components('DEFAULT_PAGE', '1'));
        $this->normalizePagination($pageSize, $page);
        if ($withGet) {
            $count = $query->get()->count();
        } else {
            $count = $query->count();
        }
        if ($pageSize != '-1') {
            $query->skip(($page - 1) * $pageSize)->take($pageSize);
        } else {
            $pageSize = $count === 0 ? 1 : $count;
        }
        $result = $query->get()
            ->makeHidden($hiddenArray)
            ->makeVisible($visibleArray);
        return [
            $dataName => $result,
            'page_number' => ceil($count / $pageSize),
            'total' => $count
        ];
    }
}