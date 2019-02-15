<?php namespace App\Http\Controllers;

use App\File;
use Illuminate\Filesystem\Filesystem;
use App\Http\Requests;

class FileViewController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{	
		$model = File::findOrFail($id);
		$path=dirname( public_path() ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $model->hash;
		$resultado=\Excel::selectSheets('Hoja1')->load($path, function($archivo)
		{
		  $results = $archivo->all();
			foreach ($results as $rows) {
				 $columna[]=$rows;
        		
             
              
                      
                    }
 		/*return view( 'file-view.show')->withResults($results);*/
		})->get();
			return view( 'file-view.show')->withResultado($resultado);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
