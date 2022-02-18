<template>
  <div class="border border-blue-400 rounded-lg px-8 py-6 mb-8">
    <form @submit.prevent="submit">
      <textarea
        name="body"
        class="w-full"
        placeholder="Write something"
        required
        autofocus
        v-model="form.body"
      >
      </textarea>

      <hr class="my-4" />

      <footer class="flex justify-between items-center">
        <img
          :src="$auth.user.avatar"
          alt="your avatar"
          class="rounded-full mr-2"
          width="50"
          height="50"
        />

        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-600 rounded-lg shadow px-10 text-sm text-white h-10"
        >
          Publish
        </button>
      </footer>
    </form>

    <p v-if="false" class="text-red-500 text-sm mt-2">errors</p>
  </div>
</template>

<script>
import createTweet from '@/graphql/mutations/CreateTweet.gql'

export default {
  data() {
    return {
      form: {
        body: '',
      },
    }
  },
  computed: {
    avatar() {
      return ''
    },
  },
  methods: {
    async submit() {
      try {
        await this.$apollo.mutate({
          mutation: createTweet,
          variables: {
            user_id: this.$auth.user.id,
            body: this.form.body,
          },
        })

        this.$nuxt.$emit('refresh-tweets')
        this.form.body = ''
      } catch (e) {
        console.log(e)
      }
    },
  },
}
</script>

<style></style>
