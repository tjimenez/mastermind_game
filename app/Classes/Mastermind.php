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
    private array $guessedColorCode;
    public array $secretColorCode;

    public function __construct()
    {
        $this->guessedColorCode = [];
        $this->colorList = $this->getColorsArray();
        $this->secretColorCode = $this->getRandomColorIndexesValues(4);
    }

    public function getColorsDataSet() : array
    {
        return $this->colorList;
    }

    public function setSecretColorPattern(array $pattern)
    {
        $this->secretColorCode = $pattern;
    }

    public function getSecretColorPattern()  : array
    {
        return $this->secretColorCode;
    }

    public function setGuessedColorCode(array $guessedColorCode)
    {
        $this->guessedColorCode = $guessedColorCode;
    }

    public function getGuessedColorCode() : array
    {
        return $this->guessedColorCode;
    }

    public function getHints() : array
    {
        $validator = Validator::make(
            [
                'guessed_code' => $this->guessedColorCode,
                'secret_code' => $this->secretColorCode
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
        foreach($this->guessedColorCode as $k => $v) {
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
