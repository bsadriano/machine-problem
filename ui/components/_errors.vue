<template>
  <div>
    <p v-for="(error, i) in errors" class="text-red-500 text-xs mt-2" :key="i">
      {{ error }}
    </p>
  </div>
</template>

<script>
export default {
  props: ['errors'],
}
</script>

<style></style>
