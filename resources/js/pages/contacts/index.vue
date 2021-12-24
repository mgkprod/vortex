<template>
  <div>
    <div class="flex items-center">
      <div>
        <div class="font-bold">Contacts</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li class="text-[#a1a5b7]"><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('contacts.index')">Contacts</inertia-link></li>
        </ol>
      </div>
      <div class="ml-auto">
        <inertia-link :href="route('contacts.create')" class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out bg-[#0194F6] hover:bg-[#0095e8] text-white"><i class="mr-1 opacity-50 fa fa-plus"></i> New contact</inertia-link>
      </div>
    </div>

    <div class="mt-8 bg-white rounded-md">
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-[#FAFBFC]">
            <th class="px-4 py-3 text-left rounded-tl-md">Name</th>
            <th class="px-4 py-3 text-left">Type</th>
            <th class="px-4 py-3 text-left">Created</th>
            <th class="px-4 py-3 rounded-tr-md"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contact in contacts" v-bind:key="contact.id" class="border-t border-[#eff2f5]">
            <td class="px-4 py-3">{{ contact.name }}</td>
            <td class="px-4 py-3">{{ types[contact.type] }}</td>
            <td class="px-4 py-3">
              <abbr :title="moment(contact.created_at).format('DD-MM-YYYY hh:mm')" class="underline decoration-dotted">
                {{ moment(contact.created_at).fromNow() }}
              </abbr>
            </td>
            <td class="px-4 py-3 text-right">
              <inertia-link class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out text-[#a1a5b7] bg-[#f5f8fa] hover:text-[#0194F6]" :href="route('contacts.notify', contact)"> <i class="fa fa-play"></i> </inertia-link>
              <inertia-link class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out text-[#a1a5b7] bg-[#f5f8fa] hover:text-[#0194F6]" :href="route('contacts.edit', contact)"> <i class="fa fa-edit"></i> </inertia-link>
              <inertia-link class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out text-[#a1a5b7] bg-[#f5f8fa] hover:text-[#0194F6]" :href="route('contacts.delete', contact)"> <i class="fa fa-trash"></i> </inertia-link>
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

  props: ['contacts', 'types'],
};
</script>
