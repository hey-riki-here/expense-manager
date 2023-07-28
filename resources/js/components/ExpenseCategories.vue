<script>
import ExpenseCategoriesModal from './modals/ExpenseCategoriesModal.vue';
import axios from 'axios';
export default {
    components: {
        ExpenseCategoriesModal,
    },
    data() {
        return {
            isModalVisible: false,
            action: '',
            expenseCategoryId: '',
            expenseCategories: [],
        };
    },
    mounted() {
        this.getExpenseCategories()
    },
    methods: {
        showModal(action, id) {
            this.isModalVisible = true;
            this.action = action;
            this.expenseCategoryId = id;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        async getExpenseCategories() {
            await axios.get('/api/expense-category').then(response => {
                this.expenseCategories = response.data
            }).catch(error => {
                console.log(error);
                this.expenseCategories = [];
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
            <p class="font-semibold">Expense Categories</p>
            <p class="font-semibold">Expense Management > Expense Categories</p>
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
                            <tbody v-if="expenseCategories.length > 0">
                                <tr v-for="(expenseCategory, key) in expenseCategories" :key="key"
                                    class="border-b bg-neutral-100 hover:cursor-pointer"
                                    @click="showModal('update', expenseCategory.id);">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ expenseCategory.display_name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ expenseCategory.description }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ getFormattedDate(expenseCategory.created_at) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3" align="center">No expense categories found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" @click="showModal('add')" class="bg-cyan-950 rounded px-2 py-1 mt-2 text-white hover:bg-cyan-800">Add
                Category</button>
        </div>
        <ExpenseCategoriesModal :action=action :id=expenseCategoryId :expenseCategories=expenseCategories v-show="isModalVisible" @close="closeModal" />
    </div>
</template>

<style></style>