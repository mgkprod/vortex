<template>
  <label :class="{ block: !flex, 'flex flex-col md:flex-row': flex }">
    <span v-if="label" :class="{ 'md:mb-0 md:w-1/4': flex }" class="block mb-2 text-sm font-semibold md:py-3"> {{ label }} <span v-if="required" class="text-[#EF3961]">*</span> </span>
    <div class="w-full" :class="{ 'md:w-3/4': flex }">
      <select class="w-full px-3 py-3 text-sm leading-tight text-[#5D6176] rounded-md placeholder-[#979BAE] focus:bg-[#EBF1F6] transition duration-200 ease-in-out bg-[#F3F7F9] appearance-none border-0 focus:ring-0 focus:outline-none" v-bind="$attrs" v-on="{ ...$listeners, input: (event) => $emit('input', event.target.value) }">
        <option v-for="(option, value) in options" v-bind:key="value" :value="value">
          {{ option }}
        </option>
      </select>

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
};
</script>
