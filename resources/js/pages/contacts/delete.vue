<template>
  <div>
    <div class="flex items-center">
      <div>
        <div class="font-bold">Delete {{ contact.name }}</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('contacts.index')">Contacts</inertia-link></li>
          <li>&bullet;</li>
          <li>
            <inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('contacts.show', contact)">{{ contact.name }}</inertia-link>
          </li>
          <li>&bullet;</li>
          <li class="text-[#a1a5b7]">Delete</li>
        </ol>
      </div>
    </div>

    <div class="mt-8 bg-white rounded-md">
      <form @submit.prevent="submit">
        <div class="flex flex-col p-6 space-y-4">
          <p>Are you sure you want to delete this contact ? All his history will also be removed.</p>
        </div>
        <hr class="w-full border-[#f3f6f9]" />
        <div class="flex justify-end p-6">
          <button class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out bg-[#f1416c] hover:bg-[#d9214e] text-white focus:outline-none disabled:opacity-60" :disabled="form.processing"><i class="mr-1 opacity-50 fa fa-trash"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  layout: require('../../layouts/app').default,

  props: ['contact', 'types'],

  data() {
    return {
      form: this.$inertia.form({}),
    };
  },

  methods: {
    submit() {
      this.form.clearErrors();

      this.form.delete(route('contacts.destroy', this.contact));
    },
  },
};
</script>
