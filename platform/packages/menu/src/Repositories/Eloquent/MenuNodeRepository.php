<?php

namespace Srapid\Menu\Repositories\Eloquent;

use Srapid\Menu\Repositories\Interfaces\MenuNodeInterface;
use Srapid\Support\Repositories\Eloquent\RepositoriesAbstract;

class MenuNodeRepository extends RepositoriesAbstract implements MenuNodeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByMenuId($menuId, $parentId, $select = ['*'], array $with = ['child'])
    {
        $data = $this->model
            ->with($with)
            ->where([
                'menu_id'   => $menuId,
                'parent_id' => $parentId,
            ]);

        if (!empty($select)) {
            $data = $data->select($select);
        }

        $data = $data->orderBy('position', 'asc')
            ->get();

        $this->resetModel();

        return $data;
    }
}
