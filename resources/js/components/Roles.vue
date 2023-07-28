<script>

import RoleModal from './modals/RoleModal.vue';
import axios from 'axios';
export default {
    components: {
        RoleModal,
    },
    data() {
        return {
            isModalVisible: false,
            action: '',
            roleId: '',
            roles: [],
        };
    },
    mounted() {
        this.getRoles()
    },
    methods: {
        showModal(action, id) {
            this.isModalVisible = true;
            this.action = action;
            this.roleId = id;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        async getRoles() {
            await axios.get('/api/role').then(response => {
                this.roles = response.data
            }).catch(error => {
                console.log(error);
                this.roles = [];
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
            <p class="font-semibold">Roles</p>
            <p class="font-semibold">User Management > Roles</p>
        </div>
        <div class="border rounded flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b bg-white font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Display name</th>
                                    <th scope="col" class="px-6 py-4">Description</th>
                                    <th scope="col" class="px-6 py-4">Created at</th>
                                </tr>
                            </thead>
                            <tbody v-if="roles.length > 0">
                                <tr v-for="(role, key) in roles" :key="key"
                                    class="border-b bg-neutral-100 hover:cursor-pointer"
                                    @click="showModal('update', role.id);">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ role.display_name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ role.description }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ getFormattedDate(role.created_at) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3" align="center">No roles found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" @click="showModal('add')" class="bg-cyan-950 rounded px-2 py-1 mt-2 text-white hover:bg-cyan-800">Add
                Role</button>
        </div>
        <RoleModal :action=action :id=roleId :roles=roles v-show="isModalVisible" @close="closeModal" />
    </div>
</template>

<style></style>