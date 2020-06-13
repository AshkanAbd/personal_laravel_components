<?php

namespace App\Component\Extensions;

use Illuminate\Database\Eloquent\Builder;

trait SearchExtension
{
    /**
     * Add where like cause in given $query on $column from request search key
     * if $changes is numeric then sub $change from request search key
     *
     * @param Builder $query
     * @param string $column
     * @param null|integer $changes
     * @param bool $ignoreCase
     * @return Builder
     */
    protected function useLikeSearch($query, $column, $changes = null, $ignoreCase = true)
    {
        if (request()->has('search') && !is_null($search = $this->normalizeSearch(request()->get('search')))) {
            if ($changes != null && is_numeric($search) && is_int($changes)) {
                $search = (int)$search - $changes;
            }
            if ($ignoreCase) {
                $query->where($column, 'ilike', '%' . $search . '%');
            } else {
                $query->where($column, 'like', '%' . $search . '%');
            }
        }
        return $query;
    }

    /**
     * Add where like raw cause in given $query on $column from request search key
     * if $changes is numeric then sub $change from request search key
     *
     * @param Builder $query
     * @param string $column
     * @param null|integer $changes
     * @param bool $ignoreCase
     * @return Builder
     */
    protected function useLikeSearchRaw($query, $column, $changes = null, $ignoreCase = true)
    {
        if (request()->has('search') && !is_null($search = $this->normalizeSearch(request()->get('search')))) {
            if ($changes != null && is_numeric($search) && is_int($changes)) {
                $search = (int)$search - $changes;
            }
            if ($ignoreCase) {
                $query->whereRaw("$column ilike '%$search%'");
            } else {
                $query->whereRaw("$column like '%$search%'");
            }
        }
        return $query;
    }

    /**
     * Add where cause in given $query on $column from request search key
     *
     * @param Builder $query
     * @param string $column
     * @param null $changes
     * @return Builder
     */
    protected function useSearch($query, $column, $changes = null)
    {
        if (request()->has('search') && !is_null($search = $this->normalizeSearch(request()->get('search')))) {
            if ($changes != null && is_numeric($search) && is_int($changes)) {
                $search = (int)$search - $changes;
            }
            $query->where($column, '=', $search);
        }
        return $query;
    }

    /**
     * Add where raw cause in given $query on $column from request search key
     *
     * @param Builder $query
     * @param string $column
     * @param null $changes
     * @param bool $ignoreCase
     * @return Builder
     */
    protected function useSearchRaw($query, $column, $changes = null, $ignoreCase = true)
    {
        if (request()->has('search') && !is_null($search = $this->normalizeSearch(request()->get('search')))) {
            if ($changes != null && is_numeric($search) && is_int($changes)) {
                $search = (int)$search - $changes;
            }
            if ($ignoreCase) {
                $query->whereRaw("LOWER(\"$column\") = LOWER($search)");
            } else {
                $query->whereRaw("\"$column\" = $search");
            }
        }
        return $query;
    }

    protected function normalizeSearch($search)
    {
        if (!is_null($search) && is_string($search)) {
            return $search;
        }
        return null;
    }
}