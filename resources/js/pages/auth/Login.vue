<template>
<!--    <div class="login-box">-->

<!--        <div class="card card-outline card-primary">-->
<!--            <div class="card-header text-center">-->
<!--                <a href="#" class="h1"><b>Admin</b>Login</a>-->
<!--            </div>-->
<!--            <div class="card-body">-->
<!--                <p class="login-box-msg">Sign in to start your session</p>-->
<!--                <div v-if="errorMessage" class="alert alert-danger" role="alert">-->
<!--                    {{errorMessage}}-->
<!--                </div>-->
<!--                <form @submit.prevent="handleSubmit">-->
<!--                    <div class="input-group mb-3">-->
<!--                        <input v-model="form.email" type="email" class="form-control" placeholder="Email">-->
<!--                        <div class="input-group-append">-->
<!--                            <div class="input-group-text">-->
<!--                                <span class="fas fa-envelope"></span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="input-group mb-3">-->
<!--                        <input v-model="form.password" type="password" class="form-control" placeholder="Password">-->
<!--                        <div class="input-group-append">-->
<!--                            <div class="input-group-text">-->
<!--                                <span class="fas fa-lock"></span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <div class="col-8">-->
<!--                            <div class="icheck-primary">-->
<!--                                <a href="/register">-->
<!--                                    Register-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="col-4">-->
<!--                            <button type="submit" class="btn btn-primary btn-block">-->
<!--                                <div v-if="loading" class="spinner-border text-light spinner-border-sm" role="status">-->
<!--                                    <span class="sr-only">Loading...</span>-->
<!--                                </div>-->
<!--                                <span v-else >Sign in</span></button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->

<!--        </div>-->
<!--    </div>-->
</template>
<script setup>
import {reactive, ref} from "vue";
import axios from "axios";
import {useStore} from "vuex";
import {useRouter} from "vue-router";
import { onMounted } from 'vue';

const errorMessage = ref('')
const loading = ref(false)
const router = useRouter()
const store = useStore()
const form = reactive({
    email: '',
    password: '',
})
onMounted(() => {
    if (store.state.token !== '') {
        axios.post('/api/auth/checkToken', {
            token: store.state.token
        })
            .then((response) => {
                if (response) {
                    loading.value = false;
                    router.push('/admin/dashboard');
                }
            })
            .catch((error) => {
                loading.value = false;
                store.commit('clearToken');
            });
    } else {
        loading.value = false;
    }
});
const handleSubmit = () => {
    loading.value = true
    axios.post('/api/auth/login', form)
        .then((response) => {
            if (response.data.access_token) {
                localStorage.setItem('auth', response.data.access_token); // Lưu access_token vào localStorage
                store.commit('setToken', response.data.access_token); // Cập nhật token trong store
                // commit được sử dụng để gọi một mutation, tức là thay đổi trạng thái trong store
                router.push('/admin/dashboard')
            }
        })
        .catch((error) => {
        })
        .finally(() => {
            loading.value = false
        })
}
</script>
