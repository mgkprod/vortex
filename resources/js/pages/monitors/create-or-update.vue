<template>
  <div>
    <div class="flex items-center">
      <div v-if="!monitor">
        <div class="font-bold">Create a new monitor</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('overview')">Monitors</inertia-link></li>
          <li>&bullet;</li>
          <li class="text-[#a1a5b7]">Create</li>
        </ol>
      </div>
      <div v-else>
        <div class="font-bold">Update {{ monitor.name }}</div>
        <ol class="inline-flex text-sm flex-row items-center space-x-1 text-[#979BAE]">
          <li><inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('overview')">Monitors</inertia-link></li>
          <li>&bullet;</li>
          <li>
            <inertia-link class="transition duration-200 ease-in-out hover:opacity-80" :href="route('monitors.show', monitor)">{{ monitor.name }}</inertia-link>
          </li>
          <li>&bullet;</li>
          <li class="text-[#a1a5b7]">Update</li>
        </ol>
      </div>
    </div>

    <div class="mt-8 bg-white rounded-md">
      <form @submit.prevent="submit">
        <div class="flex flex-col p-6 space-y-4">
          <form-input :flex="true" :required="true" label="Name" type="text" placeholder="My cool monitor" v-model="form.name" :error="form.errors.name" autofocus />
          <form-select :flex="true" :required="true" label="Type" v-model="form.type" :error="form.errors.type" :options="$page.props.types" />
          <div class="flex flex-col space-y-4">
            <template v-if="form.type == 1">
              <form-input :flex="true" :required="true" label="URL (or IP)" type="text" placeholder="https://mgk.dev" v-model="form.host" :error="form.errors.host" />
            </template>
            <template v-if="form.type == 2">
              <form-input :flex="true" :required="true" label="URL (or IP)" type="text" placeholder="https://mgk.dev" v-model="form.host" :error="form.errors.host" />
              <form-input :flex="true" :required="true" label="Keyword" type="text" placeholder="Expert Laravel" v-model="form.keyword" :error="form.errors.keyword" />
              <form-radio :flex="true" :required="true" label="Alert when" v-model="form.fails" :error="form.errors.fails" :options="{ exists: 'Keyword exists', notExists: 'Keyword not exists' }" />
            </template>
            <template v-if="form.type == 3">
              <form-input :flex="true" :required="true" label="Host (or URL or IP)" type="text" placeholder="saucisson.o2switch.net" v-model="form.host" :error="form.errors.host" />
              <form-input :flex="true" :required="true" label="Port" type="text" placeholder="2083" v-model="form.port" :error="form.errors.port" />
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

  props: ['monitor', 'types'],

  data() {
    return {
      form: this.$inertia.form({
        name: '',
        type: '',

        // Configuration (mixed)
        host: '',
        keyword: '',
        fails: '',
        port: '',
      }),
    };
  },

  mounted() {
    if (this.monitor) {
      this.form.name = this.monitor.name;
      this.form.type = this.monitor.type;

      this.form.host = this.monitor.configuration.host || '';
      this.form.keyword = this.monitor.configuration.keyword || '';
      this.form.fails = this.monitor.configuration.fails || '';
      this.form.port = this.monitor.configuration.port || '';
    }
  },

  methods: {
    submit() {
      this.form.clearErrors();

      if (this.monitor) this.form.put(route('monitors.update', this.monitor));
      else this.form.post(route('monitors.store'));
    },
  },
};
</script>
