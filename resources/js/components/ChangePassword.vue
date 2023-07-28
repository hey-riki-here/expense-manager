<script>
import axios from 'axios';
export default {
    data() {
        return {
            oldPass: '',
            newPass: '',
            userData: JSON.parse(window.sessionStorage.getItem('user')),
            success: false,
            passwordMismatch: false,
            loading: false,
        };
    },
    methods: {
        async update() {

            if (this.oldPass === this.userData.password) { 
                this.loading = true;
                this.userData.password = this.newPass;
                window.sessionStorage.setItem('user', JSON.stringify(this.userData));
                await axios.put('api/user/'.concat(this.userData.id), this.userData).then(response => {
                    this.oldPass = '';
                    this.newPass = '';
                    this.passwordMismatch = false;
                    this.success = true;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    console.log(error);
                });
            } else {
                this.passwordMismatch = true;
            }

        },
    }
}
</script>
<template>
    <div class="relative ">
        <!-- <div
            class="absolute top-0 left-0 w-full h-full bg-white opacity-60 flex justify-center items-center">
            
        </div> -->
        <div class="p-8 h-full">
            <div class="flex justify-between mb-8">
                <p class="font-semibold">Change password</p>
                <p class="font-semibold">Settings > Change password</p>
            </div>
            <div>
                <div>
                    <p>You are currently changing your password. Enter your old and new desired password.</p>
                    <div v-if="success" class="border border-green-200 bg-green-100 p-2 rounded text-gray-600 text-sm my-4">
                        You have successfully change your password.</div>
                    <div v-if="passwordMismatch"
                        class="border border-red-200 bg-red-100 p-2 rounded text-gray-600 text-sm my-4">Password does not
                        match!</div>
                    <form @submit.prevent="update">
                        <p class="text-sm mt-3">Enter old password</p>
                        <input v-model="oldPass" class="border" type="password">
                        <p class="text-sm mt-3">Enter new password</p>
                        <input v-model="newPass" class="border" type="password">
                        <button class="block mt-3 bg-cyan-950 text-white px-2 py-1 rounded hover:bg-cyan-800" type="submit">
                            <span v-if="loading == false">Submit</span>
                            <img v-else class="w-[21px] h-[21px] opacity-100" src="../../assets/img/loading-gif.gif" alt="">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style></style>