<template>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <div class="d-flex justify-content-between">
                        <h3>{{totalAppointmentCount}}</h3>
                        <select @change="getAppointmentsCount()" v-model="selectedAppointmentSatus"
                            style="height: 2rem; outline: 2px solid transparent;" class="px-1 rounded border-0">
                            <option>All</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <p>Appointments</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <router-link to="/admin/appointments" class="small-box-footer">
                    View Appointments
                    <i class="fas fa-arrow-circle-right"></i>
                </router-link>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <div class="d-flex justify-content-between">
                        <h3>0</h3>
                        <select
                            style="height: 2rem; outline: 2px solid transparent;" class="px-1 rounded border-0">
                            <option value="TODAY">Today</option>
                            <option value="30">30 days</option>
                            <option value="60">60 days</option>
                            <option value="360">360 days</option>
                            <option value="MTD">Month to Date</option>
                            <option value="YTD">Year to Date</option>
                        </select>
                    </div>
                    <p>Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <router-link to="/admin/users" class="small-box-footer">
                    View Users
                    <i class="fas fa-arrow-circle-right"></i>
                </router-link>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
const selectedAppointmentSatus = ref('all')
const totalAppointmentCount = ref(0)
const getAppointmentsCount = () => {
   axios.get('/api/dashboard/appointments', {
       params: {
           status: selectedAppointmentSatus.value,
       }
   }).then((response) => {
       totalAppointmentCount.value = response.data.totalAppointmentCount
   })
}
onMounted(() => {
    getAppointmentsCount()
})
</script>
