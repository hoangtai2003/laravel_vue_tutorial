<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">ListUsers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">ListUsers</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <button @click="addUser" type="button" class="mb-2 btn btn-primary" >
                Add new User
            </button>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <th scope="row">{{user.id}}</th>
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                            <td>
                                <a href="#" @click.prevent="editUser(user)">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form @submit="handleSubmit" :initial-values="formValues">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control"
                                   id="name" aria-describedby="nameHelp" placeholder="Enter full name" v-model="formValues.name"/>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control "
                                   id="email" aria-describedby="nameHelp" placeholder="Enter full name" v-model="formValues.email"/>
                        </div>

                        <div class="form-group">
                            <label for="email">Password</label>
                            <Field name="password" type="password" class="form-control "
                                   id="password" aria-describedby="nameHelp" placeholder="Enter password" v-model="formValues.password"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" v-if="editing">Update</button>
                        <button type="submit" class="btn btn-primary" v-else>Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
<script setup>
    import axios from "axios";
    import { onMounted, ref } from "vue";
    import { Form, Field } from 'vee-validate';
    import * as yup from 'yup';
    const users = ref([])
    const editing = ref(false)
    const formValues = ref({
        name: '',
        email: '',
        password: ''
    })
    const getUser = () => {
        axios.get('/api/list/users')
            // Sau khi nhận được respone thì gán giá trị response vào users thông qua .value
            .then((response) => {
                users.value = response.data
        })
    }
    const createUser = (values) => {
        axios.post('/api/create/users', values)
            .then((response) => {
                getUser()
                $('#userFormModal').modal('hide')
            })
    }
    const editUser = (user) => {
        editing.value = true
        $('#userFormModal').modal('show')
        formValues.value = {
            id: user.id,
            name: user.name,
            email: user.email,
            password: ''
        }
    }
    const addUser = () => {
        editing.value = false
        $('#userFormModal').modal('show')
        formValues.value = {
            name: '',
            email: '',
            password: ''
        }
    }
    const updateUser = (values) => {
        axios.put('/api/update/users/' + formValues.value.id, values)
            .then((response) => {
                getUser()
                $('#userFormModal').modal('hide')
            })
    }
    const handleSubmit = (values) => {
        if (editing.value) {
            updateUser(values)
        } else {
            createUser(values)
        }
    }
    // Gọi lại hàm getUser() khi component được mounted
    onMounted(() => {
        getUser()
    })
</script>
