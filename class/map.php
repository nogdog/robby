<?php

class Map
{
	/**
	 * @var array Contents of each map coordinate
	 */
	private $cells = array();

	/**
	 * @var array Current x/y position
	 */
	private $curPosition = array(1,1);

	/**
	 * Constructor
	 * Populates the map with cans (and walls)
	 * @return void
	 * @param float $density density of cans, 0 to 1
	 */
	public function __construct($density = 0.3)
	{
		if($density < 0 or $density > 1) {
			throw new Exception("Density must be between 0 and 1");
		}
		$density = $density * 100;
		for($x = 0; $x < 12; $x++) {
			for($y = 0; $y < 12; $y++) {
				if($y === 0 or $y === 11 or $x === 0 or $x === 11) {
					$this->cells[$x][$y] = 3;
				}
				elseif(rand(0, 100) < $density) {
					$this->cells[$x][$y] = 2;
				}
				else {
					$this->cells[$x][$y] = 1;
				}
			}
		}
	}

	public function situation($x, $y)
	{
		return $this->cells[$x][$y-1]
		     . $this->cells[$x+1][$y]
			 . $this->cells[$x][$y+1]
			 . $this->cells[$x-1][$y]
			 . $this->cells[$x][$y];
	}

	public function pickupCan($x, $y)
	{
		if( ! isset($this->cells[$x][$y])) {
			throw new Exception("Invalid map coordinate $x $y");
		}
		if($this->cells[$x][$y] === 2) {
			$this->cells[$x][$y] = 1;
			return true;
		}
		if($this->cells[$x][$y] === 3) {
			throw new Exception("Hey, that's a wall!");
		}
		return false; // no can there
	}

	/**
	 * Get the map array
	 * @return array
	 */
	public function getCells()
	{
		return $this->cells;
	}

	/**
	 * Get a text representation of the map
	 * @return string
	 * @param int $rx Robot's x coordinate 1-10
	 * @param int $ry Robot's y coordinate 1-10
	 */
	public function getTextMap($rx, $ry)
	{
		if($rx < 1 or $rx > 10 or $ry < 1 or $ry > 10) {
			throw new Exception("Invalid robot coordinates $rx / $ry");
		}
		$out = '';
		for($y = 0; $y < 12; $y++) {
			for($x = 0; $x < 12; $x++) {
				switch($this->cells[$x][$y]) {
					case 3:
						$out .=  chr(178);
						if(($y === 0 or $y === 11) and $x !== 11) {
							$out .= chr(178);
						}
						else {
							$out .= ' ';
						}
						break;
					case 2:
						if($x === $rx and $y === $ry) {
							$out .= '@ ';
						}
						else {
							$out .= chr(249).' ';
						}
						break;
					case 1:
						if($x === $rx and $y === $ry) {
							$out .= 'O ';
						}
						else {
							$out .='  ';
						}
						break;
					default:
						throw new Exception('WTF?');
				}
			}
			$out .=PHP_EOL;
		}
		return $out;
	}
}

