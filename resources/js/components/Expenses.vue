<script>
import ExpensesModal from './modals/ExpensesModal.vue';
import axios from 'axios';

export default {
    components: {
        ExpensesModal,
    },
    data() {
        return {
            isModalVisible: false,
            action: '',
            expenseId: '',
            expenses: [],
            userData: JSON.parse(window.sessionStorage.getItem('user')),
        };
    },
    mounted() {
        this.getExpenses()
    },
    methods: {
        showModal(action, id) {
            this.isModalVisible = true;
            this.action = action;
            this.expenseId = id;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        async getExpenses() {
            await axios.get('/api/expense').then(response => {
                if (this.userData.role !== 'Administrator'){
                    this.expenses = response.data.filter(expense => expense.created_by_user_with_id == this.userData.id);
                } else {
                    this.expenses = response.data;
                }
                
            }).catch(error => {
                console.log(error);
                this.expenses = [];
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
            <p class="font-semibold">Expenses</p>
            <p class="font-semibold">Expense Management > Expenses</p>
        </div>
        <div class="border rounded flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b bg-white font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Expense Category</th>
                                    <th scope="col" class="px-6 py-4">Amount</th>
                                    <th scope="col" class="px-6 py-4">Entry Date</th>
                                    <th scope="col" class="px-6 py-4">Created at</th>
                                </tr>
                            </thead>
                            <tbody v-if="expenses.length > 0">
                                <tr v-for="(expense, key) in expenses" :key="key"
                                    class="border-b bg-neutral-100 hover:cursor-pointer"
                                    @click="showModal('update', expense.id);">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ expense.expense_category }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">${{ expense.amount }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ expense.entry_date.split(' ')[0] }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ getFormattedDate(expense.created_at) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="4" align="center">No expenses found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" @click="showModal('add')" class="bg-cyan-950 rounded px-2 py-1 mt-2 text-white hover:bg-cyan-800">Add
                Expense</button>
        </div>
        <ExpensesModal :action=action :id=expenseId :expenses=expenses v-show="isModalVisible" @close="closeModal" />
    </div>
</template>

<style></style>