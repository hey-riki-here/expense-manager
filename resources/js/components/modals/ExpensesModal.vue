<script>
import axios from 'axios';
export default {
    name: 'ExpensesModal',
    props: ['action', 'id', 'expenses'],
    data() {
        return {
            expense: {},
            loading: false,
            expenseCategories: [],
            userData: JSON.parse(window.sessionStorage.getItem('user')),
        }
    },
    watch: {
        id: {
            immediate: true,
            async handler(newVal, oldVal) {
                if (this.action == 'update') {
                    this.showExpense(this.id);
                } else {
                    this.loading = true;
                    this.expense = {};
                    await this.getExpenseCategories();
                    this.loading = false;
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
            this.expense.created_by_user_with_id = this.userData.id;
            await axios.post('/api/expense', this.expense).then(response => {
                this.close();
                this.loading = false;
                this.expenses.push(response.data.expense);
            }).catch(error => {
                this.close();
                this.loading = false;
                console.log(error);
            });
        },
        async showExpense(id) {
            this.loading = true;
            await axios.get('api/expense/'.concat(id)).then(async response => {
                const { expense_category, amount, entry_date } = response.data;
                this.expense.expense_category = expense_category;
                this.expense.amount = amount;
                this.expense.entry_date = this.getFormattedDate(entry_date);
                await this.getExpenseCategories();
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                console.log(error);
            });
        },
        async update() {
            this.loading = true;
            this.expense.id = this.id;
            await axios.put('api/expense/'.concat(this.id), this.expense).then(response => {

                this.expenses.every((expense, index) => {
                    if (expense.id === this.expense.id) {
                        this.expense.created_at = expense.created_at;
                        this.expenses[index] = this.expense;
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
        async deleteExpense() {
            if (confirm('Are you sure you want to delete this expense?')) {
                this.loading = true;
                await axios.delete('/api/expense/'.concat(this.id)).then(response => {

                    this.expenses.every((expense, index) => {
                        if (expense.id === this.id) {
                            this.expenses.splice(index, 1);
                            return false;
                        } else {
                            return true;
                        }
                    });

                    this.close();
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    this.close();
                    console.log(error);
                });
            } else {
                this.close();
            }
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
            return dateString.split(' ')[0];
        }
    },
};
</script>

<template>
    <transition name="modal-fade">
        <div class="modal-backdrop">
            <div class="modal" role="dialog" aria-labelledby="modalTitle" aria-describedby="modalDescription">
                <header class="modal-header" id="modalTitle">
                    <slot name="header">
                        {{ action == "add" ? 'Add' : 'Update' }} Expense
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
                                <p class="shrink-0 mr-auto">Expense Category</p>
                                <select required v-model="expense.expense_category" class="border rounded w-full">
                                    <option v-for="(expenseCategory, key) in expenseCategories" :key="key"
                                        :value="expenseCategory.display_name">{{ expenseCategory.display_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex gap-5 mb-2">
                                <p class="mr-auto">Amount</p>
                                <input required type="number" v-model="expense.amount" class="border rounded align-sub" />
                            </div>
                            <div class="flex gap-5 mb-2">
                                <p class="shrink-0 mr-auto">Entry date</p>
                                <input required v-model="expense.entry_date" class="border rounded w-full" type="date">
                            </div>
                            <footer class="modal-footer">
                                <button v-if="action == 'update'" type="button"
                                    class="bg-red-600 text-white mr-auto px-2 py-1 rounded hover:bg-red-500"
                                    @click="deleteExpense" aria-label="Close modal">
                                    Delete
                                </button>
                                <button type="button"
                                    class="border border-cyan-600 px-2 py-1 rounded hover:bg-cyan-950 hover:text-white hover:border-cyan-950"
                                    @click="close" aria-label="Close modal">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-cyan-950 text-white px-2 py-1 rounded hover:bg-cyan-800"
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