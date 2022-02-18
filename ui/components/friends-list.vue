<template>
  <div class="bg-gray-200 border border-gray-300 rounded-lg py-4 px-6">
    <h3 class="font-bold text-xl mb-4">Following</h3>

    <div v-if="$apollo.loading">Loading...</div>
    <div v-else-if="follows">
      <ul>
        <li v-for="user in follows" :key="user.id" class="mb-4">
          <div>
            <NuxtLink
              :to="`/profiles/${user.username}`"
              class="flex items-center text-sm"
            >
              <img
                :src="user.avatar"
                alt=""
                class="rounded-full mr-2"
                width="40"
                height="40"
              />
              {{ user.name }}
            </NuxtLink>
          </div>
        </li>
      </ul>
    </div>
    <div v-else>No friends yet!</div>
  </div>
</template>

<script>
import followsQuery from '@/graphql/queries/Follows.gql'

export default {
  data() {
    return {
      currentUser: {
        follows: [],
      },
    }
  },
  apollo: {
    follows: {
      query: followsQuery,
      variables() {
        return {
          id: this.$auth.user.id,
        }
      },
    },
  },
}
</script>

<style></style>
