<script>
import axios from 'axios';

export default {
    name: 'UserModal',
    props: ['action', 'id', 'users'],
    data() {
        return {
            user: {},
            loading: false,
            roles: [],
            prevRole: '',
            emailAreadyInUse: false,
        }
    },
    watch: {
        id: {
            immediate: true,
            async handler(newVal, oldVal) {
                if (this.action == 'update') {
                    this.showUser(this.id);
                } else {
                    this.user = {};
                    await this.getRoles();
                }
            }
        },
    },
    methods: {
        close() {
            this.$emit('close');
        },
        async formAction() {
            if (this.action == 'add') {
                this.create();
            } else {
                this.update();
            }
        },
        async create() {
            this.loading = true;
            this.user.password = 'creadtedbyadmin';
            await axios.post('/api/user', this.user).then(response => {
                if (response.data.created) {
                    this.emailAreadyInUse = false;
                    this.close();
                    this.users.push(response.data.user);
                } else {
                    this.emailAreadyInUse = true;

                }
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                this.close();
                console.log(error);
            });
        },
        async showUser(id) {
            this.loading = true;
            await axios.get('api/user/'.concat(id)).then(async response => {
                const { name, email_address, role } = response.data;
                this.user.name = name;
                this.user.email_address = email_address;
                this.user.role = role;
                this.prevRole = role;
                await this.getRoles();
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                console.log(error);
            });
        },
        async update() {
            this.loading = true;
            this.user.id = this.id;
            await axios.put('api/user/'.concat(this.id), this.user).then(response => {

                this.users.every((user, index) => {
                    if (user.id === this.user.id) {
                        this.user.created_at = user.created_at;
                        this.users[index] = this.user;
                        this.prevRole = this.user.role;
                        return false;
                    } else {
                        return true;
                    }
                });

                this.close();
                this.loading = false;
            }).catch(error => {
                this.close();
                this.loading = false;
                console.log(error);
            });
        },
        async deleteUser() {
            if (confirm('Are you sure you want to delete this user?')) {
                this.loading = true;
                await axios.delete('/api/user/'.concat(this.id)).then(response => {

                    this.users.every((user, index) => {
                        if (user.id === this.id) {
                            this.users.splice(index, 1);
                            return false;
                        } else {
                            return true;
                        }
                    });

                    this.close();
                    this.loading = false;
                }).catch(error => {
                    this.close();
                    this.loading = false;
                    console.log(error);
                });
            } else {
                this.close();
            }
        },
        async getRoles() {
            await axios.get('/api/role').then(response => {
                this.roles = response.data
            }).catch(error => {
                console.log(error);
                this.roles = [];
            });
        },
    },
};
</script>

<template>
    <transition name="modal-fade">
        <div class="modal-backdrop">
            <div class="modal" role="dialog" aria-labelledby="modalTitle" aria-describedby="modalDescription">
                <header class="modal-header" id="modalTitle">
                    <slot name="header">
                        {{ action == "add" ? 'Add' : 'Update' }} User
                    </slot>
                </header>

                <section class="modal-body" id="modalDescription">
                    <slot name="body">
                        <div v-if="loading == true && action == 'update'" class="w-[317px] text-center">
                            <i class="text-xs">Loading data...</i>
                        </div>
                        <form class="relative" v-else @submit.prevent="formAction">
                            <div v-if="loading == true" class="absolute top-0 left-0 w-full h-full bg-white opacity-60">
                            </div>
                            <div class="flex gap-5 mb-2">
                                <p class="mr-auto">Name</p>
                                <input :readonly="action === 'update' && prevRole === 'Administrator'" required type="text"
                                    v-model="user.name" class="border rounded align-sub" />
                            </div>
                            <div class="flex gap-5 mb-2">
                                <p class="mr-auto">Email address</p>
                                <div>
                                    <input :readonly="action === 'update' && prevRole === 'Administrator'" required
                                        type="text" v-model="user.email_address" class="border rounded" />
                                    <p v-if="emailAreadyInUse" class="text-sm text-red-600">Email alreay in use!</p>
                                </div>

                            </div>
                            <div class="flex gap-5 mb-2">
                                <p class="mr-auto">Role</p>
                                <select v-if="action === 'add' || prevRole !== 'Administrator'" required v-model="user.role"
                                    class="border rounded w-full">
                                    <option v-for="(role, key) in roles" :key="key" :value="role.display_name">{{
                                        role.display_name }}
                                    </option>
                                </select>
                                <input v-else readonly required type="text" v-model="user.role"
                                    class="border rounded align-sub" />
                            </div>
                            <footer class="modal-footer">
                                <button v-if="action == 'update' && prevRole !== 'Administrator'" type="button"
                                    class="bg-red-600 text-white mr-auto px-2 py-1 rounded  hover:bg-red-500"
                                    @click="deleteUser" aria-label="Close modal">
                                    Delete
                                </button>
                                <button type="button"
                                    class="border border-cyan-600 px-2 py-1 rounded hover:bg-cyan-950 hover:text-white  hover:border-cyan-950"
                                    @click="close" aria-label="Close modal">
                                    Close
                                </button>
                                <button v-if="action == 'add' || prevRole !== 'Administrator'" type="submit"
                                    class="bg-cyan-950 text-white px-2 py-1 rounded hover:bg-cyan-800"
                                    aria-label="Close modal">
                                    {{ action == "add" ? 'Save' : 'Update' }}
                                </button>
                            </footer>
                        </form>
                    </slot>
                </section>
            </div>
        </div>
    </transition>
</template>

<style></style>