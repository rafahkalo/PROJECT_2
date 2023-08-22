<?php

namespace App\Http\Controllers;

use App\Models\Complaint_External;
use Illuminate\Http\Request;

class ComplaintExternalController extends Controller
{
    


    public function showcomplaint_External()
    {
      $complaint_External=Complaint_External::get();
     return response()->json($complaint_External, 200);
    
    }









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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint_External $complaint_External)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint_External $complaint_External)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint_External $complaint_External)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint_External $complaint_External)
    {
        //
    }
}
