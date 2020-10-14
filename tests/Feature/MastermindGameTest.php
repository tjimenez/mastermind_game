<?php

namespace Tests\Feature;

use App\Classes\Mastermind;
use App\Traits\ColorsDataSetGenerator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class MastermindGameTest extends TestCase
{
    public function test_trait_dependency_exists()
    {
        $this->assertTrue(trait_exists(ColorsDataSetGenerator::class));
    }

    public function test_mastermind_class_exists()
    {
        $this->assertTrue(class_exists(Mastermind::class));
    }

    public function test_mastermind_class_can_be_instantiated()
    {
        $object = new Mastermind();
        $this->assertInstanceOf(Mastermind::class, $object);
    }

    public function test_secret_code_is_defined_and_length_is_four()
    {
        $object = new Mastermind();
        $this->assertIsArray($object->getSecretColorPattern());
        $this->assertCount(4, $object->getSecretColorPattern());
    }

    public function test_color_data_set_is_defined()
    {
        $object = new Mastermind();
        $this->assertIsArray($object->getColorsDataSet());
        $this->assertGreaterThanOrEqual(4, $object->getColorsDataSet());
    }

    public function test_validation_error_when_secret_code_color_is_not_valid()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern([]);
        $this->assertInstanceOf(MessageBag::class, $object->getHints());
        $this->assertTrue(isset($object->getHints()->messages()['secret_code'][0]));
    }

    public function test_secret_code_color_cannot_be_custom_colors_out_of_dataset()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern(['black', 'green', 'sss', 'white']);
        $this->assertInstanceOf(MessageBag::class, $object->getHints());
        $this->assertTrue(isset($object->getHints()->messages()['secret_code'][0]));
    }

    public function test_secret_color_code_can_be_defined()
    {
        $object = new Mastermind();
        $payload = ['red','blue','gold', 'black'];
        $object->setSecretColorPattern($payload);
        $this->assertEquals($payload, $object->getSecretColorPattern());
    }

    public function test_validation_error_when_guessed_color_is_not_valid()
    {
        $object = new Mastermind();
        $object->setGuessedColorPattern([]);
        $this->assertInstanceOf(MessageBag::class, $object->getHints());
        $this->assertTrue(isset($object->getHints()->messages()['guessed_code'][0]));
    }

    public function test_guess_colors_cannot_be_custom_colors_out_of_dataset()
    {
        $object = new Mastermind();
        $object->setGuessedColorPattern(['black', 'green', 'brown', 'white']);
        $this->assertInstanceOf(MessageBag::class, $object->getHints());
        $this->assertTrue(isset($object->getHints()->messages()['guessed_code'][0]));
    }

    public function test_guessed_color_can_be_defined()
    {
        $object = new Mastermind();
        $object->setGuessedColorPattern(['red','blue','gold', 'black']);
        $this->assertEquals(['red','blue','gold', 'black'], $object->getGuessedColorPattern());
    }

    public function test_game_exact_feedback()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern(['black','white','orange', 'gold']);
        $object->setGuessedColorPattern(['black','white','orange', 'gold']);
        $this->assertEquals(['black', 'black', 'black', 'black'], $object->getHints());
    }

    public function test_game_has_coincidences_but_not_exact_feedback()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern(['black','white','orange', 'gold']);
        $object->setGuessedColorPattern(['white', 'black', 'gold', 'orange']);
        $this->assertEquals(['white', 'white', 'white', 'white'], $object->getHints());
    }

    public function test_game_has_not_coincidences_feedback()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern(['red','blue','green', 'purple']);
        $object->setGuessedColorPattern(['white', 'black', 'gold', 'orange']);
        $this->assertEquals(['', '', '', ''], $object->getHints());
    }

    public function test_game_has_coincidences_and_wrongs_feedback()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern(['red','blue','green', 'purple']);
        $object->setGuessedColorPattern(['black', 'red', 'gold', 'green']);
        $this->assertEquals(['', 'white', '', 'white'], $object->getHints());
    }

    public function test_game_has_mixed_feedback()
    {
        $object = new Mastermind();
        $object->setSecretColorPattern(['black','white','blue', 'orange']);
        $object->setGuessedColorPattern(['black', 'blue', 'gold', 'green']);
        $this->assertEquals(['black', 'white', '', ''], $object->getHints());
    }

}
