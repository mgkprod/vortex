<template>
  <label :class="{ block: !flex, 'flex flex-col md:flex-row': flex }">
    <span v-if="label" :class="{ 'md:mb-0 md:w-1/4': flex }" class="block mb-2 text-sm font-semibold md:py-3"> {{ label }} <span v-if="required" class="text-[#EF3961]">*</span> </span>
    <div class="flex flex-col w-full space-y-2 md:pt-3" :class="{ 'md:w-3/4': flex }">
      <label v-for="(option, value) in options" v-bind:key="value" class="inline-flex items-center">
        <input type="radio" class="bg-[#eff2f5] text-[#009ef7] w-5 h-5 border-0 focus:ring-0" v-bind="$attrs" :name="name" :value="value" v-on="{ ...$listeners, input: (event) => $emit('input', event.target.value) }" /><span class="ml-2 text-sm">{{ option }}</span>
      </label>

      <p v-if="error" class="mt-1 pl-1 text-xs text-[#EF3961]" v-text="error"></p>
    </div>
  </label>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    id: String,
    label: String,
    options: Array | Object,
    error: String,
    flex: { type: Boolean, default: () => false },
    required: { type: Boolean, default: () => false },
  },

  data() {
    return {
      name: '',
    };
  },

  mounted() {
    this.name = Date.now();
  },
};
</script>
