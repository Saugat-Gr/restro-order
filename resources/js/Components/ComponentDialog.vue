<script setup>
import { CModal, CButton } from "@coreui/vue";

const props = defineProps({
  visible: Boolean,
  message: { type: String, default: "Are you sure?" },
  button_text: {type:String, default:"Delete"}
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
    autoclose="'static'" 
  >
    <div class="modal-body">
      {{ message }}
    </div>

    <!-- ✅ Custom footer div NOW WORKS -->
    <div class="d-flex justify-content-end gap-2 p-3 border-top">
      <CButton color="secondary" @click="cancel" v-if="button_text !== 'Cancel'">Cancel</CButton>
      <CButton color="danger" @click="confirm">{{ button_text }}</CButton>
    </div>
  </CModal>
</template>