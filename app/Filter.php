<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'filters';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 'description', 'constraint' ];

	/**
	 * A filter belongs to many reports
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function reports()
	{
		return $this->belongsToMany('App\Report');
	}

	/**
	 * A filter belongs to many fields
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function fields()
	{
		return $this->belongsToMany('App\Field');
	}
	public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }

}