<script setup>

 import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CHeader } from '@coreui/vue';

  defineOptions({
    layout: AuthenticatedLayout,
  }); 

</script>

<template>

  <CHeader>This is header of the dashboard</CHeader>

</template>