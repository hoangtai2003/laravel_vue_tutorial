<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function appointments(){
        $totalAppointmentCount = Appointment::query()
            ->when(request('status') === 'scheduled', function ($query){
           $query->where('status', AppointmentStatus::SCHEDULED);
        })
            ->when(request('status') === 'confirmed', function ($query){
            $query->where('status', AppointmentStatus::CONFIRMED);
        })
            ->when(request('status') === 'cancelled', function ($query){
            $query->where('status', AppointmentStatus::CANCELLED);
        })
            ->count();
        return response()->json([
           'totalAppointmentCount' => $totalAppointmentCount
        ]);
    }
}
