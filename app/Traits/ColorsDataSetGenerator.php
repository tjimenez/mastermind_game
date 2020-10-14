<?php
namespace App\Traits;

trait ColorsDataSetGenerator {

    public function getColorsArray()
    {
        return [
            'black',
            'white',
            'blue',
            'orange',
            'gold',
            'yellow',
            'purple',
            'green',
            'red'
        ];
    }

    public function getRandomColorIndexesValues(int $nodes){
        $values = [];
        for ($row = 0; $row < $nodes; $row++) {
            $values[$row] = array_rand(array_flip($this->getColorsArray()), 1);
        }
        return $values;
    }
}
