<?php

namespace App\Helpers\Filters;

use App\Helpers\Filters\QueryFilter;


/**
 * Класс для фильтрации фотографий, расширяющий абстрактный класс.
 * Все методы относящиеся к фильтрации фотографий писать только тут!
 *
 * @var value
 */

class PhotoFilter extends QueryFilter {

    /**
     * Поиск по названию
     *
     * @var string
     * @return void
     */
    public function search($value)
    {
        $this->builder->where('name', 'LIKE', "%$value%");
    }

    /**
     * Поиск по slug подкаталога
     *
     * @var slug
     * @return void
     */
    public function subcatalog($value)
    {
        $this->builder->whereHas('photoCategory', function($q) use($value) {
            !$value ?: $q->where('slug', '=', $value);
        });
    }

    /**
     * Фильтруем по месяцу
     *
     * @var string
     * @return void
     */
    public function month($value)
    {
        $this->builder->whereMonth('created_at', '=', $value);
    }

    /**
     * Фильтруем по году
     *
     * @var string
     * @return void
     */
    public function year($value)
    {
        $this->builder->whereYear('created_at', '=', $value);
    }

    /**
     * Фильтруем по дате добавления
     *
     * @var string
     * @return void
     */
    public function sort($value)
    {
        $this->builder->orderBy('created_at', $value === 'asc' ? 'asc' : 'desc');
    }

    /**
     * Фильтруем по цвету
     *
     * @var string
     * @return void
     */
    public function color($value)
    {
        $this->builder->where('color', 'LIKE', "%$value%");
    }

}
