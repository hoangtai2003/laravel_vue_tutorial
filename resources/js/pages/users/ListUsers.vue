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
            <div class="d-flex justify-content-between">
                <div>
                    <button @click="addUser" type="button" class="mb-2 btn btn-primary" >
                        <i class="fa fa-plus-circle mr-1"></i>
                        Add new User
                    </button>
                    <button v-if="selectedUsers.length > 0" @click="bulkDelete" type="button" class="ml-2 mb-2 btn btn-danger">
                        <i class="fa fa-trash mr-1"></i>
                        Delete Selected
                    </button>
                </div>

                <div>
                    <input type="text" class="form-control" placeholder="Search..." v-model="searchQuery">
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th><input type="checkbox" v-model="selectAll" @change="selectedAllUsers"></th>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered Date</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="users.length > 0">
                            <!-- @: dùng để lắng nghe sự kiện sự kiện từ component con và gọi lại trong component cha (giống với v-on) -->
                            <!-- : Được dùng để truyền prop vào component con (giống với v-bind) -->
                            <ListUserItems v-for="(user, index) in users"
                                           :key="user.id"
                                           :index=index
                                           :user=user
                                           @edit-user="editUser"
                                           @user-deleted="userDeleted"
                                           @toggle-selection="toggleSelection"
                                           :select-all="selectAll"/>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6">No results found .... </td>
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
import {onMounted, ref, watch} from "vue";
import { Form, Field } from 'vee-validate';
import { useToastr } from "@/toastr";
import ListUserItems from "@/pages/users/ListUserItems.vue";

const index = ref(null);
const toastr = useToastr();
const users = ref([])
const editing = ref(false)
const formValues = ref({
    name: '',
    email: '',
    password: ''
})
const selectedUsers = ref([]);
const searchQuery = ref(null)
const selectAll = ref(false)
const getUser = () => {
    axios.get('/api/users/list')
        // Sau khi nhận được respone thì gán giá trị response vào users thông qua .value
        .then((response) => {
            users.value = response.data
        })
}
const createUser = (values) => {
    axios.post('/api/users/create', values)
        .then((response) => {
            getUser()
            $('#userFormModal').modal('hide')
            toastr.success("User update successfully!")
        }).catch((error) => {
        toastr.error("User creation failed")
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
    axios.put('/api/users/update/' + formValues.value.id, values)
        .then(response => {
            getUser()
            $('#userFormModal').modal('hide')
            toastr.success("User update successfully!")
        }).catch(error => {
        toastr.error("User update failed")
    })
}
const handleSubmit = (values) => {
    if (editing.value) {
        updateUser(values)
    } else {
        createUser(values)
    }
}
const userDeleted = (userId) => {
    // Kiểm tra xem id của người dùng hiện tại có trùng với id cần xóa hay không nếu có sẽ giữ lại nếu không sẽ loại bỏ khỏi mảng
    users.value = users.value.filter(user => user.id !== userId);
}
const search = () => {
    axios.get('/api/users/search', {
        params: {
            searchQuery: searchQuery.value
        }
    }).then((response) => {
        users.value = response.data
    }).catch((error) => {
        console.error(error);
    })
}
const toggleSelection = (user) => {
    // indexOf() tìm kiếm vị trí của user.id trong mảng selectedUsers.value
    const index = selectedUsers.value.indexOf(user.id)
    if (index === -1) { // Người dùng đã được chọn
        // Sử dụng push() để thêm user.id vào mảng
        selectedUsers.value.push(user.id)
    } else { // Người dùng đã được chọn trước đo
        selectedUsers.value.splice(index, 1)
        // Sử dụng splice() để loại bỏ phần tử vị trí index khỏi mảng
    }
}
const bulkDelete = () => {
    axios.delete('/api/users', {
        params: {
            ids: selectedUsers.value
        }
    }).then((response) => {
        getUser()
        selectedUsers.value = []
        selectAll.value = false
        toastr.success(response.data.message)
    })
}
const selectedAllUsers = () => {
    if (selectAll.value){
        selectedUsers.value = users.value.data.map(user => user.id)
    } else {
        selectedUsers.value = []
    }
}
watch(searchQuery, () => {
    search()
})
// Gọi lại hàm getUser() khi component được mounted
onMounted(() => {
    getUser();
})
</script>
