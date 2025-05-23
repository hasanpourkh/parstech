import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Vue 3 و FormKit
import { createApp } from 'vue';
import { plugin as FormKitPlugin, defaultConfig } from '@formkit/vue';
import ServiceForm from './components/ServiceForm.vue';

// فیلدهای فرم را به صورت window متغیر می‌گذاریم تا از blade هم قابل تغییر باشد
window.serviceFormFields = window.serviceFormFields || [
    { type: 'text', name: 'name', label: 'نام', validation: 'required' },
    { type: 'text', name: 'family', label: 'نام خانوادگی', validation: 'required' },
    { type: 'text', name: 'nid', label: 'کد ملی', validation: 'required' },
    { type: 'date', name: 'birth', label: 'تاریخ تولد' },
    { type: 'tel', name: 'father_phone', label: 'شماره تماس پدر' },
    { type: 'file', name: 'picture', label: 'تصویر' }
];

// فقط اگر المنت وجود داشت Vue mount شود
if (document.getElementById('dynamic-service-form')) {
    const app = createApp({
        data() {
            return {
                fields: window.serviceFormFields
            }
        },
        template: `<ServiceForm :fields="fields" />`
    });
    app.component('ServiceForm', ServiceForm);
    app.use(FormKitPlugin, defaultConfig);
    app.mount('#dynamic-service-form');
}
