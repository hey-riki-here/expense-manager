<script>
export default {
    data() {
        return {
            userData: JSON.parse(window.sessionStorage.getItem('user')),
        };
    },
}

</script>

<template>
    <div v-if="userData != null" class="relative flex min-h-screen">
        <!-- Sidebar menu -->
        <div class="bg-cyan-950 text-cyan-100 w-56">
            <div class="w-8 h-8 mx-5 mt-5 bg-gray-400 rounded-full"></div>
            <p class="mt-2 mb-8 px-5">{{ userData && userData.name }} ({{ userData && userData.role }})</p>
            <nav class="flex flex-col">
                <a class="mb-2 px-5 py-1 hover:bg-cyan-900" href="/dashboard">Dashboard</a>
                <div class="py-1" v-if="userData && userData.role == 'Administrator'">
                    <p class="mb-2 px-5 py-1 select-none">User management</p>
                    <a class="mb-2 px-8 py-1 block hover:bg-cyan-900" href="/roles">Roles</a>
                    <a class="mb-2 px-8 py-1 block hover:bg-cyan-900" href="/users">User</a>
                </div>
                <p class="mb-2 px-5 py-1 select-none">Expense management</p>
                <a v-if="userData && userData.role == 'Administrator'" class="mb-2 px-8 py-1 hover:bg-cyan-900" href="/expense-categories">Expense
                    categories</a>
                <a class="mb-2 px-8 py-1 hover:bg-cyan-900" href="/expenses">Expenses</a>
                <p v-if="userData && userData.role !== 'Administrator'" class="mb-2 px-5 py-1 select-none">Settings</p>
                <a v-if="userData && userData.role !== 'Administrator'" class="mb-2 px-8 py-1 hover:bg-cyan-900" href="/change-password">Change
                    password</a>
            </nav>
        </div>
    </div>
</template>
<style></style>