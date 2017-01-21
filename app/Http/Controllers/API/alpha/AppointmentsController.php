<?php

namespace App\Http\Controllers\API\alpha;

use App\Http\Controllers\API\alpha\Transformers\AppointmentTransformer;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentsController extends BaseAPIController
{
    protected $transformer;
    
    public function __construct(AppointmentTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    
    /**
     * @param Appointment $appointment
     * @return array
     */
    public function show(Appointment $appointment)
    {
        $transformedAppointment = $this->transformer->transform($appointment);
        return $this->respond($transformedAppointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    
    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Appointment $appointment)
    {
        $transformedAppointment = $this->transformer->transform($appointment);
        $appointment->delete(); // soft delete
        return $this->respond($transformedAppointment, ['deleted' => true]);
    }
}
