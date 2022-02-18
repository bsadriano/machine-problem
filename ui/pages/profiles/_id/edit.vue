<template>
  <div v-if="$apollo.loading">Loading...</div>
  <div v-else>
    <form @submit.prevent="submit" enctype="multipart/form-data">
      <div class="mb-6">
        <label
          class="block mb-2 uppercase font-bold text-xs text-gray-700"
          for="name"
        >
          Name
        </label>

        <input
          class="border border-gray-400 p-2 w-full"
          type="text"
          name="name"
          id="name"
          v-model="name"
          required
        />

        <errors v-if="errors.name" :errors="errors.name" />
      </div>

      <div class="mb-6">
        <label
          class="block mb-2 uppercase font-bold text-xs text-gray-700"
          for="username"
        >
          Username
        </label>

        <input
          class="border border-gray-400 p-2 w-full"
          type="text"
          name="username"
          id="username"
          v-model="username"
          required
        />

        <errors v-if="errors.username" :errors="errors.username" />
      </div>

      <div class="mb-6">
        <label
          class="block mb-2 uppercase font-bold text-xs text-gray-700"
          for="avatar"
        >
          Avatar
        </label>

        <div class="flex">
          <input
            class="border border-gray-400 p-2 w-full"
            type="file"
            name="avatar"
            id="avatar"
            accept="image/*"
            @change="onFileChange"
          />

          <img :src="avatar" alt="your avatar" width="40" />
        </div>

        <errors v-if="errors.avatar" :errors="errors.avatar" />
      </div>

      <div class="mb-6">
        <label
          class="block mb-2 uppercase font-bold text-xs text-gray-700"
          for="avatar"
        >
          Email
        </label>

        <input
          class="border border-gray-400 p-2 w-full"
          type="email"
          name="email"
          id="email"
          v-model="email"
          required
        />

        <errors v-if="errors.email" :errors="errors.email" />
      </div>

      <div class="mb-6">
        <label
          class="block mb-2 uppercase font-bold text-xs text-gray-700"
          for="password"
        >
          Password
        </label>

        <input
          class="border border-gray-400 p-2 w-full"
          type="password"
          name="password"
          id="password"
          v-model="password"
        />

        <errors v-if="errors.password" :errors="errors.password" />
      </div>

      <div class="mb-6">
        <label
          class="block mb-2 uppercase font-bold text-xs text-gray-700"
          for="password_confirmation"
        >
          Password Confirmation
        </label>

        <input
          class="border border-gray-400 p-2 w-full"
          type="password"
          name="password_confirmation"
          id="password_confirmation"
          v-model="password_confirmation"
        />

        <errors
          v-if="errors.password_confirmation"
          :errors="errors.password_confirmation"
        />
      </div>

      <div class="mb-6">
        <button
          type="submit"
          class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500 mr-4"
        >
          Submit
        </button>

        <!-- <a href="{{ userProfile.user.path() }}" class="hover:underline">Cancel</a> -->
        <a href="#" class="hover:underline">Cancel</a>
      </div>
    </form>
  </div>
</template>

<script>
import errors from '@/components/_errors'

import userProfileQuery from '@/graphql/queries/UserProfile'
import updateUser from '@/graphql/mutations/UpdateUser'

export default {
  layout: 'twitter',
  components: {
    errors,
  },
  data() {
    return {
      id: '',
      name: '',
      username: '',
      avatar: '',
      originalAvatar: '',
      avatarFile: null,
      email: '',
      password: '',
      password_confirmation: '',
      errors: {
        name: '',
        username: '',
        avatar: '',
        password: '',
        password_confirmation: '',
      },
    }
  },
  apollo: {
    userProfile: {
      query: userProfileQuery,
      variables() {
        return {
          auth_user_id: this.$auth.user.id,
          username: this.$route.params.id,
        }
      },
      result({
        data: {
          userProfile: { user },
        },
        loading,
        networkStatus,
      }) {
        this.id = user.id
        this.name = user.name
        this.avatar = user.avatar
        this.originalAvatar = user.avatar
        this.email = user.email
        this.username = user.username
      },
    },
  },
  methods: {
    async submit() {
      try {
        await this.$apollo.mutate({
          mutation: updateUser,
          variables: {
            id: this.id,
            name: this.name,
            username: this.username,
            avatar: this.avatarFile,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          },
        })

        this.$router.push(`/profiles/${this.username}`)
      } catch (error) {
        let { graphQLErrors } = error

        if (graphQLErrors[0].extensions.category === 'validation') {
          this.errors = graphQLErrors[0].extensions.validation
        }
      }
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files
      if (!files.length) {
        this.avatarFile = null
        this.avatar = this.originalAvatar
        return
      }

      this.avatarFile = files[0]

      var reader = new FileReader()

      reader.onload = e => {
        this.avatar = e.target.result
      }

      reader.readAsDataURL(this.avatarFile)
    },
  },
}
</script>

<style></style>
