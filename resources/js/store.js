import { createStore } from 'vuex';

const store = createStore({
    state() {
         // Trong state định nghĩa một trường token với giá trị mặc định được lấy từ localStorage với key là "auth"
        return {
            token: localStorage.getItem('auth') || '',
        };
    },
    mutations: {
        // Cài đặt giá trị mới cho trường token và lưu giá trị đó vào localStorage
        setToken(state, token) {
            localStorage.setItem('auth', token);
            state.token = token;
        },
        //xóa token khỏi localStorage và đặt trường token thành chuỗi rỗng.
        clearToken(state) {
            localStorage.removeItem('auth');
            state.token = '';
        },
    },
});

export default store;
