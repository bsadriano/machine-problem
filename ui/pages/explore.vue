<template>
  <div>
    <div v-if="$apollo.loading">Loading...</div>
    <div v-else-if="users">
      <NuxtLink
        v-for="user in users.data"
        :key="user.id"
        :to="`/profiles/${user.username}`"
        class="flex items-center mb-5"
      >
        <img
          :src="user.avatar"
          :alt="`${user.username}'s avatar`"
          width="60"
          class="mr-4 rounded"
        />

        <div>
          <h4 class="font-bold">{{ '@' + user.username }}</h4>
        </div>
      </NuxtLink>
    </div>
    <div v-else>No users found.</div>
  </div>
</template>

<script>
import usersQuery from '@/graphql/queries/Users'

const size = 50

export default {
  head: {
    title: 'Explore',
  },
  middleware: 'authenticated',
  layout: 'twitter',
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
}
</script>

<style></style>
