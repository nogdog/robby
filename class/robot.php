<?php

class Robot
{
	private $genome = array();
	private $random = false;
	private $parentGenome = array();

	public function __construct(Robot $parent1 = null, Robot $parent2 = null, $mutation=0.001)
	{
		if($mutation < 0 or $mutation > 1) {
			throw new Exception("mutation rate must be between 0 and 1, inclusive");
		}
		if( ! is_null($parent1) and ! is_null($parent2)) {
			$this->parentGenome[1] = $parent1->getGenome();
			$this->parentGenome[2] = $parent2->getGenome();
			$this->mutation = $mutation * 1000;
		}
		else {
			$this->random = true;
		}
		$this->generateGenome();
	}

	/**
	 * Generate a random genome for our robot
	 * @return void
	 */
	private function generateGenome()
	{
		for($a=1; $a<=3; $a++) {
			for($b=1; $b<=3; $b++) {
				for($c=1; $c<=3; $c++) {
					for($d=1; $d<=3; $d++) {
						for($e=1; $e<=3; $e++) {
							if($this->random or mt_rand(0,1000) < $this->mutation) {
								$this->genome[$a.$b.$c.$d.$e] = mt_rand(1,6);
								if( ! $this->random) {
									error_log("MUTATION!");
								}
							}
							else {
								$this->genome[$a.$b.$c.$d.$e] = $this->parentGenome[mt_rand(1,2)][$a.$b.$c.$d.$e];
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Get the genome for this robot
	 * @return array
	 */
	public function getGenome()
	{
		return $this->genome;
	}

	public function getParents()
	{
		return $this->parentGenome;
	}
}