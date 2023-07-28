<script>
import Chart from 'chart.js/auto';
import axios from 'axios';

export default {
    data() {
        return {
            expenseCategories: [],
            expenses: [],
            data: {},
            hexColors: [],
            userData: JSON.parse(window.sessionStorage.getItem('user')),
        };
    },
    async mounted() {

        await this.getExpenseCategories();
        await this.getExpenses();

        this.expenseCategories.forEach(category => {
            this.data[category.display_name] = 0;
        });

        this.expenseCategories.forEach(category => {
            this.expenses.forEach(expense => {
                if (expense.expense_category == category.display_name) {
                    if (expense.expense_category in this.data) {
                        this.data[expense.expense_category] = parseFloat(this.data[expense.expense_category]) + parseFloat(expense.amount);
                    } else {
                        this.data[expense.expense_category] = parseFloat(expense.amount);
                    }
                }
            });
        });

        let chartData = [];
        for (const key in this.data) {
            chartData.push(this.data[key]);
        }

        this.hexColors = this.generateHexColors(chartData.length);
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                    label: '$',
                    data: chartData,
                    backgroundColor: this.hexColors,
                    hoverOffset: 4
                }]
            },
        });
    },
    methods: {
        async getExpenseCategories() {
            await axios.get('/api/expense-category').then(response => {
                this.expenseCategories = response.data
            }).catch(error => {
                console.log(error);
                this.expenseCategories = [];
            });
        },
        async getExpenses() {
            await axios.get('/api/expense').then(response => {
                if (this.userData.role !== 'Administrator') {
                    this.expenses = response.data.filter(expense => expense.created_by_user_with_id == this.userData.id);
                } else {
                    this.expenses = response.data;
                }
            }).catch(error => {
                console.log(error);
                this.expenses = [];
            });
        },
        generateHexColors(size) {
            let hexArr = [];

            for (let i = 0; i < size; i++) {
                let n = (Math.random() * 0xfffff * 1000000).toString(16);
                hexArr.push('#' + n.slice(0, 6));
            }

            return hexArr;
        },
    }
}
</script>

<template>
    <div class="p-8">
        <div class="flex justify-between">
            <p class="font-semibold">My Expenses</p>
            <p class="font-semibold">Dashboard</p>
        </div>
        <div v-if="expenseCategories.length > 0" class="flex justify-center gap-16 mt-12">
            <div>
                <p class="text-lg font-semibold">Expense Categories</p>
                <ul v-for="(category, index) in expenseCategories" :key="index">
                    <li>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2" :style="{ backgroundColor: hexColors[index] }"></div>
                            <p>{{ category.display_name }}</p>
                        </div>
                    </li>
                </ul>
                <!-- <p ></p> -->


            </div>
            <div>
                <p class="text-lg font-semibold">Total</p>
                <p v-for="(item, index) in data" :key="index">${{ item }}</p>
            </div>
            <div class="w-72 h-72">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div v-else class="text-center mt-5">
            <p>No data to show.</p>
        </div>
    </div>
</template>

<style></style>