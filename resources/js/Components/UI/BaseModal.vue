<script setup>
import {
  CModal,
  CModalHeader,
  CModalTitle,
  CModalBody,
  CModalFooter,
} from "@coreui/vue";

defineProps({
  visible: Boolean,
  title: String,
});

defineEmits(["close"]);
</script>

<template>
  <CModal :visible="visible" @close="$emit('close')" backdrop="static">
    <CModalHeader>
      <CModalTitle>{{ title }}</CModalTitle>
    </CModalHeader>

    <CModalBody>
      <slot />
    </CModalBody>

    <CModalFooter>
      <slot name="footer" />
    </CModalFooter>
  </CModal>
</template>