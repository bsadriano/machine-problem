<template>
  <div>
    <div v-if="$apollo.loading">Loading...</div>
    <template v-else>
      <header class="mb-6 relative">
        <div class="relative">
          <img
            src="http://localhost:8000/images/default-profile-banner.jpg"
            alt=""
            class="mb-2"
          />

          <img
            :src="userProfile.user.avatar"
            alt=""
            class="rounded-full mr-2 absolute bottom-0 transform -translate-x-1/2 translate-y-1/2"
            style="left: 50%"
            width="150"
          />
        </div>

        <div class="flex justify-between items-center mb-6">
          <div style="max-width: 270px">
            <h2 class="font-bold text-2xl mb-0">
              {{ userProfile.user.name }}
            </h2>
            <p class="text-sm">Joined {{ userProfile.user.created_at }}</p>
          </div>

          <div class="flex">
            <NuxtLink
              v-if="userProfile.can_edit"
              :to="`/profiles/${userProfile.user.username}/edit`"
              class="rounded-full border border-gray-300 py-2 px-4 text-black text-xs mr-2"
            >
              Edit Profile
            </NuxtLink>

            <FollowButton
              v-if="userProfile.can_follow"
              :_user="userProfile.user"
              :_isFollowing="userProfile.is_following"
            />
          </div>
        </div>

        <p class="text-sm">
          The name’s Bugs. Bugs Bunny. Don’t wear it out. Bugs is an
          anthropomorphic gray and white rabbit or hare who is famous for his
          flippant, insouciant personality. He is also characterized by a
          Brooklyn accent, his portrayal as a trickster, and his catch phrase
          "Eh...What's up, doc?"
        </p>
      </header>

      <Timeline :tweets="timeline.data" />
    </template>
  </div>
</template>

<script>
import Timeline from '@/components/timeline'
import FollowButton from '@/components/follow-button'
import timelineQuery from '@/graphql/queries/Timeline'
import userProfileQuery from '@/graphql/queries/UserProfile'


export default {
  components: {
    Timeline,
    FollowButton,
  },
  layout: 'twitter',
  apollo: {
    timeline: {
      query: timelineQuery,
      variables() {
        return {
          auth_user_id: this.$auth.user.id,
          username: this.$route.params.id,
        }
      },
    },
    userProfile: {
      query: userProfileQuery,
      variables() {
        return {
          auth_user_id: this.$auth.user.id,
          username: this.$route.params.id,
        }
      },
    },
  },
}
</script>

<style></style>
