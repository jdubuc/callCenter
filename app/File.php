<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'files';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 'mime', 'uploaded_by', 'path', 'hash' ];

	/**
	 * A file belongs to many users
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function users()
	{
		return $this->belongsToMany('App\User');
	}

	/**
	 * A file has many reports.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function reports()
	{
		return $this->hasMany('App\Report');
	}
	public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
}
