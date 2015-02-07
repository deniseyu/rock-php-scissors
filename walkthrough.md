# Test-Driving Rock, Paper, Scissors with PHPUnit

## Overview

Test-driven development is a method of writing as little code as possible in order to achieve functionality. In order to ensure that tests are purposeful (i.e. not false positives), it is standard practice at Makers Academy to write a failing test, then write just enough code to make the test pass, followed by refactoring. The aim of this tutorial is to provide a glimpse into the "Makers Way" of software development using PHP and PHPUnit. This walkthrough assumes basic familiarity with PHP syntax.

There are many ways to write Rock, Paper, Scissors. In this version, we will err on the side of object orientation for each "choice", and the win/loss mechanism will be a method inside the Game class.

## Installing Dependencies
If you haven't done so already, install Composer and PHPUnit. Composer is a package manager for PHP applications, similar to Bundler and NPM.

```php
brew install composer
composer global require "phpunit/phpunit=4.5.*"
```

Create a composer.json file in your root directory, and fill it out like so:

```php
{
  "require-dev": {
    "phpunit/phpunit": "4.5.*"
  }
}
```
Running ```composer install``` from shell will load all of a program's dependencies in the same way as ```npm install```. It will also generate a vendor folder and composer.lock file.

At this point, if you are going to push to Github, create a .gitignore file and place composer.lock and the vendor folder into it.

## Project Setup

Set up two directories, src/ and tests/. Following convention, the model script goes into the src folder and the corresponding test script goes into tests. The file tree at this stage:

```
|-src/
|---game.php
|-tests/
|---gameTest.php
|-composer.json
|-composer.lock
|-.gitignore
```

In PHPUnit, all test scripts are actually classes that need to inherit a host of methods from the PHPUnit Framework object. A bit of convention to flag: where RSpec looks for lines that begin with "expect", PHPUnit runs tests based on the names of public functions. It will only recognize test cases that begin with a lowercase "test" followed by a camelcased descriptor, i.e. ```testDoSomething```.

PHPUnit syntax generally follows this pattern:

```$this->someAssertion($actual, $expected);```

A full appendix of assertions is here: https://phpunit.de/manual/current/en/appendixes.assertions.html

## Building objects

First, let's write a failing test.

```php
// tests/gameTest.php

<?php
class GameTest extends PHPUnit_Framework_TestCase {
  public function testCheckType(){
    $rock = new Rock();
    $this->assertEquals($rock->type, "Rock");
  }
}
?>
```
Run the test in shell with the command ```phpunit tests```.

We should get an error message telling us that the Rock object does not exist. Let's fix that:

```php
// src/game.php
<?php class Rock {} ?>
```
Run the tests again. It should still be failing -- because we haven't told PHPUnit where to look for  source files! At the top of gameTest.php, insert the following line:
```php
require './src/game.php'
```
On the next run, the error message should finally change. PHPUnit can find the Rock class, but it doesn't know what the "type" method is. Let's write just enough code to make this test pass.
```php
// src/game.php
<?php
class Rock {
  public $type = "Rock";
}
?>
```
Now the test should pass. Repeat for the Paper and Scissors classes!

## Building the Game class

Once all of the objects are created, it's time to build some game logic. Let's begin with another failing test:

```php
// tests/gameTest.php

<?php
class GameTest extends PHPUnit_Framework_TestCase {
  public function testReturnsDraw(){
    $game = new Game();
    $rock = new Rock();
    $this->assertEquals($game->evaluates($rock, $rock), "Draw");
  }
}
?>
```
This will fail on execution, because the Game class does not exist. Update the source file:
```php
// src/game.php

<?php
class Game {}
?>
```
Run the tests again, and it should tell us that no method called "evaluates" exists yet. Let's fix that, but being careful to only write as much code as we need at the moment.
```php
<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    return "Draw";
  }
}
?>
```
The test should now pass, but this isn't a complete Rock, Paper, Scissors game. Let's write some more tests to guide our design of the game logic:
```php
// tests/gameTest.php

<?php
public function testRockBeatsScissors(){
  $game = new Game();
  $rock = new Rock();
  $scissors = new Scissors();
  $this->assertEquals($game->evaluates($rock, $scissors), $rock);
}
?>
```
We now need to add some flow control to the model. Amend the model so that draws are returned when choiceOne and choiceTwo have the same type; otherwise, let the method return the first choice passed in.

