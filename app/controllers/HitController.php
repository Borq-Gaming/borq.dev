<?php

class HitController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	//var bank
	public $sword;
	public $armor;
	public $hp;
	public $guardHp;
	public $guardIndex;

	public function postGuard() 
	{
		$game = Auth::user();
		if ($game->map->guards->toArray()) {
			$this->fetchStats();
			$this->fetchGuard();
			$return = $this->fight();
			$this->update();
		} else {
			$return = "There is no one to fight.";
		}

		// return result
		return Response::json($return);
	}

	// fetch all necessary stats and set up
	public function fetchStats()
	{
		$stats = Auth::user();
		
		// fetch sword and armor stats
		$sword = $stats->sword;
		$armor = $stats->armor;
		
		// fetch HP
		$this->hp = $stats->health;

		if ($sword) {
			$this->sword = 1.15;
		} else {
			$this->sword = 1;
		}

		if ($armor) {
			$this->armor = 1.15;
		} else {
			$this->armor = 1;
		}
	}

	public function fetchGuard()
	{
		$game = Auth::user();

		$guard = $game->map->guards()->where('health', '>', 0)->first();
		$this->guardHp = $guard->health;
	}

	public function fight()
	{
		//player rngs
		$hit = mt_rand(1, 100);
		$rng = mt_rand(1, 67);
		$rng = ($rng/100) + 0.67;

		//guard rngs
		$guard = mt_rand(1, 3);

		// player hit
		if ($hit > 30) {
			$damage = (((3 * $this->sword) * $this->armor) * $rng);
			$thing = $damage;
			$damage = round($damage);

			$return1 = "You hit the guard for " . $damage . " damage. ";
		} else {
			$damage = 0;

			$return1 = "You miss the guard. ";
		}

		//guard hit
		if ($guard == 1) {
			$guardDamage = 1;
		} else {
			$guardDamage = 2;
		}

		$return2 = "The guard hits you for " . $guardDamage . " damage. ";

		$this->guardHp = $this->guardHp - $damage;
		$this->hp = $this->hp - $guardDamage;

		return $return1 . $return2;
	}

	//
	public function update()
	{
		$update = Auth::user();
		$update->health = $this->hp;
		$update->save();

		$guard = $update->map->guards()->where('health', '>', 0)->first();
		$guard->health = $this->guardHp;
		$guard->save();

	}
}
