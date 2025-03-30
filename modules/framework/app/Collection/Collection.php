<?php

namespace Atsmacode\Framework\Collection;

use Atsmacode\Framework\Dbal\Model;

trait Collection
{
    public function collect(): Model
    {
        foreach ($this->content as $key => $value) {
            $this->content[$key] = is_a($value, self::class) ? $value : self::find($value);
        }

        return $this;
    }

    /**
     * Used when $this->content is an array of the DB properties:
     * array(10) {
     *   [0] => array(4) {      
     *     'id' =>       
     *     int(11)       
     *     'amount' =>   
     *     int(1000)     
     *     'player_id' =>
     *     int(1)        
     *     'table_id' => 
     *     int(1)
     *     ...   
     *   }
     *   ...
     * }
     * 
     * And when desired result is a single self instance.          
     */
    public function search(string $column, mixed $value): ?Model
    {
        $key = array_search($value,
            array_column($this->content, $column)
        );

        if ($key !== false && array_key_exists($key, $this->content)) {
            return self::find($this->content[$key]);
        }

        return null;
    }

    /**
     * Used when $this->content is an array of Models:
     * 
     * array(1) {
     *   [14] =>
     *   class App\Models\Stack#69 (13) {
     *       public $servername =>
     *       string(9) "localhost"
     *       public $username =>
     *       string(4) "root"
     * ...
     * 
     * And when desired result is an array of selfs.
     * 
     * @return array<Model>
     */
    public function searchMultiple(string $column, mixed $value): ?array
    {

        $keys = array_keys(
            array_column($this->content, $column),
            $value
        );

        if (count($keys) > 0) {
            $this->content = array_filter($this->content, function($key) use($keys){
                return in_array($key, $keys);
            }, ARRAY_FILTER_USE_KEY);

            return $this->content;
        }

        return null;
    }

    public function slice(int $start, int $finish): ?Model
    {

        $items = array_slice($this->content, $start, $finish);

        if (count($items) === 1) {
            return self::find(array_shift($items));
        }

        return null;
    }

    public function filter(string $column, mixed $value): Model
    {
        $this->content = array_filter($this->content, function($key) use ($column, $value) {
            return $this->content[$key][$column] !== $value;
        }, ARRAY_FILTER_USE_KEY);

        return $this;
    }

    public function latest(): int
    {
        $dates = array_column($this->content, 'updated_at', 'id');

        uasort($dates, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        });

        return array_key_first(array_slice($dates, 0, 1, true));
    }

    public function first(): Model
    {
        return $this->content[0];
    }
}
