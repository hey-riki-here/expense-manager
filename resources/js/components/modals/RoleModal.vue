<script>
import axios from 'axios';

export default {
  name: 'RoleModal',
  props: ['action', 'id', 'roles'],
  data() {
    return {
      role: {},
      prevRole: '',
      loading: false,
      roleExists: false,
    }
  },
  watch: {
    id: {
      immediate: true,
      handler(newVal, oldVal) {
        if (this.action == 'update') {
          this.showRole(this.id);
        } else {
          this.role = {};
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
      await axios.post('/api/role', this.role).then(response => {
        this.close();
        this.loading = false;
        this.roles.push(response.data.role);
      }).catch(error => {
        this.close();
        this.loading = false;
        console.log(error);
      });
    },
    async showRole(id) {
      this.loading = true;
      await axios.get('api/role/'.concat(id)).then(response => {
        const { display_name, description } = response.data;
        this.role.display_name = display_name;
        this.prevRole = display_name;
        this.role.description = description;
        this.loading = false;
      }).catch(error => {
        this.loading = false;
        console.log(error);
      });
    },
    async update() {
      let roleExists = false;
      for (const role of this.roles) {
        if (this.role.display_name === role.display_name) {
          roleExists = true;
          break;
        }
      }

      if (roleExists === false || this.role.display_name === this.prevRole) {
        this.roleExists = false;
        this.loading = true;
        this.role.id = this.id;
        await axios.put('api/role/'.concat(this.id), this.role).then(async response => {

          this.roles.every((role, index) => {
            if (role.id === this.role.id) {
              this.role.created_at = role.created_at;
              this.roles[index] = this.role;
              return false;
            } else {
              return true;
            }
          });

          await axios.get('/api/user').then(async response => {
            for (const user of response.data) {
              if (user.role === this.prevRole) {
                user.role = this.role.display_name;
                await axios.put('api/user/'.concat(user.id), user).then(response => {
                }).catch(error => {
                  console.log(error);
                });
              }
            }
          }).catch(error => {
            console.log(error);
          });

          this.close();
          this.loading = false;
        }).catch(error => {
          this.loading = false;
          this.close();
          console.log(error);
        });
      } else {
        this.roleExists = true;
      }

    },
    async deleteRole() {
      if (confirm('Are you sure you want to delete this role?')) {
        this.loading = true;
        await axios.delete('/api/role/'.concat(this.id)).then(response => {

          this.roles.every((role, index) => {
            if (role.id === this.id) {
              this.roles.splice(index, 1);
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
            {{ action == "add" ? 'Add' : 'Update' }} Role
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
                <div>
                  <input :readonly="prevRole === 'Administrator'" required type="text" v-model="role.display_name"
                    class="border rounded align-sub" />
                  <p v-if="roleExists" class="text-sm text-red-600">Role already exists!</p>
                </div>

              </div>
              <div class="flex gap-5 mb-2">
                <p class="mr-auto">Description</p>
                <input :readonly="prevRole === 'Administrator'" required type="text" v-model="role.description"
                  class="border rounded" />
              </div>
              <footer class="modal-footer">
                <button v-if="action == 'update' && prevRole !== 'Administrator'" type="button"
                  class="bg-red-600 text-white mr-auto px-2 py-1 rounded hover:bg-red-500" @click="deleteRole"
                  aria-label="Close modal">
                  Delete
                </button>
                <button type="button"
                  class="border border-cyan-600 px-2 py-1 rounded hover:bg-cyan-950  hover:text-white  hover:border-cyan-950"
                  @click="close" aria-label="Close modal">
                  Close
                </button>
                <button v-if="prevRole !== 'Administrator'" type="submit"
                  class="bg-cyan-950 text-white px-2 py-1 rounded hover:bg-cyan-800" aria-label="Close modal">
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