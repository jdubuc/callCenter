<?php 
namespace App\Http\Controllers\FileUpload;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest as FileUploadRequest;
use Auth;

class FileUploadController extends Controller
{

	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * @return \Illuminate\View\View
	 *
	 */
	public function index()
	{
		$files = Auth::user()->files()->get()->sortByDesc('created_at');

		if ($files->isEmpty()) {
			return redirect('file-upload/create');
		}

		return view( 'file-upload.index' )->withFiles($files);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view( 'file-upload.create' );
	}

	/**
     *
	 * @param \App\Http\Requests\FileUploadRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( FileUploadRequest $request )
	{
		// TODO add validation to ensure that files on disk aren't overwritten

		$uploadedFile	= $request->file('file');

		$fileHash		= hash_hmac_file('sha1', sys_get_temp_dir() . DIRECTORY_SEPARATOR . $uploadedFile->getFilename(), getenv('APP_KEY'));


		$filename		= $uploadedFile->getClientOriginalName();
		$user			= Auth::user();

		$uploadedFile->move(dirname( public_path() ) . DIRECTORY_SEPARATOR . 'uploads', $fileHash);

		// Find record or create a new one
		$record			= File::firstOrNew(['hash' => $fileHash]);

		if ($record->exists)
		{
			// If uploaded by is != from user id, insert into pivot table
			if ($record->getAttribute('uploaded_by') != $user->id)
			{
				$record->users()->save($user);
			}

			return redirect('file-upload');
			//return Redirect::back()->withErrors(['File exists.']);
		}

        $record->name			= $filename;
        $record->mime			= $uploadedFile->getClientMimeType();
		$record->hash			= $fileHash;
		$record->uploaded_by	= $user->id;

        // Save Record
		$record->save();

		// Save to pivot table
		$record->users()->save($user);

		return redirect('file-upload');
	}
}