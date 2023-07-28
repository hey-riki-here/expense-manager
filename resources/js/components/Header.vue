<script>
export default {
    data() {
        return {
            userData: JSON.parse(window.sessionStorage.getItem('user')),
        };
    },
    methods: {
        logout() {
            window.sessionStorage.removeItem('user');
            window.location.href = '/login';
        }
    }
}
</script>

<template>
    <div v-if="userData != null">
        <!-- Header -->
        <div class="flex justify-end gap-4 bg-white shadow px-3 py-4">
            <p class="text-gray-400">Welcome to Expense Manager</p>
            <button class="text-gray-400" @click="logout">Log out</button>
        </div>
    </div>
</template>

<style></style>