<?php

namespace App\Http\Controllers;

use App\Contract;
use App\fileDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDocController extends Controller
{

    private $storagePath;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        // contract id query
        $contract = Contract::where('id', $id)->firstOrFail();

        // get the file
        $file = $request->file('file');

        $data = [];
        // check file name
        $filename = $file->getClientOriginalName();
        // file data
        $data['size'] = $file->getSize();
        $data['filename'] = $filename;
        $data['mime'] = $file->getMimeType();


        // validate
//        $this->validateData($data, $this->rulesPdfCreate);


        $fileEntity = new fileDoc($data);


        // get the file from db
        $fileExists = FileDoc::where('contract_id', $id)->first();


        // check if file entry exists
        if ($fileExists) {
            // delete the existing file entity from db
            $contract->file()->delete();
            // delete file/folder from storage
            Storage::disk('contracts')->deleteDirectory($id);
        }

        // save the files data in the database on the files table
        $contract->file()->save($fileEntity);

        // store the file on the disk
        $file->storeAs($id, $filename, 'contracts');

        return response()->json(['id' => $fileEntity->id], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('no contract ID for this file', 400);
        }

        $file = Contract::findOrFail($id)->file;

        return response()->json($file, 200);
    }


    /**
     * download the file
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id) {

        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('id of contract is not provided', 400);
        }

        $fileQuery = Contract::findOrFail($id)->file;

        $filename = $fileQuery->filename;
        $fileType = $fileQuery->mime;

        // file path
        $filePath = storage_path("\app\contracts\\" . $id .'\\'.  $filename);

        // download file
        $headers = array('Content-Type' => $fileType);

        return response()->file($filePath, $headers);
    }
}
