<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Appointments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <router-link to="/admin/appointments/create" class="btn btn-primary">
                                <i class="fa fa-plus-circle mr-1"></i>Add New Appointment
                            </router-link>
                        </div>
                        <div class="btn-group">
                            <button @click="getAppointment()" type="button" class="btn" :class="[typeof selectedStatus === 'undefined' ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-info">{{appointmentCount}}</span>
                            </button>

                            <button
                                v-for="status in appointmentStatus"
                                @click="getAppointment(status.value)"
                                type="button" class="btn" :class="[selectedStatus === status.value ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">{{status.name}}</span>
                                <span class="badge" :class="`badge-${status.color}`">{{status.count}}</span>
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(appointment, index) in appointments" :key="appointment.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ appointment.client.first_name }} {{appointment.client.last_name}}</td>
                                    <td>{{ appointment.start_time }}</td>
                                    <td>{{ appointment.end_time }}</td>
                                    <td>
                                            <span class="badge" :class="`badge-${appointment.status.color}`">{{ appointment.status.name }}</span>
                                    </td>
                                    <td>
                                        <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                            <i class="fa fa-edit mr-2 text-primary"></i>
                                        </router-link>

                                        <a href="#" @click="deleteAppointment(appointment.id)">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<script setup>
import axios from "axios";
import {computed, onMounted, ref} from "vue";
import { useRouter,useRoute} from "vue-router";
import Swal from "sweetalert2";
const appointments = ref([])
const appointmentStatus = ref([])
const selectedStatus = ref()
const router = useRouter()
const route = useRoute()
const getAppointmentStatus = () => {
    axios.get('/api/appointments/appointment-status')
        .then((response) => {
            appointmentStatus.value = response.data
        })

}
const getAppointment = async (status) => {
    selectedStatus.value = status
    const params = {};
    // Kiểm tra status có tồn tại hay không, nếu có gán giá trị của status vào thuộc tính status trong đối tượng params
    if (status){
        params.status = status
    }
    const response = await axios.get('/api/appointments/list', {
        params: params
    })
    appointments.value = response.data
}
const updateAppointmentStatusCount = (id) => {
    const deletedAppointmentStatus = appointments.value.find(appointment => appointment.id === id).status.name;
    const statusToUpdate = appointmentStatus.value.find(status => status.name === deletedAppointmentStatus);
    statusToUpdate.count--;
};
const deleteAppointment = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/appointments/${id}/delete`)
                .then((response) => {
                    updateAppointmentStatusCount(id)
                    appointments.value = appointments.value.filter(appointment => appointment.id !== id)
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                })
        }
    });
}
// ***
const appointmentCount = computed(() => {
    return appointmentStatus.value.map(status => status.count).reduce((acc, value) => acc + value, 0)
})
onMounted(()=> {
    getAppointment();
    getAppointmentStatus();
})
</script>
