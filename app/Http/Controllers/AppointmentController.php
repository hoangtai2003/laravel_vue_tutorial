<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Http\Request;

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
        // Lấy danh sách các trạng thái của cuộc hẹn từ hằng số AppointmentStatus thông qua phương thức cases()
        $cases = AppointmentStatus::cases();
        // collect($cases) dùng để chuyển đổi các trạng thái thành một Collection để có thể sử dụng các phương thức của Collection
        // map(function ($status) { ... }): Duyệt qua mỗi phần tử trong Collection hàm callback này trả về một mảng thông tin về mỗi trạng thái.
        return collect($cases)->map(function ($status){
           return [
             'name' => $status->name,
             'value' => $status->value,
             'count' => Appointment::where('status', $status->value)->count(),
             'color' => AppointmentStatus::from($status->value)->color()
           ];
        });
    }
    public function create (Request $request){
        $request->validate([
           'title' => 'required',
           'description' => 'required'
        ]);
        Appointment::create([
            'title' => request('title'),
            'client_id' => 1,
            'start_time' => now(),
            'end_time' => now(),
            'description' => request('description'),
            'status' => AppointmentStatus::SCHEDULED
        ]);
        return response()->json(['message' => 'Create Appointment Successfully']);
    }
}
