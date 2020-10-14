<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;

class MastermindTerminalGame extends Command
{
    public array $colorsDataset = [
        'black',
        'white',
        'orange',
        'gold',
        'yellow',
        'purple',
        'green',
        'red'
    ];
    public array $codeMakerSecretPattern;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play:mastermind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play mastermind through terminal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // initial message
        $this->codeMakerSecretPattern ?? $this->getInteractiveMessage();

        // CodeMaker create code pattern, duplicates are allowed.
        $this->codeMakerSecretPattern = $this->codeMakerSecretPattern  ?? $this->getRandomValuesFromArray($this->colorsDataset, 4);

        // CodeBreaker tries to guess the pattern
        $attempt = $this->getCodeBreakerSecretAttemptResponseValidated();

        // 3 - codemaker provides feedback placing ±4 pegs on the row related to the guesses.
        // black peg when code from guess are correct in both, color and position
        // white peg when code from guess are correct in color but wrong in position
        // the order for small pegs are not important
        $feedback = $this->getCodeMakerFeedbackForAttempt($attempt);

        // result: build a function that takes a list of 4 colors and returns a list of 0‐4 black/white results.
        dump( $feedback);

        if (count(array_unique($feedback)) === 1 && end($feedback) === 'black') {
            $this->info('You win!');
            dump($this->codeMakerSecretPattern);

            $continue = $this->choice('Want to play again?', ['n', 'y']);
            if($continue == 'y') {
                $this->codeMakerSecretPattern = [];
               $this->handle();
            }

            $this->info('Bye');

        } else {
            $this->handle();
        }

        // ideas
        // difficulty level levels (easy medium difficult)
        // change mode ( codebreaker || codemaker )
        // add more color options 4!=8
        // add interactive guide for guest user.
    }

    /**
     * Console messages
     * @param int|null $status
     */
    public function getInteractiveMessage(int $status = null)
    {
        switch ($status) {
            default:
                $this->info('### Welcome to mastermind terminal game ###');
                        sleep(1);
                $this->info('CodeMaker is creating a secret pattern');
                        sleep(2);
        }
    }

    /**
     * Return list of values allowing duplicates
     * array_rand won't allows to return duplicates so a custom function implementing array_rand was created
     * @param array $array
     * @param int $nodes
     * @return array
     */
    public function getRandomValuesFromArray(array $array, int $nodes)
    {
        $values = [];
        for ($row = 0; $row < $nodes; $row++) {
            $values[$row] = array_rand($array, 1);
        }
        return $values;
    }

    /**
     * Return codeBreaker attempt
     * @return array|mixed
     */
    public function getCodeBreakerSecretAttemptResponseValidated()
    {
        $response = $this->choice(
            'What is the secret? <fg=red>(write colors or related numbers separated by comma)',
            $this->colorsDataset,
            null,
            $maxAttempts = 3,
            $allowMultipleSelections = true
        );
        return $this->validateCodeBreakerSecretAttempt($response);
    }

    /**
     * Validate CodeBreaker attempt
     * @param array $response
     * @return array|mixed
     */
    public function validateCodeBreakerSecretAttempt(array $response)
    {
        $result = [];

        //response should be same length of code maker
        if (count($response) != count($this->codeMakerSecretPattern)) {
            $this->error('You should select ' . count($this->codeMakerSecretPattern) . ' colors');
            return $this->getCodeBreakerSecretAttemptResponseValidated();
        }

        //build array of color indexes
        foreach ($response as $value) {
            $result[] = array_search($value, $this->colorsDataset);
        }

        return $result;

    }

    /**
     * Evaluate CodeBreakerAttempt and return feedback
     * @param array $attempt
     * @return array
     */
    public function getCodeMakerFeedbackForAttempt(array $attempt)
    {
        $feedback = [];
        $codeMakerSecret = $this->codeMakerSecretPattern;
        foreach($attempt as $k => $v) {
            if (isset($codeMakerSecret[$k]) && $codeMakerSecret[$k] == $v) {
                $feedback[] = 'black';
                unset($codeMakerSecret[$k]);
            } elseif (in_array($v, $codeMakerSecret, true)) {
                $i = array_search($v, $codeMakerSecret);
                unset($codeMakerSecret[$i]);
                $feedback[] = 'white';
            } else {
                $feedback[] = '';
            }
         }

        return $feedback;

    }

}
