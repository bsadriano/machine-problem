<template>
  <button
    @click="follow"
    class="bg-blue-500 rounded-full shadow py-2 px-4 text-white text-xs"
  >
    {{ isFollowing ? 'Unfollow Me' : 'Follow Me' }}
  </button>
</template>

<script>
import _ from 'lodash'

import follow from '@/graphql/mutations/Follow'

export default {
  props: {
    _user: Object,
    _isFollowing: Boolean,
  },
  data() {
    return {
      user: this._user,
      isFollowing: this._isFollowing,
    }
  },
  methods: {
    updateFollow: _.debounce(async function () {
      try {
        await this.$apollo.mutate({
          mutation: follow,
          variables: {
            auth_user_id: this.$auth.user.id,
            username: this.$route.params.id,
            is_following: this.isFollowing,
          },
        })
      } catch (e) {
        console.log(e)
      }
    }, 300),
    follow() {
      this.isFollowing = !this.isFollowing

      this.updateFollow()
    },
  },
}
</script>

<style></style>
