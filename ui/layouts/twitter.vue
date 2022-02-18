<template>
  <div>
    <section class="px-8 py-4 mb-6">
      <header class="container mx-auto">
        <h1 class="text-3xl font-bold">
          <a href="/tweets"> Tweets </a>
        </h1>
      </header>
    </section>

    <section class="px-8">
      <main class="container mx-auto">
        <div class="lg:flex lg:justify-center">
          <div class="lg:w-32">
            <SidebarLinks />
          </div>

          <div class="lg:flex-1 lg:mx-10 lg:mb-10" style="max-width: 700px">
            <Nuxt />
          </div>

          <div class="lg:w-1/6">
            <FriendsList />
          </div>
        </div>
      </main>
    </section>
  </div>
</template>

<script>
import SidebarLinks from '@/components/sidebar-links'
import FriendsList from '@/components/friends-list'

export default {
  components: {
    SidebarLinks,
    FriendsList,
  },
}
</script>

<style></style>
