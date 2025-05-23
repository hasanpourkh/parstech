<template>
    <div>
      <FormKit
        type="form"
        :actions="true"
        submit-label="ذخیره اطلاعات"
        @submit="handleSubmit"
      >
        <FormKit
          v-for="field in fields"
          :key="field.name"
          v-bind="field"
        />
      </FormKit>
      <div v-if="success" class="alert alert-success mt-3">
        اطلاعات با موفقیت ذخیره شد!
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const props = defineProps({
    fields: {
      type: Array,
      required: true
    }
  });

  const success = ref(false);

  function handleSubmit(values) {
    // اینجا می‌توانید اطلاعات را با ajax به بک‌اند بفرستید
    // به عنوان تست:
    console.log('فرم ارسال شد:', values);
    success.value = true;
  }
  </script>
