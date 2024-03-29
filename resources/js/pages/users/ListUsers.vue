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
            <button type="button" class="mb-2 btn btn-primary" data-toggle="modal" data-target="#createUserModal">
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
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <th scope="row">{{user.id}}</th>
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="createUserModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Add New User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" type="text" class="form-control" v-model="form.name"
                                   id="name" aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control " v-model="form.email"
                                   placeholder="Enter full name" />
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Password</label>
                            <input name="password" type="password" class="form-control " v-model="form.password"
                                   placeholder="Enter password" />
                            <span class="invalid-feedback"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" @click="createUser" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import axios from "axios";
    import {onMounted, reactive, ref} from "vue";
    let users = ref([])
    const form = reactive({
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
    const createUser = () => {
        axios.post('/api/create/users', form)
            .then((response) => {
                //Thêm dữ liệu của user mới vào mảng users, response.data chứa dữ liệu của user mới mà máy chủ trả về sau khi tạo user thành công
                users.value.push(response.data)
                form.name = '';
                form.email = '';
                form.password = '';
                $('#createUserModal').modal('hide')
            })
    }
    // Gọi lại hàm getUser() khi component được mounted
    onMounted(() => {
        getUser()
    })
</script>
