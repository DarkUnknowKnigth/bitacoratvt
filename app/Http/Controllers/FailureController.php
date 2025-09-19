<?php

namespace App\Http\Controllers;

use App\Models\Failure;
use App\Http\Requests\StoreFailureRequest;
use App\Http\Requests\UpdateFailureRequest;

class FailureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFailureRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Failure $failure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Failure $failure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFailureRequest $request, Failure $failure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Failure $failure)
    {
        //
    }
}
