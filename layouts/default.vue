<template>
  <div class="flex flex-col min-h-screen">
    <SiteHeader />

    <main class="flex-grow mt-20">
      <Nuxt />
    </main>

    <footer>footer</footer>

    <PortalTarget name="mobile-menu" />
  </div>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    name: 'Default',
    computed: {
      ...mapState('global', 'isMobileMenuOpen'),
    },
  }
</script>

<style lang="scss" scoped></style>
