<template>
  <div class="border border-gray-300 rounded-lg">
    <template v-if="tweets">
      <Tweet v-for="tweet in tweets" :key="tweet.id" :_tweet="tweet" />
    </template>
    <template v-else>
      <p class="p-4">No tweets yet.</p>
    </template>
  </div>
</template>

<script>
import Tweet from './tweet'

export default {
  props: {
    tweets: Array,
  },
  components: {
    Tweet,
  },
}
</script>

<style></style>