```php
// src/game.php

<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    if ($choiceOne->type == $choiceTwo->type) {
      return "Draw";
    }
    else {
      return $choiceOne;
    }
  }
}
?>
```
Next, let's write a failing test that shakes up the order of arguments passed into the evaluates method.
```php
// tests/gameTest.php

<?php
public function testScissorsBeatsPaper(){
  $game = new Game();
  $scissors = new Scissors();
  $paper = new Paper();
  $this->assertEquals($game->evaluates($paper, $scissors), $scissors);
}
?>
```
The test should now fail. We need to make the evaluates method more robust. There are a few ways to achieve this, and it depends on our determination of class responsibilities. In this implementation, the Rock, Paper, and Scissors objects will hold the "information" about which classes they trump, rather than the game class. Reopen the Rock class and add another attribute:
```php
class Rock {
  public $type = "Rock";
  public $beats = "Scissors";
}
```
Then rewrite the Game class's evaluate method to account for the objects' newly-added attribute in the game logic:
```php
<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    if ($choiceOne->type == $choiceTwo->type) {
      return "Draw";
    }
    else if ($choiceOne->beats == $choiceTwo->type) {
      return $choiceOne;
    }
    else {
      return $choiceTwo;
    }
  }
}
?>
```
The logic of the evaluates method now accounts for the order of parameters. Write the final test, for the case when PaperBeatsRock, and watch it pass! (I know -- this part isn't strictly TDD.)

## Refactoring

Notice anything about the test cases? There's a lot of repetition -- the same objects are instantiated over and over again throughout the test suite. This is bad, because it violates the DRY principle.

In PHPUnit, unfortunately there doesn't really exist a clean way to instantiate objects in a beforeAll or beforeEach hook. Because your tests live within an extension of the Test Framework object, all test-related circumstances have to be "attached" to this particular object. These are called "fixtures". More documentation here: https://phpunit.de/manual/current/en/fixtures.html

The PHPUnit Test Framework object comes with two protected methods that simulate before and after hooks in RSpec or Jasmine. They are called setUp() and tearDown(), respectively. Any functionality wrapped in the setUp() and tearDown() will be executed before and after each test. We will basically be monkey-patching these methods to DRY up our tests.

First, declare every object you will be instantiating as a protected variable, then affix them to the Test Framework object in the setUp fixture at the top of your test file, like so:

```php
<?php
class GameTest extends PHPUnit_Framework_TestCase
{
  protected $game;
  protected $rock;
  protected $paper;
  protected $scissors;

  protected function setUp()
  {
    $this->game = new Game();
    $this->rock = new Rock();
    $this->paper = new Paper();
    $this->scissors = new Scissors();
  }
  // etc.
}
?>
```
To access the particular object created in the setUp fixture, the object references throughout your tests must change from $rock to $this->rock, $game to $this->game, and so on. Go ahead and delete every object instantiation within your tests now and run phpunit.

That's it! Can you think of any other functionality?

## Completed Code

```php
// src/game.php

<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    if ($choiceOne->type == $choiceTwo->type) {
      return "Draw";
    }
    else if ($choiceOne->beats == $choiceTwo->type) {
      return $choiceOne;
    }
    else {
      return $choiceTwo;
    }
  }
}
class Rock {
  public $type = "Rock";
  public $beats = "Scissors";
}
class Paper {
  public $type = "Paper";
  public $beats = "Rock";
}
class Scissors {
  public $type = "Scissors";
  public $beats = "Paper";
}
?>
```

```php
// tests/gameTest.php

<?php
require "./src/game.php";

class GameTest extends PHPUnit_Framework_TestCase
{
  protected $game;
  protected $rock;
  protected $paper;
  protected $scissors;

  protected function setUp()
  {
    $this->game = new Game();
    $this->rock = new Rock();
    $this->paper = new Paper();
    $this->scissors = new Scissors();
  }
  public function testCheckType()
  {
    $this->assertEquals($this->rock->type, "Rock");
    $this->assertEquals($this->paper->type, "Paper");
    $this->assertEquals($this->scissors->type, "Scissors");
  }
  public function testDraw()
  {
    $this->assertEquals($this->game->evaluates($this->paper, $this->paper), "Draw");
  }
  public function testRockBeatsScissors()
  {
    $this->assertEquals($this->game->evaluates($this->rock, $this->scissors), $this->rock);
  }
  public function testPaperBeatsRock()
  {
    $this->assertEquals($this->game->evaluates($this->rock, $this->paper), $this->paper);
  }
  public function testScissorsBeatsPaper()
  {
    $this->assertEquals($this->game->evaluates($this->scissors, $this->paper), $this->scissors);
  }
}

?>
```
