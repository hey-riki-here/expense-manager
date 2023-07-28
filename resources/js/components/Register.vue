<script>
import axios from 'axios';
export default {
    data() {
        return {
            newUser: {
                name: '',
                email_address: '',
                password: '',
                role: 'User',
            },
            reEnterPass: '',
            passwordMismatch: false,
            hasUserLoggedIn: false,
            loading: false,
            success: false,
            emailAreadyInUse: false,
        }
    },
    created() {
        if (window.sessionStorage.length > 0) {
            this.hasUserLoggedIn = true;
            window.location.href = '/dashboard';
        }
    },
    methods: {
        async create() {
            if (this.newUser.password === this.reEnterPass) {
                this.loading = true;
                await axios.post('/api/user', this.newUser).then(response => {
                    console.log(response.data);
                    if (response.data.created) {
                        this.success = true;
                        this.emailAreadyInUse = false;
                        this.loading = false;
                        this.passwordMismatch = false;
                        this.newUser.name = '';
                        this.newUser.email_address = '';
                        this.newUser.password = '';
                        this.reEnterPass = '';
                    } else {
                        this.success = false;
                        this.emailAreadyInUse = true;
                        this.loading = false;
                        this.passwordMismatch = false;
                    }
                }).catch(error => {
                    this.success = true;
                    this.loading = false;
                });
            } else {
                this.passwordMismatch = true;
            }
        },
    }
}
</script>
<template>
    <div v-if="hasUserLoggedIn == false" class="flex justify-center bg-cyan-950 w-full h-screen text-white text-center">
        <div class="relative self-center bg-white w-fit text-black rounded p-8">
            <div v-if="loading"
                class="absolute top-0 left-0 w-full h-full bg-white opacity-60 flex justify-center items-center">
                <img class="w-8 h-8 opacity-100" src="../../assets/img/loading-gif.gif" alt="">
            </div>
            <form class="mb-3" @submit.prevent="create">
                <p class="text-xl font-semibold">Expense Manager (Register)</p>
                <p v-if="success" class="text-sm text-green-600">Account created successfully!</p>
                <p v-if="passwordMismatch" class="text-sm text-red-600">Password does not match!</p>
                <div class="my-3">
                    <p>Name</p>
                    <input required v-model="newUser.name" type="text" class="border rounded w-full">
                </div>
                <div class="my-3">
                    <p>Email</p>
                    <input required v-model="newUser.email_address" type="text" class="border rounded w-full">
                    <p v-if="emailAreadyInUse" class="text-sm text-red-600">Email alreay in use!</p>
                </div>
                <div class="mb-3">
                    <p>Password</p>
                    <input required v-model="newUser.password" type="password" class="border rounded w-full">
                </div>
                <div class="mb-3">
                    <p>Re-enter Password</p>
                    <input required v-model="reEnterPass" type="password" class="border rounded w-full">
                </div>
                <button class="bg-cyan-950 text-white rounded px-4 py-1 hover:bg-cyan-800" type="submit">Register</button>
            </form>
            <a class="text-sm text-blue-500 underline" href="/login">Login</a>
        </div>
    </div>
</template>
<style></style>