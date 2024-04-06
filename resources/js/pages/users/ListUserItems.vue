<template>
    <tr>
        <td><input type="checkbox" @change="toggleSelection" :checked="selectAll"></td>
        <th scope="row">{{index + 1}}</th>
        <td>{{user.name}}</td>
        <td>{{user.email}}</td>
        <td>{{user.formatted_created_at}}</td>
        <td>
            <!-- $event.target.value: giá trị mới được chọn trong selecbox            -->
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <!--value="role.value" Đặt giá trị của mỗi tùy chọn trong select box bằng giá trị của thuộc tính value của đối tượng role.             -->
                <option v-for="role in roles" :value="role.value" :selected="(user.role === role.name)" >{{role.name}}</option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="editUser(user)">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="emit('confirmDeleteUser', user.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
</template>
<script setup>
import axios from "axios";
import { useToastr } from "@/toastr";
import { ref } from "vue";

const props = defineProps({
    user: Object,
    index: Number,
    selectAll: {type: Boolean, default: false}
})
const roles = ref([
    {
        name: 'USER',
        value: 2
    },
    {
        name: 'ADMIN',
        value: 1
    }
])
const users = ref([])
const toastr = useToastr();
const emit = defineEmits(['editUser', 'toggleSelection', 'confirmDeleteUser'])
const toggleSelection = () => {
    emit('toggleSelection', props.user)
}
const getUser = () => {
    axios.get('/api/users/list')
        // Sau khi nhận được respone thì gán giá trị response vào users thông qua .value
        .then((response) => {
            users.value = response.data
        })
}
const editUser = (user) => {
    emit("editUser", user)
}
const changeRole = (user, role) => {
    axios.patch(`/api/users/change-role/${user.id}`, {
        role: role,
    }).then(() => {
        toastr.success("Change role successfully!")
    })

}
</script>
