<script>
import axios from 'axios';
export default {
  name: 'ExpensesModal',
  props: ['action', 'id', 'expenseCategories'],
  data() {
    return {
      expenseCategory: {},
      loading: false,
    }
  },
  watch: {
    id: {
      immediate: true,
      handler(newVal, oldVal) {
        if (this.action == 'update') {
          this.showExpenseCategory(this.id);
        } else {
          this.expenseCategory = {};
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
      await axios.post('/api/expense-category', this.expenseCategory).then(response => {
        this.close();
        this.loading = false;
        this.expenseCategories.push(response.data.expenseCategory);
      }).catch(error => {
        this.close();
        this.loading = false;
        console.log(error);
      });
    },
    async showExpenseCategory(id) {
      this.loading = true;
      await axios.get('api/expense-category/'.concat(id)).then(response => {
        const { display_name, description } = response.data;
        this.expenseCategory.display_name = display_name;
        this.expenseCategory.description = description;
        this.loading = false;
      }).catch(error => {
        this.loading = false;
        console.log(error);
      });
    },
    async update() {
      this.loading = true;
      this.expenseCategory.id = this.id;
      await axios.put('api/expense-category/'.concat(this.id), this.expenseCategory).then(response => {

        this.expenseCategories.every((expenseCategory, index) => {
          if (expenseCategory.id === this.expenseCategory.id) {
            this.expenseCategory.created_at = expenseCategory.created_at;
            this.expenseCategories[index] = this.expenseCategory;
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
    async deleteExpenseCategory() {
      if (confirm('Are you sure you want to delete this expense category?')) {
        this.loading = true;
        await axios.delete('/api/expense-category/'.concat(this.id)).then(response => {

          this.expenseCategories.every((expenseCategory, index) => {
            if (expenseCategory.id === this.id) {
              this.expenseCategories.splice(index, 1);
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
            {{ action == "add" ? 'Add' : 'Update' }} Category
          </slot>
        </header>

        <section class="modal-body" id="modalDescription">
          <slot name="body">
            <div v-if="loading == true && action == 'update'" class="w-[317px] text-center">
              <i class="text-xs">Loading data...</i>
            </div>
            <form class="relative" v-else @submit.prevent="formAction">
              <div v-if="loading == true" class="absolute top-0 left-0 w-full h-full bg-white opacity-60"></div>
              <div class="flex gap-5 mb-2">
                <p class="mr-auto">Display name</p>
                <input required type="text" v-model="expenseCategory.display_name" class="border rounded align-sub" />
              </div>
              <div class="flex gap-5 mb-2">
                <p class="mr-auto">Description</p>
                <input required type="text" v-model="expenseCategory.description" class="border rounded" />
              </div>
              <footer class="modal-footer">
                <button v-if="action == 'update'" type="button"
                  class="bg-red-600 text-white mr-auto px-2 py-1 rounded hover:bg-red-500" @click="deleteExpenseCategory"
                  aria-label="Close modal">
                  Delete
                </button>
                <button type="button"
                  class="border border-cyan-600 px-2 py-1 rounded hover:bg-cyan-950  hover:text-white  hover:border-cyan-950"
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