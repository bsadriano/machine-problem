<template>
  <div class="h-screen lg:flex lg:justify-center">
    <div
      class="lg:w-1/6 p-3 bg-gray-800 text-white"
      style="flex: 0 0 calc(380px)"
    >
      <div class="text-2xl">Stories</div>

      <div class="mt-3">
        <NuxtLink class="text-blue-400" to="/stories/archived">
          Archive
        </NuxtLink>
      </div>

      <div class="text-lg mt-4">Your Story</div>

      <NuxtLink class="flex py-2 px-4 items-center" to="/stories/create">
        <img
          class="rounded-full w-14 h-14"
          src="https://randomuser.me/api/portraits/women/85.jpg"
        />
        <div class="flex flex-col ml-2">
          <div>Create a Story</div>
          <span class="text-gray-400"> Write something. </span>
        </div>
      </NuxtLink>

      <div class="list subheader two-line">
        <div class="text-lg">All Stories</div>
        <div v-if="$apollo.loading">Loading...</div>
        <div
          v-else-if="stories.length > 0"
          v-for="(story, i) in stories"
          :key="i"
          @click="showStories(i)"
          class="flex py-2 px-4 items-center cursor-pointer hover:bg-gray-700"
          :class="{ 'bg-gray-700': i == currentStoryIndex }"
        >
          <img
            class="rounded-full w-14 h-14"
            src="https://randomuser.me/api/portraits/women/85.jpg"
          />
          <div class="flex flex-col ml-2">
            <div>{{ story.user }}</div>
            <span class="text-gray-400">
              <span
                class="text-blue-400"
                v-if="story.posts.filter(post => !post.seen).length > 0"
              >
                {{ story.posts.filter(post => !post.seen).length }} new &middot;
              </span>
              1h
            </span>
          </div>
        </div>
        <div v-else class="text-gray-400">No recent stories.</div>
      </div>
    </div>

    <div class="lg:flex-1 bg-black relative">
      <div class="flex h-full">
        <div
          class="flex-auto cursor-pointer flex justify-end items-center pr-5"
          @click.stop="previous"
        >
          <div>
            <button
              class="bg-gray-300 p-3 rounded-full hover:bg-gray-200"
              :class="{ invisible: !hasPreviousStory && !hasPreviousStoryPost }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7"
                />
              </svg>
            </button>
          </div>
        </div>
        <div
          class="flex-auto relative"
          :style="{
            'background-image': 'url(http://localhost:8000/images/bg-1.jpg)',
          }"
          style="
            background-repeat: no-repeat;
            background-size: cover;
            flex: 0 0 550px;
          "
        >
          <div class="h-full flex flex-col">
            <div class="px-3 flex gap-2 mt-2">
              <div
                v-for="i in currentStoryPosts.length"
                :key="`${currentStoryIndex}:${i}`"
                class="flex-auto bg-gray-800 p-0 flex justify-start"
                style="height: 4px"
              >
                <div
                  class="white-bar"
                  :class="{
                    'grow-animation': i - 1 == currentStoryPostIndex,
                    'paused-animation':
                      i - 1 == currentStoryPostIndex && isAnimationPaused,
                    'remove-animation': i - 1 < currentStoryPostIndex,
                  }"
                />
              </div>
            </div>
            <div
              class="flex-auto flex items-center justify-center mt-0 text-white"
            >
              <div>
                <span> {{ content }} </span>
              </div>
            </div>
            <div class="rounded-lg px-2">
              <input
                class="w-full mb-2"
                type="text"
                v-model="comment"
                placeholder="Reply..."
                @keyup.enter="commentOnStory"
              />
            </div>
          </div>

          <button
            class="mt-2 mr-2 p-6 absolute top-0 right-0 text-white"
            @click="toggleIsAnimationPaused"
          >
            <svg
              v-if="isAnimationPaused"
              viewBox="0 0 20 20"
              class="h-10 w-10"
              version="1.1"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
            >
              <g
                id="Page-1"
                stroke="none"
                stroke-width="1"
                fill="currentColor"
                fill-rule="evenodd"
                data-darkreader-inline-stroke=""
                style="--darkreader-inline-stroke: none"
              >
                <g id="icon-shape">
                  <polygon id="Rectangle-161" points="4 4 16 10 4 16"></polygon>
                </g>
              </g>
            </svg>
            <svg
              v-else
              viewBox="0 0 20 20"
              class="h-10 w-10"
              version="1.1"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
            >
              <g
                id="Page-1"
                stroke="none"
                stroke-width="1"
                fill="currentColor"
                fill-rule="evenodd"
                data-darkreader-inline-stroke=""
                style="--darkreader-inline-stroke: none"
              >
                <g id="icon-shape">
                  <path
                    d="M5,4 L8,4 L8,16 L5,16 L5,4 Z M12,4 L15,4 L15,16 L12,16 L12,4 Z"
                    id="Combined-Shape"
                  ></path>
                </g>
              </g>
            </svg>
          </button>
        </div>
        <div
          class="flex-auto cursor-pointer flex justify-start items-center"
          @click.stop="next"
        >
          <div>
            <button
              class="bg-gray-300 p-3 rounded-full hover:bg-gray-200 ml-5"
              :class="{ invisible: !hasNextStory && !hasNextStoryPost }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <NuxtLink
        class="p-3 mr-5 mt-2 rounded-full absolute top-0 right-0 text-white"
        to="/tweets"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </NuxtLink>
    </div>
  </div>
