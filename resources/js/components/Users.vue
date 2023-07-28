<script>

import UserModal from './modals/UserModal.vue';
import axios from 'axios';

export default {
    components: {
        UserModal,
    },
    data() {
        return {
            isModalVisible: false,
            action: '',
            userId: '',
            users: [],
            selectedUser: {},
        };
    },
    mounted() {
        this.getUsers()
    },
    methods: {
        showModal(action, id) {
            this.isModalVisible = true;
            this.action = action;
            this.userId = id;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        async getUsers() {
            await axios.get('/api/user').then(response => {
                this.users = response.data
            }).catch(error => {
                console.log(error);
                this.users = [];
            });
        },
        getFormattedDate(dateString) {
            return dateString.split('T')[0];
        }
    }
}
</script>

<template>
    <div class="relative p-8">
        <div class="flex justify-between mb-8">
            <p class="font-semibold">Users</p>
            <p class="font-semibold">User Management > Users</p>
        </div>
        <div class="border rounded flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b bg-white font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Name</th>
                                    <th scope="col" class="px-6 py-4">Email address</th>
                                    <th scope="col" class="px-6 py-4">Role</th>
                                    <th scope="col" class="px-6 py-4">Created at</th>
                                </tr>
                            </thead>
                            <tbody v-if="users.length > 0">
                                <tr v-for="(user, key) in users" :key="key" class="border-b bg-neutral-100 hover:cursor-pointer" @click="showModal('update', user.id);">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ user.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ user.email_address }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ user.role }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ getFormattedDate(user.created_at) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="4" align="center">No users found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" @click="showModal('add')" class="bg-cyan-950 rounded px-2 py-1 mt-2 text-white hover:bg-cyan-800">Add
                User</button>
        </div>
        <UserModal :action=action :id=userId :users=users v-show="isModalVisible" @close="closeModal" />
    </div>
</template>

<style></style>