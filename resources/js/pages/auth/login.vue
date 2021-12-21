<template>
  <div class="w-full max-w-md mx-auto">
    <form class="flex flex-col p-10 mx-auto bg-white rounded-md shadow-xl shadow-gray-200" @submit.prevent="submit">
      <form-input class="mb-6" label="Email" v-model="form.email" :error="form.errors.email" autofocus autocomplete="email" />
      <form-input class="mb-6" label="Password" type="password" v-model="form.password" :error="form.errors.password" autocomplete="current-password" />

      <button class="inline-block disabled:opacity-60 w-full px-4 py-3 mt-4 font-bold text-sm transition duration-200 rounded-md ease-in-out bg-[#0194F6] hover:bg-[#0095e8] text-white focus:outline-none"><i class="mr-1 opacity-50 fas fa-sign-in-alt"></i> Continue</button>
    </form>
  </div>
</template>

<script>
export default {
  layout: require('../../layouts/gate').default,

  data() {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
      }),
    };
  },

  methods: {
    submit() {
      this.form.clearErrors();
      this.form.post(this.route('login'));

      this.form.password = '';
    },
  },
};
</script>
