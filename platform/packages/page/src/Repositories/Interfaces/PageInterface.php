<?php

namespace Srapid\Page\Repositories\Interfaces;

use Srapid\Support\Repositories\Interfaces\RepositoryInterface;

interface PageInterface extends RepositoryInterface
{

    /**
     * @return mixed
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     */
    public function getFeaturedPages($limit);

    /**
     * @param array $array
     * @param array $select
     * @return mixed
     */
    public function whereIn($array, $select = []);

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     */
    public function getSearch($query, $limit = 10);

    /**
     * @param bool $active
     * @return mixed
     */
    public function getAllPages($active = true);
}
