<template>
  <div class="h-screen lg:flex lg:justify-center">
    <div
      class="lg:w-1/6 p-3 bg-gray-800 text-white flex flex-col justify-between"
      style="flex: 0 0 calc(380px)"
    >
      <div>
        <div class="text-lg mt-4">Your Story</div>

        <div class="flex py-2 px-4 items-center">
          <img
            class="rounded-full w-14 h-14"
            src="https://randomuser.me/api/portraits/women/85.jpg"
          />
          <div class="flex flex-col ml-2">
            <div>{{ $auth.user.name }}</div>
          </div>
        </div>

        <div class="mt-4">
          <textarea
            v-model="body"
            class="text-white bg-gray-800 w-full h-40"
            placeholder="Start typing"
          ></textarea>
        </div>
      </div>

      <div class="flex px-2 gap-2">
        <NuxtLink
          class="w-5/12 py-2 rounded-sm bg-gray-600 text-center hover:bg-gray-700"
          to="/stories"
          >Discard</NuxtLink
        >
        <button
          class="w-7/12 py-2 rounded-sm bg-blue-600 hover:bg-blue-700"
          @click="shareStory"
        >
          Share story
        </button>
      </div>
    </div>

    <div class="lg:flex-1 bg-black relative">
      <div class="flex h-full">
        <div class="flex-auto"></div>
        <div
          class="flex-auto"
          :style="{
            'background-image': 'url(http://localhost:8000/images/bg-1.jpg)',
          }"
          style="
            background-repeat: no-repeat;
            background-size: cover;
            flex: 0 0 550px;
          "
        >
          <div class="h-full flex flex-col mt-2">
            <div
              class="flex-auto flex items-center justify-center mt-0 text-white"
            >
              <div>
                <span> {{ body }} </span>
              </div>
            </div>
          </div>
        </div>
        <div class="flex-auto"></div>
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
import createStory from '@/graphql/mutations/CreateStory'

export default {
  name: 'IndexPage',
  data() {
    return {
      body: '',
    }
  },
  methods: {
    async shareStory() {
      await this.$apollo.mutate({
        mutation: createStory,
        variables: {
          user_id: this.$auth.user.id,
          body: this.body,
        },
      })

      this.$router.push('/stories')
    },
  },
}
</script>

<style>
.content {
  height: 100%;
}
</style>
