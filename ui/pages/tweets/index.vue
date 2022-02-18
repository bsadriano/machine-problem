<template>
  <div>
    <PublishTweetPanel />

    <div v-if="$apollo.loading">Loading...</div>
    <div v-else-if="timeline">
      <Timeline :tweets="timeline.data" />
    </div>
  </div>
</template>

<script>
import PublishTweetPanel from '@/components/publish-tweet-panel'
import Timeline from '@/components/timeline'

import timelineQuery from '@/graphql/queries/Timeline'

export default {
  head: {
    title: 'Tweets',
  },
  middleware: 'authenticated',
  components: {
    PublishTweetPanel,
    Timeline,
  },
  layout: 'twitter',
  apollo: {
    timeline: {
      query: timelineQuery,
      variables() {
        return {
          auth_user_id: this.$auth.user.id,
          username: this.$auth.user.username,
        }
      },
      fetchPolicy: 'no-cache',
    },
  },
  created() {
    this.$nuxt.$on('refresh-tweets', () => {
      this.$apollo.queries.timeline.refetch()
    })
  },
}
</script>

<style></style>
