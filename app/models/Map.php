<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Map extends \Eloquent {
	protected $fillable = [];

	use SoftDeletingTrait;

    public function users()
    {
        return $this->hasMany('User');
    }

    public function northMap()
    {
    	return $this->belongsTo('Map', 'north_map_id');
    }

    public function southMap()
    {
    	return $this->belongsTo('Map', 'south_map_id');
    }

    public function eastMap()
    {
    	return $this->belongsTo('Map', 'east_map_id');
    }

    public function westMap()
    {
    	return $this->belongsTo('Map', 'west_map_id');
    }

    public function items()
    {
        return $this->hasMany('Item');
    }

    public function actions()
    {
        return $this->hasMany('Action');
    }

    public function guards()
    {
        return $this->hasMany('Guard');
    }
}