<script>
import axios from 'axios';

export default {
    data() {
        return {
            user: {
                email: '',
                password: '',
            },
            loading: false,
            success: true,
            users: [],
            hasUserLoggedIn: false,
        };
    },
    created() {
        if (window.sessionStorage.length > 0) {
            this.hasUserLoggedIn = true;
            window.location.href = '/dashboard';
        }
    },
    methods: {
        async login() {
            this.loading = true;
            await axios.post('/api/login', this.user).then(response => {
                if (response.data != '') {
                    window.sessionStorage.setItem('user', JSON.stringify(response.data));
                    window.location.href = '/dashboard';
                    this.success = true;
                } else {
                    this.loading = false;
                    this.success = false;
                }
            }).catch(error => {
                this.loading = false;
                this.success = false;
                console.log(error);
                this.users = [];
            });
        },
    }
}
</script>
<template>
    <div v-if="hasUserLoggedIn == false" class="flex justify-center bg-cyan-950 w-full h-screen text-white text-center">
        <div class="relative self-center bg-white w-fit text-black rounded p-8">
            <div v-if="loading" class="absolute top-0 left-0 w-full h-full bg-white opacity-60 flex justify-center items-center">
                <img class="w-8 h-8 opacity-100" src="../../assets/img/loading-gif.gif" alt="">
            </div>
            <form class="mb-3" @submit.prevent="login">
                <p class="text-xl font-semibold">Expense Manager (Login)</p>
                <p v-if="success == false" class="text-sm text-red-600">Invalid email or password!</p>
                <div class="my-3">
                    <p>Email</p>
                    <input required v-model="user.email" class="border rounded" type="text">
                </div>
                <div class="mb-3">
                    <p>Password</p>
                    <input required v-model="user.password" class="border rounded" type="password">
                </div>
                <button class="bg-cyan-950 text-white rounded px-4 py-1 hover:bg-cyan-800" type="submit">Login</button>
            </form>
            <a class="text-sm text-blue-500 underline" href="/register">Register</a>
        </div>
    </div>
</template>
<style></style>