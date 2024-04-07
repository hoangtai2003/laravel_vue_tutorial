<template>
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Admin</b>Register</a>
            </div>
            <div class="card-body">
                <form @submit.prevent="handleSubmit">
                    <div class="input-group mb-3">
                        <input v-model="form.name" type="text" class="form-control" placeholder="Name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="form.email" type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="form.password" type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="form.cpassword" type="password" class="form-control" placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <a href="/login">
                                    Login
                                </a>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</template>
<script setup>
import {reactive} from "vue";
import axios from "axios";

const form = reactive({
    name: '',
    email: '',
    password: '',
    cpassword: ''
})
const handleSubmit = () => {
    axios.post('/register', form)
        .then(() => {
            window.location.href = '/login';
        })
}
</script>
