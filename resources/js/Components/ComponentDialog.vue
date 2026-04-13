<script setup>
import { CModal, CButton } from "@coreui/vue";

const props = defineProps({
  visible: Boolean,
  message: { type: String, default: "Are you sure?" },
});

const emit = defineEmits(["update:visible", "confirm", "cancel"]);

const cancel = () => {
  emit("update:visible", false);
  emit("cancel");
};

const confirm = () => {
  emit("confirm");
  emit("update:visible", false);
};
</script>

<template>
  <CModal
    :visible="visible"
    title="Confirm Delete"
    backdrop="static"
    centered
    autoclose={false} 
  >
    <div class="modal-body">
      {{ message }}
    </div>

    <!-- ✅ Custom footer div NOW WORKS -->
    <div class="d-flex justify-content-end gap-2 p-3 border-top">
      <CButton color="secondary" @click="cancel">Cancel</CButton>
      <CButton color="danger" @click="confirm">Delete</CButton>
    </div>
  </CModal>
</template>