</template>

<script>
import storiesQuery from '@/graphql/queries/Stories'
import seeStory from '@/graphql/mutations/SeeStory'
import commentOnStory from '@/graphql/mutations/CommentOnStory'

export default {
  name: 'IndexPage',
  data() {
    return {
      isAnimationPaused: true,
      stories: [],
      right: true,
      title: 'Vuetify.js',
      content: 'Content',
      currentStoryIndex: -1,
      nextPostTimeout: null,
      interval: 0,
      start: null,
      delay: null,
      comment: '',
    }
  },
  computed: {
    currentStory() {
      return this.currentStoryIndex >= 0
        ? this.stories[this.currentStoryIndex]
        : null
    },
    currentStoryPostIndex: {
      cache: false,
      get() {
        return this.currentStory ? this.currentStory.currentPostIndex : -1
      },
    },
    currentStoryPosts() {
      return this.currentStory ? this.currentStory.posts : []
    },
    currentStoryPost() {
      return this.currentStoryPosts[this.currentStoryPostIndex]
    },
    hasPreviousStory() {
      return this.currentStoryIndex >= 1
    },
    hasPreviousStoryPost() {
      return this.currentStoryPostIndex >= 1
    },
    hasNextStory() {
      return this.currentStoryIndex < this.stories.length - 1
    },
    hasNextStoryPost() {
      return this.currentStoryPostIndex < this.currentStoryPosts.length - 1
    },
    unseenPosts() {
      return this.currentStoryPosts.filter(post => !post.seen)
    },
  },
  methods: {
    async showStories(index) {
      if (this.currentStoryIndex != index) {
        this.currentStoryIndex = index
        this.content = this.currentStoryPost.text
        this.stories[this.currentStoryIndex].posts[
          this.currentStoryPostIndex
        ].seen = true
        await this.viewStory()
        this.setNextPostTimeout()
      }
    },
    setNextPostTimeout() {
      this.isAnimationPaused = false
      this.delay = 1000 * 3
      this.start = new Date()
      clearTimeout(this.nextPostTimeout)
      this.nextPostTimeout = setTimeout(() => {
        if (this.hasNextStory || this.hasNextStoryPost) {
          this.next()
        }
      }, this.delay)
    },
    previous() {
      if (!this.hasPreviousStoryPost && !this.hasPreviousStory) {
        return
      }

      if (!this.hasPreviousStoryPost) {
        if (this.hasPreviousStory) {
          this.currentStoryIndex--
        }
      } else {
        this.stories[this.currentStoryIndex].currentPostIndex--
      }

      this.content = this.currentStoryPost.text
      this.stories[this.currentStoryIndex].posts[
        this.currentStoryPostIndex
      ].seen = true

      this.viewStory()
      this.setNextPostTimeout()
    },
    next() {
      if (!this.hasNextStoryPost && !this.hasNextStory) {
        return
      }

      if (!this.hasNextStoryPost) {
        if (this.hasNextStory) {
          this.currentStoryIndex++
        }
      } else {
        this.stories[this.currentStoryIndex].currentPostIndex++
      }

      this.content = this.currentStoryPost.text
      this.stories[this.currentStoryIndex].posts[
        this.currentStoryPostIndex
      ].seen = true

      this.viewStory()
      this.setNextPostTimeout()
    },
    toggleIsAnimationPaused() {
      this.isAnimationPaused = !this.isAnimationPaused

      if (this.isAnimationPaused) {
        clearTimeout(this.nextPostTimeout)
        this.delay -= new Date() - this.start
      } else {
        this.start = new Date()
        this.nextPostTimeout = setTimeout(() => {
          if (this.hasNextStory || this.hasNextStoryPost) {
            this.next()
          }
        }, this.delay)
      }
    },
    createStory() {
      console.log('createstory')
    },
    async viewStory() {
      // TODO: prevent apollo from fetching data after creating new record in views table
      // await this.$apollo.mutate({
      //   mutation: seeStory,
      //   variables: {
      //     story_id: this.currentStoryPost.story_id,
      //     user_id: this.$auth.user.id,
      //   },
      // })
    },
    async commentOnStory() {
      await this.$apollo.mutate({
        mutation: commentOnStory,
        variables: {
          story_id: this.currentStoryPost.story_id,
          user_id: this.$auth.user.id,
          body: this.comment,
        },
      })

      this.comment = ''
    },
  },
  mounted() {
    // this.setNextPostTimeout()
  },
  apollo: {
    stories: {
      query: storiesQuery,
      pollInterval: 0,
      variables() {
        return {
          auth_user_id: this.$auth.user.id,
        }
      },
      result({ data, loading, networkStatus }) {
        const { stories } = data

        if (stories.length > 0) {
          this.stories = stories.map(story => ({
            ...story,
            currentPostIndex: 0,
          }))
        }
      },
    },
  },
}
</script>

<style>
.previous:hover,
.next:hover {
  cursor: pointer;
}

.previous:hover .button,
.next:hover .button {
  background: #555 !important;
}

.white-bar {
  background: white;
  height: 4px;
}

@keyframes fill {
  0% {
    transform: scaleX(0);
  }

  100% {
    transform: scaleX(1);
  }
}

.grow-animation {
  width: 100%;
  animation: fill 3s;
  transform-origin: left center;
}

.remove-animation {
  width: 100%;
  animation: none !important;
}

.paused-animation {
  animation-play-state: paused;
}

.content {
  height: 100%;
}
</style>
