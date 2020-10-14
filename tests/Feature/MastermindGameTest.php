<?php

namespace Tests\Feature;

use App\Console\Commands\MastermindTerminalGame;
use Tests\TestCase;

class MastermindGameTest extends TestCase
{
    public function test_class_exists()
    {
        $this->assertTrue(class_exists(MastermindTerminalGame::class));
    }

    public function test_codeMaker_has_selected_a_color_pattern()
    {
        $this->artisan('play:mastermind')
            ->expectsOutput('### Welcome to mastermind terminal game ###')
            ->expectsOutput('Codemaker is creating a secret')
            ->expectsOutput('Ready to decode the secret? CodeMaker has 4 key codes. Good luck...')
            ->expectsQuestion('What is the secret? (write colors or related numbers separated by comma)', '0,1,2,3')
            ->assertExitCode(0);
    }

}
