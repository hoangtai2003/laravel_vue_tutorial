<script setup>
    import axios from "axios";
    import { useToastr } from "@/toastr";
    import { ref } from "vue";

    defineProps({
        user: Object
    })
    const users = ref([])
    const toastr = useToastr();
    const userIdBeingDeleted = ref(null)
    const emit = defineEmits(['userDeleted', 'editUser'])
    const confirmDeleteUser = (user) => {
        //Đặt giá trị userIdBeingDeleted thành id của người dùng cần xóa
        userIdBeingDeleted.value = user.id
        $('#deleteUserModal').modal('show')
    }
    const deleteUser = () => {
        axios.delete(`/api/destroy/users/${userIdBeingDeleted.value}`)
            .then(response => {
                getUser()
                $('#deleteUserModal').modal('hide')
                toastr.success("Delete user successfully!")
                emit("userDeleted", userIdBeingDeleted.value)
            }).catch(error => {
                toastr.error("Error deleting user")
        });
    }
    const getUser = () => {
        axios.get('/api/list/users')
            // Sau khi nhận được respone thì gán giá trị response vào users thông qua .value
            .then((response) => {
                users.value = response.data
            })
    }
    const editUser = (user) => {
        emit("editUser", user)
    }

</script>
<template>
    <tr>
        <th scope="row">{{user.id}}</th>
        <td>{{user.name}}</td>
        <td>{{user.email}}</td>
        <td>{{user.formatted_created_at}}</td>
        <td>{{user.role}}</td>
        <td>
            <a href="#" @click.prevent="editUser(user)">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="confirmDeleteUser(user)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this user ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete User</button>
                </div>
            </div>
        </div>
    </div>

</template>
