<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Edit</span>
                        <span v-else>Create</span>
                        Appointment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input v-model="form.title" type="text" class="form-control" id="title" placeholder="Enter Title">
                                            <span class="text-danger text-sm" v-if="errors && errors.title">{{errors.title[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client Name</label>
                                            <select v-model="form.client_id" id="client" class="form-control" >
                                                <option v-for="client in clients" :value="client.id" :key="client.id">{{client.first_name}} {{client.last_name}}</option>
                                            </select>
                                            <span class="text-danger text-sm" v-if="errors && errors.client_id">{{errors.client_id[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start-time">Start Time</label>
                                            <input v-model="form.start_time"  type="text" class="form-control flatpickr"  id="start-time">
                                            <span class="text-danger text-sm" v-if="errors && errors.start_time">{{errors.start_time[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end-time">End Time</label>
                                            <input v-model="form.end_time" type="text" class="form-control flatpickr"  id="end-time">
                                            <span class="text-danger text-sm" v-if="errors && errors.end_time">{{errors.end_time[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea  class="form-control"  id="description" rows="3"
                                              placeholder="Enter Description" v-model="form.description"></textarea>
                                    <span class="text-danger text-sm" v-if="errors && errors.description">{{errors.description[0]}}</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, reactive, ref} from "vue";
import { Form } from "vee-validate";
import axios from "axios";
import { useToastr } from "@/toastr";
import { useRouter, useRoute } from "vue-router";
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';
const toastr = useToastr()
const router = useRouter()
const route = useRoute()
const form = reactive({
    title: '',
    client_id: '',
    start_time: '',
    end_time: '',
    description: ''
})
const errors = ref()
const clients = ref([])
const editMode = ref(false)
const handleSubmit = (values) => {
    if (editMode.value){
        updateAppointment(values)
    } else {
        createAppointment(values)
    }
}
const createAppointment = () => {
    errors.value = ''
    axios.post('/api/appointments/create', form)
        .then((response) => {
            router.push('/admin/appointments')
            toastr.success(response.data.message)
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
            toastr.error("Appointment creation fails")
        })
}
const updateAppointment = () => {
    errors.value = ''
    axios.put(`/api/appointments/${route.params.id}/update`, form)
        .then((response) => {
            router.push('/admin/appointments')
            toastr.success(response.data.message)
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
            toastr.error("Appointment update fails")
        })
}
const getClient = async () => {
    const response = await axios.get('/api/clients/list')
    clients.value = response.data
}
const getAppointment = () => {
    axios.get(`/api/appointments/${route.params.id}/edit`)
        .then((response) => {
            form.title = response.data.title
            form.client_id = response.data.client_id
            form.start_time = response.data.formatted_start_time
            form.end_time = response.data.formatted_end_time
            form.description = response.data.description
        })
}
onMounted(() => {
    if (route.name === 'admin.appointments.edit'){
        editMode.value = true
        getAppointment()
    }
    flatpickr (".flatpickr", {
        enableTime: true,
        dateFormat: 'Y-m-d h:i K',
        defaultHour: 10,
    });
    getClient()
})
</script>
