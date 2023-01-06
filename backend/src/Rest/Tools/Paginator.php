<?php

namespace App\Rest\Tools;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * Обертка над стоковым пагинатором с дополнительным простым API для удобства.
 *
 * @template T
 * @extends DoctrinePaginator<T>
 */
class Paginator extends DoctrinePaginator
{
    /**
     * Возвращает максимальное количество доступных страниц.
     */
    public function maxPage(): ?int
    {
        $query = $this->getQuery();

        $limit = $query->getMaxResults();

        if (0 === $limit || null === $limit) {
            return null;
        }

        return (int) ceil($this->count() / $limit);
    }

    /**
     * Возвращает номер страницы.
     */
    public function page(): ?int
    {
        $query = $this->getQuery();

        $limit = $query->getMaxResults();
        $offset = $query->getFirstResult();

        if (null === $limit || null === $offset) {
            return null;
        }

        if (0 === $limit || 0 === $offset) {
            return 1;
        }

        return (int) floor($offset / $limit) + 1;
    }

    /**
     * Возвращает лимит
     */
    public function limit(): ?int
    {
        $query = $this->getQuery();

        if (null === $query->getMaxResults()) {
            return null;
        }

        return $query->getMaxResults();
    }
}
