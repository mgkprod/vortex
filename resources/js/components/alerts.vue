<template>
  <div v-if="$page.props.flash.success || $page.props.flash.warning || $page.props.flash.error" class="w-full mt-8">
    <div class="container flex flex-col px-8 mx-auto space-y-4">
      <div v-if="$page.props.flash.success" class="p-4 text-sm rounded-md text-[#205237] bg-[#dcf5e7] border-[#cbf0dc] border"><i class="mr-1 opacity-50 fas fa-check text-[#50cd89]"></i> {{ $page.props.flash.success }}</div>
      <div v-if="$page.props.flash.warning" class="p-4 text-sm rounded-md text-[#665000] bg-[#fff4cc] border-[#ffeeb3] border"><i class="mr-1 opacity-50 fas fas-exclamation-triangle text-[#ffc700]"></i> {{ $page.props.flash.warning }}</div>
      <div v-if="$page.props.flash.error" class="p-4 text-sm rounded-md text-[#912741] bg-[#fcd9e2] border-[#fbc6d3] border"><i class="mr-1 opacity-50 fas fa-times text-[#f1416c]"></i> {{ $page.props.flash.error }}</div>
    </div>
  </div>
</template>

<script>
export default {
  //
};
</script>
