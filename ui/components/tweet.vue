<template>
  <div class="flex p-4 border-b border-b-gray-400">
    <div class="mr-2 flex-shrink-0">
      <NuxtLink :to="`/profiles/${tweet.user.username}`">
        <img
          :src="tweet.user.avatar"
          alt="user avatar"
          class="rounded-full mr-2"
          width="50"
          height="50"
        />
      </NuxtLink>
    </div>

    <div>
      <h5 class="font-bold mb-2">
        <NuxtLink :to="`/profiles/${tweet.user.username}`">
          {{ tweet.user.name }}
        </NuxtLink>
      </h5>

      <p class="text-sm mb-3">
        {{ tweet.body }}
      </p>

      <div class="flex">
        <div
          class="flex items-center mr-4"
          :class="tweet.is_liked ? 'text-blue-500' : 'text-gray-500'"
        >
          <svg
            viewBox="0 0 20 20"
            class="mr-1 w-3 cursor-pointer"
            @click="like"
          >
            <g
              id="Page-1"
              stroke="none"
              stroke-width="1"
              fill="none"
              fill-rule="evenodd"
              data-darkreader-inline-stroke=""
              style="--darkreader-inline-stroke: none"
            >
              <g class="fill-current">
                <path
                  d="M11.0010436,0 C9.89589787,0 9.00000024,0.886706352 9.0000002,1.99810135 L9,8 L1.9973917,8 C0.894262725,8 0,8.88772964 0,10 L0,12 L2.29663334,18.1243554 C2.68509206,19.1602453 3.90195042,20 5.00853025,20 L12.9914698,20 C14.1007504,20 15,19.1125667 15,18.000385 L15,10 L12,3 L12,0 L11.0010436,0 L11.0010436,0 Z M17,10 L20,10 L20,20 L17,20 L17,10 L17,10 Z"
                  id="Fill-97"
                ></path>
              </g>
            </g>
          </svg>
          <span class="text-xs">
            {{ tweet.likes ? tweet.likes : '0' }}
          </span>
        </div>

        <div
          class="flex items-center"
          :class="tweet.is_disliked ? 'text-blue-500' : 'text-gray-500'"
        >
          <svg
            viewBox="0 0 20 20"
            class="mr-1 w-3 cursor-pointer"
            @click="dislike"
          >
            <g
              id="Page-1"
              stroke="none"
              stroke-width="1"
              fill="none"
              fill-rule="evenodd"
              data-darkreader-inline-stroke=""
              style="--darkreader-inline-stroke: none"
            >
              <g class="fill-current">
                <path
                  d="M11.0010436,20 C9.89589787,20 9.00000024,19.1132936 9.0000002,18.0018986 L9,12 L1.9973917,12 C0.894262725,12 0,11.1122704 0,10 L0,8 L2.29663334,1.87564456 C2.68509206,0.839754676 3.90195042,8.52651283e-14 5.00853025,8.52651283e-14 L12.9914698,8.52651283e-14 C14.1007504,8.52651283e-14 15,0.88743329 15,1.99961498 L15,10 L12,17 L12,20 L11.0010436,20 L11.0010436,20 Z M17,10 L20,10 L20,0 L17,0 L17,10 L17,10 Z"
                  id="Fill-97"
                ></path>
              </g>
            </g>
          </svg>
          <span class="text-xs">
            {{ tweet.dislikes ? tweet.dislikes : '0' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'

import unlikeTweet from '@/graphql/mutations/UnlikeTweet.gql'
import likeTweet from '@/graphql/mutations/LikeTweet.gql'
import dislikeTweet from '@/graphql/mutations/DislikeTweet.gql'

export default {
  props: {
    _tweet: Object,
  },
  data() {
    return {
      tweet: this._tweet,
    }
  },
  methods: {
    updateLike: _.debounce(async function (mutation) {
      try {
        await this.$apollo.mutate({
          mutation,
          variables: {
            tweet_id: this.tweet.id,
            user_id: this.$auth.user.id,
          },
        })
      } catch (e) {
        console.log(e)
      }
    }, 300),
    async like() {
      var mutation

      if (this.tweet.is_liked) {
        this.tweet.likes--
        this.tweet.is_liked = null
        mutation = unlikeTweet
      } else {
        this.tweet.likes++
        this.tweet.is_liked = true
        mutation = likeTweet
      }

      if (this.tweet.is_disliked) {
        this.tweet.dislikes--
        this.tweet.is_disliked = null
      }

      this.updateLike(mutation)
    },
    async dislike() {
      var mutation

      if (this.tweet.is_disliked) {
        this.tweet.dislikes--
        this.tweet.is_disliked = null
        mutation = unlikeTweet
      } else {
        this.tweet.dislikes++
        this.tweet.is_disliked = true
        mutation = dislikeTweet
      }

      if (this.tweet.is_liked) {
        this.tweet.likes--
        this.tweet.is_liked = null
      }

      this.updateLike(mutation)
    },
  },
}
</script>

<style></style>
