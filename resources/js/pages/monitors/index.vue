<template>
  <div>
    <div class="flex items-center">
      <div>
        <div class="font-bold">Monitors</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li class="text-[#a1a5b7]"><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('monitors.index')">Monitors</inertia-link></li>
        </ol>
      </div>
      <div class="ml-auto">
        <inertia-link :href="route('monitors.create')" class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out bg-[#0194F6] hover:bg-[#0095e8] text-white"><i class="mr-1 opacity-50 fa fa-plus"></i> New monitor</inertia-link>
      </div>
    </div>

    <div class="mt-8 bg-white rounded-md">
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-[#FAFBFC]">
            <th class="px-4 py-3 text-left rounded-tl-md">Name</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Uptime</th>
            <th class="px-4 py-3 text-left">Response</th>
            <th class="px-4 py-3 text-left">Lastest check</th>
            <th class="px-4 py-3 rounded-tr-md"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="monitor in monitors" v-bind:key="monitor.id" class="border-t border-[#eff2f5]">
            <td class="px-4 py-3">{{ monitor.name }}</td>
            <td class="px-4 py-3">
              <div v-if="monitor.latest_heartbeat.status == 0" class="px-2 py-1 text-xs inline font-bold rounded-md bg-[#fff5f8] text-[#f1416c]">ERR</div>
              <div v-if="monitor.latest_heartbeat.status == 1" class="px-2 py-1 text-xs inline font-bold rounded-md bg-[#e8fff3] text-[#50cd89]">OK</div>
            </td>
            <td class="px-4 py-3">
              <div v-if="monitor.uptime <= 90" class="px-2 py-1 text-xs inline font-bold rounded-md bg-[#fff5f8] text-[#f1416c]">{{ monitor.uptime }}%</div>
              <div v-else-if="monitor.uptime <= 98" class="px-2 py-1 text-xs inline font-bold rounded-md bg-[#fff8dd] text-[#ffc700]">{{ monitor.uptime }}%</div>
              <div v-else class="px-2 py-1 text-xs inline font-bold rounded-md bg-[#e8fff3] text-[#50cd89]">{{ monitor.uptime }}%</div>
            </td>
            <td class="px-4 py-3">{{ Math.round(monitor.latest_heartbeat.response_time * 100) / 100 }}s</td>
            <td class="px-4 py-3">{{ moment(monitor.latest_heartbeat.created_at).fromNow() }}</td>
            <td class="px-4 py-3 text-right">
              <inertia-link class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out text-[#a1a5b7] bg-[#f5f8fa] hover:text-[#0194F6]" :href="route('monitors.edit', monitor)"> <i class="fa fa-edit"></i> </inertia-link>
              <inertia-link class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out text-[#a1a5b7] bg-[#f5f8fa] hover:text-[#0194F6]" :href="route('monitors.delete', monitor)"> <i class="fa fa-trash"></i> </inertia-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  layout: require('../../layouts/app').default,

  props: ['monitors'],
};
</script>
