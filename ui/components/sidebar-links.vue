<template>
  <ul>
    <li>
      <NuxtLink
        to="/tweets"
        class="font-bold text-lg mb-4 block"
        :class="{ underline: this.$nuxt.$route.path == '/tweets' }"
      >
        Home
      </NuxtLink>
    </li>
    <li>
      <NuxtLink
        to="/explore"
        class="font-bold text-lg mb-4 block"
        :class="{ underline: this.$nuxt.$route.path == '/explore' }"
      >
        Explore
      </NuxtLink>
    </li>
    <li>
      <NuxtLink
        :to="`/profiles/${$auth.user.username}`"
        class="font-bold text-lg mb-4 block"
        :class="{ underline: this.$nuxt.$route.path == '/profile' }"
      >
        Profile
      </NuxtLink>
    </li>
    <li>
      <NuxtLink to="/stories" class="font-bold text-lg mb-4 block">
        Stories
      </NuxtLink>
    </li>
    <li v-if="$auth.user">
      <button @click="$auth.logout()" class="font-bold text-lg mb-4 block">
        Log Out
      </button>
    </li>
    <li v-if="!$auth.user">
      <NuxtLink to="/login" class="font-bold text-lg mb-4 block">
        Log In
      </NuxtLink>
    </li>
  </ul>
</template>

<script>
export default {}
</script>

<style></style>
