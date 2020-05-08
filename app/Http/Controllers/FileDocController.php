<?php

namespace App\Http\Controllers;

use App\Contract;
use App\fileDoc;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileDocController extends Controller
{
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
        $data['name'] = $file->getMimeType();


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
     * @param  \App\fileDoc  $fileDoc
     * @return \Illuminate\Http\Response
     */
    public function show(fileDoc $fileDoc)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\fileDoc  $fileDoc
     * @return \Illuminate\Http\Response
     */
    public function edit(fileDoc $fileDoc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\fileDoc  $fileDoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, fileDoc $fileDoc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\fileDoc  $fileDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy(fileDoc $fileDoc)
    {
        //
    }
}
