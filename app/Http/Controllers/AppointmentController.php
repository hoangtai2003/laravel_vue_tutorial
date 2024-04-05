<?php

namespace App\Http\Controllers;

use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function list(){
        return Appointment::query()
            ->with('client:id,first_name,last_name')
            ->latest()
            ->get()
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
}
