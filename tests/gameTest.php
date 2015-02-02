<?php
require "game.php";

class GameTest extends PHPUnit_Framework_TestCase  
{
	public function testAdd() 
	{
		$this->assertEquals(15, 12);
  }
  public function checkType() 
  {
    $game = new Game();
    echo $game->type;
    $this->assertEquals($game->type, "Game");
  }
}

?>
