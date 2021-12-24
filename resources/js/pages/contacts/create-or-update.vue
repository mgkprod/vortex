<template>
  <div>
    <div class="flex items-center">
      <div v-if="!contact">
        <div class="font-bold">Create a new contact</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('contacts.index')">Contacts</inertia-link></li>
          <li>&bullet;</li>
          <li class="text-[#a1a5b7]">Create</li>
        </ol>
      </div>
      <div v-else>
        <div class="font-bold">Update {{ contact.name }}</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('contacts.index')">Contacts</inertia-link></li>
          <li>&bullet;</li>
          <li>
            <inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('contacts.show', contact)">{{ contact.name }}</inertia-link>
          </li>
          <li>&bullet;</li>
          <li class="text-[#a1a5b7]">Update</li>
        </ol>
      </div>
    </div>

    <div class="mt-8 bg-white rounded-md">
      <form @submit.prevent="submit">
        <div class="flex flex-col p-6 space-y-4">
          <form-input :flex="true" :required="true" label="Name" type="text" placeholder="My cool contact" v-model="form.name" :error="form.errors.name" autofocus />
          <form-select :flex="true" :required="true" label="Type" v-model="form.type" :error="form.errors.type" :options="$page.props.types" />
          <div class="flex flex-col space-y-4">
            <template v-if="form.type == 4">
              <form-input :flex="true" :required="true" label="Discord Webhook URL" type="text" placeholder="https://discord.com/api/webhooks/[...]" v-model="form.discordWebhook" :error="form.errors.discordWebhook" />
            </template>
          </div>
        </div>
        <hr class="w-full border-[#f3f6f9]" />
        <div class="flex justify-end p-6">
          <button class="inline-block px-4 py-3 font-bold text-sm transition duration-200 rounded-md ease-in-out bg-[#0194F6] hover:bg-[#0095e8] text-white focus:outline-none disabled:opacity-60" :disabled="form.processing"><i class="mr-1 opacity-50 fa fa-save"></i> Save</button>
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
      form: this.$inertia.form({
        name: '',
        type: '',

        // Configuration (mixed)
        discordWebhook: '',
      }),
    };
  },

  mounted() {
    if (this.contact) {
      this.form.name = this.contact.name;
      this.form.type = this.contact.type;

      this.form.discordWebhook = this.contact.configuration.discord_webhook || '';
    }
  },

  methods: {
    submit() {
      this.form.clearErrors();

      if (this.contact) this.form.put(route('contacts.update', this.contact));
      else this.form.post(route('contacts.store'));
    },
  },
};
</script>
