<?php

/**
 * Create a test situation and run robots through it.
 */
class Test
{
	private $scoring = array(
		'wall'   => -3,
		'noCan'  => -2,
		'pickup' => 1
	);

	private $map;

	private $moves;
	
	public function __construct(Map $map, $moves = 100)
	{
		$this->map = $map;
		$this->moves = $moves;
	}

	public function runTest(Robot $robot, $log = false)
	{
		$score = 0;
		$map = $this->map->getCells();
		$position = array('x' => 1, 'y' => 1);
		$genome = $robot->getGenome();
		for($i = 0; $i < $this->moves; $i++)
		{
			$situation = $this->situation($map, $position['x'], $position['y']);
			if($genome[$situation] === 6) // pickup
			{
				if($map[$position['x']][$position['y']] === 2)
				{
					$score += $this->scoring['pickup'];
					$map[$position['x']][$position['y']] = 1;
					if($log) { error_log("Pickup! ($score)"); }
				}
				else
				{
					$score += $this->scoring['noCan'];
					if($log) { error_log("Nothing there ($score)"); }
				}
			}
			else
			{
				$direction = $genome[$situation];
				if($direction === 5)
				{
					$direction = mt_rand(1,4);
				}
				switch($direction)
				{
					case 1:
						$xd = 0;
						$yd = -1;
						break;
					case 2:
						$xd = 1;
						$yd = 0;
						break;
					case 3:
						$xd = 0;
						$yd = 1;
						break;
					case 4:
						$xd = -1;
						$yd = 0;
						break;
					default:
						throw new Exception("Invalid direction $direction");
				}
				if($map[$position['x'] + $xd][$position['y'] + $yd] === 3)
				{
					$score += $this->scoring['wall'];
					if($log) { error_log("BOING! ($score)"); }
				}
				else
				{
					$position['x'] += $xd;
					$position['y'] += $yd;
					if($log) { error_log("Moved $direction ($score)"); }
				}
			}
		}
		return $score;
	}

	private function situation(Array $map, $x, $y)
	{
		return $map[$x][$y-1]
		     . $map[$x+1][$y]
			 . $map[$x][$y+1]
			 . $map[$x-1][$y]
			 . $map[$x][$y];
	}

}