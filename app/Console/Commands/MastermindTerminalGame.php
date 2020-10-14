<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MastermindTerminalGame extends Command
{
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
    protected $description = 'Play mastermind throught terminal';

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
        // resources
            // big colorful pegs for computer || guest
            // small black and white pegs for computer

        // workflow
            // 1 -  computer/codemaker choose 4 code pegs
                // duplicates are allowed (add levels of dificulties)
                // this selection is hidden from code breaker until the game finish or he decodes the pattern

            // 2 - guest/codebreaker tries to guess the pattern
            // 3 - codemaker provides feedback placing ±4 pegs on the row related to the guesses.
                // black peg when code from guess are correct in both, color and position
                // white peg when code from guess are correct in color but wrong in position
                // the order for small pegs are not important

            // result: build a function that takes a list of 4 colors and returns a list of 0‐4 black/white results.

        // ideas
            // difficulty level levels (easy medium difficult)
            // change mode ( codebreaker || codemaker )
            // add more color options 4!=8
    }
}
