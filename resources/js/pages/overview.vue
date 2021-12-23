<template>
  <div>
    <div class="flex items-center">
      <div>
        <div class="font-bold">Overview</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li>Home</li>
          <li>&bullet;</li>
          <li class="text-[#a1a5b7]">Overview</li>
        </ol>
      </div>
    </div>

    <div class="flex flex-row space-x-8">
      <div class="w-1/2 p-6 mt-8 text-white bg-[#50cd89] rounded-md">
        <div class="font-bold">{{ monitorStatuses[1] ? monitorStatuses[1].length : 0 }}</div>
        monitors are up
      </div>
      <div class="w-1/2 p-6 mt-8 text-white bg-[#f1416c] rounded-md">
        <div class="font-bold">{{ monitorStatuses[0] ? monitorStatuses[0].length : 0 }}</div>
        monitors are down
      </div>
    </div>

    <div class="p-6 mt-8 bg-white rounded-md">
      <div class="flex flex-col space-y-4">
        <div class="font-bold">Recent events</div>

        <div v-for="notification in notifications" v-bind:key="notification.id" class="flex flex-row items-center space-x-4">
          <div class="text-xs text-right flex-grow-0 flex-shrink-0 w-[120px]">{{ moment(notification.created_at).format('DD-MM-YYYY') }} {{ moment(notification.created_at).format('hh:mm') }}</div>
          <div class="flex-grow-0 flex-shrink-0">
            <i class="text-xs fas fa-circle" :class="{ 'text-[#f1416c]': notification.data.status == 0, 'text-[#50cd89]': notification.data.status == 1 }"></i>
          </div>
          <div>{{ notification.data.text }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: require('../layouts/app').default,

  props: ['monitorStatuses', 'downMonitors', 'notifications'],
};
</script>
