<template>
  <div class="flex flex-col min-h-screen">
    <nav>
      <div class="bg-[#1D1C29] text-[#ACACBB]">
        <div class="container px-8 py-8 mx-auto">
          <div class="flex items-center justify-center">
            <inertia-link :href="route('index')">
              <img src="/images/logo.svg" class="h-6" />
            </inertia-link>
          </div>
        </div>
      </div>
      <div class="bg-[#2B2A3A] text-[#ACACBB]">
        <div class="container px-8 py-4 mx-auto">
          <div class="flex items-center justify-between">
            <div class="flex flex-row items-center space-x-1">
              <inertia-link :href="route('overview')" :class="{ 'bg-[#3B3A4A]': route().current('overview*') }" class="inline-block px-4 py-2 text-sm font-bold transition duration-200 ease-in-out rounded-md hover:bg-[#3B3A4A]"> <i class="mr-1 opacity-50 fas fa-chart-line"></i> Overview </inertia-link>
              <inertia-link :href="route('monitors.index')" :class="{ 'bg-[#3B3A4A]': route().current('monitors*') }" class="inline-block px-4 py-2 text-sm font-bold transition duration-200 ease-in-out rounded-md hover:bg-[#3B3A4A]"> <i class="mr-1 opacity-50 fas fa-chart-line"></i> Monitors </inertia-link>
            </div>

            <button type="button" class="inline-block px-4 py-2 text-sm font-bold transition duration-200 ease-in-out rounded-md hover:bg-[#3B3A4A] focus:outline-none" @click="$inertia.post(route('logout'))"><i class="fas fa-sign-out-alt"></i></button>
          </div>
        </div>
      </div>
    </nav>

    <alerts class="mt-8"></alerts>

    <div class="container px-8 mx-auto mt-8">
      <slot />
    </div>

    <footer class="container flex flex-row justify-between w-full px-8 py-8 mx-auto mt-auto text-xs text-center text-[#979BAE]">
      <div>{{ $page.props.appVersion }}</div>
      <div>Made with ♥ by <a href="https://mgk.dev" class="transition duration-200 ease-in-out border-b border-current hover:opacity-80" target="_blank">Simon Rubuano</a> as <a href="https://github.com/mgkprod/Vortex" class="transition duration-200 ease-in-out border-b border-current hover:opacity-80" target="_blank">open-source software</a>.</div>
    </footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      open: false,
    };
  },

  methods: {
    toggle() {
      this.open = !this.open;
    },
  },
};
</script>
