<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fields';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name' ];

	/**
	 * A field belongs to a field|data type
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function fieldType()
	{
		return $this->belongsTo('App\FieldType');
	}

	/**
	 * A field belongs to many reports
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function reports()
	{
		return $this->belongsToMany('App\Report');
	}

	/**
	 * A field belongs to many filters
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function filters()
	{
		return $this->belongsToMany('App\Filters');
	}

}
