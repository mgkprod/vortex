<template>
  <div class="flex flex-col items-center justify-between min-h-screen">
    <div></div>

    <div class="flex flex-col items-center w-full">
      <div class="px-4 py-3 mb-8">
        <inertia-link :href="route('index')">
          <img src="/images/logo_on_white.svg" class="h-10" />
        </inertia-link>
      </div>

      <slot />
    </div>

    <footer class="container flex flex-row justify-between w-full max-w-screen-xl px-8 py-4 mx-auto my-8 text-xs text-center text-[#979BAE]">
      <div>{{ $page.props.appVersion }}</div>
      <div>Made with ♥ by <a href="https://mgk.dev" class="transition duration-200 ease-in-out border-b border-current hover:opacity-80" target="_blank">Simon Rubuano</a> as <a href="https://github.com/mgkprod/Vortex" class="transition duration-200 ease-in-out border-b border-current hover:opacity-80" target="_blank">open-source software</a>.</div>
    </footer>
  </div>
</template>

<script>
export default {
  //
};
</script>
