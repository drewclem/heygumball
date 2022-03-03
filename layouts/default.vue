<template>
  <div class="flex flex-col min-h-screen">
    <SiteHeader />

    <main class="flex-grow mt-12 lg:mt-20">
      <Nuxt />
    </main>

    <footer class="bg-gray-100 p-6 border-t border-red-500">
      <div class="narrow-wrapper">
        <nuxt-link to="/" class="block w-32">
          <GumballLogo class="w-full" />
        </nuxt-link>
      </div>
    </footer>

    <PortalTarget name="mobile-menu" />
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import GumballLogo from '~/assets/Gumball-logo.svg?inline'

  export default {
    name: 'Default',
    components: {
      GumballLogo,
    },
    computed: {
      ...mapState('global', 'isMobileMenuOpen'),
    },
  }
</script>
