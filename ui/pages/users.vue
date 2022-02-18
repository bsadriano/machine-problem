<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-xl">Users</h1>

            <div v-if="$apollo.loading">Loading...</div>
            <div v-else-if="users">
              <ul>
                <li v-for="(user, i) of users.data" :key="i" class="user">
                  {{ user.name }}
                </li>
              </ul>
              <div class="flex">
                <div
                  class="mx-2"
                  @click="setPage(1)"
                  :class="
                    this.page == 1
                      ? 'text-gray-500 cursor-progress'
                      : 'cursor-pointer'
                  "
                >
                  First
                </div>
                <div
                  class="mx-2"
                  @click="previous"
                  :class="
                    this.page == 1
                      ? 'text-gray-500 cursor-progress'
                      : 'cursor-pointer'
                  "
                >
                  Previous
                </div>
                <div
                  class="mx-2 cursor-pointer"
                  :class="{
                    'font-bold': i == users.paginatorInfo.currentPage,
                  }"
                  v-for="i in users.paginatorInfo.lastPage"
                  :key="i"
                  @click="setPage(i)"
                >
                  {{ i }}
                </div>
                <div
                  class="mx-2"
                  @click="next"
                  :class="
                    this.page == users.paginatorInfo.lastPage
                      ? 'text-gray-500 cursor-progress'
                      : 'cursor-pointer'
                  "
                >
                  Next
                </div>
                <div
                  class="mx-2"
                  @click="setPage(users.paginatorInfo.lastPage)"
                  :class="
                    this.page == users.paginatorInfo.lastPage
                      ? 'text-gray-500 cursor-progress'
                      : 'cursor-pointer'
                  "
                >
                  Last
                </div>
              </div>
            </div>
            <div v-else>No users found.</div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import usersQuery from '@/graphql/queries/Users.gql'
import Layout from '@/layouts/authenticated.vue'

const size = 2

export default {
  head: {
    title: 'Welcome',
  },
  middleware: 'authenticated',
  components: {
    Layout,
  },
  data() {
    return {
      page: 1,
    }
  },
  apollo: {
    users: {
      query: usersQuery,
      variables() {
        return {
          first: size,
          page: this.page,
        }
      },
    },
  },
  methods: {
    previous() {
      if (this.page == 1) return

      this.page--
    },
    next() {
      if (this.page == this.users.paginatorInfo.lastPage) return

      this.page++
    },
    setPage(page) {
      this.page = page
    },
  },
}
</script>

<style></style>
