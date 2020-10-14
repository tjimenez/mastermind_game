<?php

namespace App\Classes;

use App\Traits\ColorsDataSetGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class Mastermind
{
    use ColorsDataSetGenerator;

    private array $colorList;
    private array $guessedColorPattern;
    private array $secretColorPattern;

    public function __construct()
    {
        $this->guessedColorPattern = [];
        $this->colorList = $this->getColorsArray();
        $this->secretColorPattern = $this->getRandomColorIndexesValues(4);
    }

    public function getColorsDataSet() : array
    {
        return $this->colorList;
    }

    public function setSecretColorPattern(array $pattern)
    {
        $this->secretColorPattern = $pattern;
    }

    public function getSecretColorPattern()  : array
    {
        return $this->secretColorPattern;
    }

    public function setGuessedColorPattern(array $guessedColorPattern)
    {
        $this->guessedColorPattern = $guessedColorPattern;
    }

    public function getGuessedColorPattern() : array
    {
        return $this->guessedColorPattern;
    }

    public function getHints()
    {
        $validator = Validator::make(
            [
                'guessed_code' => $this->guessedColorPattern,
                'secret_code' => $this->secretColorPattern
            ],
            [
                'guessed_code' => [
                    'required',
                    'array',
                    Rule::in($this->colorList),
                    'min:4',
                    'max:4',
                ],
                'secret_code' => [
                    'required',
                    'array',
                    Rule::in($this->colorList),
                    'min:4',
                    'max:4',
                ],
            ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        }

        $feedback = [];
        $codeMakerSecretCode = $this->getSecretColorPattern();

        //compare guessed code with secret code
        foreach($this->guessedColorPattern as $k => $v) {
            if (isset($codeMakerSecretCode[$k]) && $codeMakerSecretCode[$k] == $v) {
                $feedback[] = 'black';
                unset($codeMakerSecretCode[$k]);
            }
            elseif (in_array($v, $codeMakerSecretCode, true)) {
                $i = array_search($v, $codeMakerSecretCode);
                unset($codeMakerSecretCode[$i]);
                $feedback[] = 'white';
            } else {
                $feedback[] = '';
            }
        }

        return $feedback;

    }

}
