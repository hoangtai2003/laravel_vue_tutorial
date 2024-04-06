<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function list(){
        return Appointment::query()
            ->with('client:id,first_name,last_name')
            ->when(request('status'), function ($query){
                return $query->where('status', AppointmentStatus::from(request('status')));
            })->latest()->get()
            // map() được gọi trên Collection để chuyển đổi từng phần tử
            // trong Collection thành một giá trị mới dựa trên hàm callback được cung cấp
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'start_time' => $appointment->start_time->format('Y-m-d h:i A'),
                    'end_time' => $appointment->end_time->format('Y-m-d h:i A'),
                    'status' => [
                        'name' => $appointment->status->name,
                        'color' => $appointment->status->color()
                    ],
                    'client' => $appointment->client
                ];
            });
    }
    public function getStatusWithCount() {
        $cases = AppointmentStatus::cases();
        return collect($cases)->map(function ($status){
           return [
             'name' => $status->name,
             'value' => $status->value,
             'count' => Appointment::where('status', $status->value)->count(),
             'color' => AppointmentStatus::from($status->value)->color()
           ];
        });
    }
}