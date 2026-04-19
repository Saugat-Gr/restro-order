<script setup>
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  staffs: Object,
});

const search = ref("");

const staffsList = computed(() => props.staffs ?? []);


const filteredStaffs = computed(() => {
  if (!search.value) return staffsList.value;

  return staffsList.value.filter((s) =>
    `${s.name} ${s.email}`.toLowerCase().includes(search.value.toLowerCase())
  );
});


const statusForm = useForm({
  status: "",
  _method: "patch",
});


const toggleStaffStatus = (staff) => {
  const originalStatus = staff.status;
  const newStatus = originalStatus === "active" ? "inactive" : "active";

  staff.status = newStatus;

  statusForm.status = newStatus;

  statusForm.patch(route("staffs.update", staff.id), {
    preserveScroll: true,

    onError: () => {
      staff.status = originalStatus;
    },
  });
};


const destroy = (id) => {
  if (confirm("Are you sure you want to delete this staff?")) {
    router.delete(route("staffs.destroy", id), {
      preserveScroll: true,
    });
  }
};
</script>
<template>
  <CContainer class="mt-4">

    <div class="bg-white border-0 rounded-4 shadow-lg p-4">

      <!-- HEADER -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h4 class="mb-0 fw-semibold">Staff Management</h4>
          <small class="text-medium-emphasis">
            Manage restaurant staff and permissions
          </small>
        </div>

        <CButton color="primary" :href="route('staffs.create')">
          <i class="bi bi-plus me-1"></i>
          Add Staff
        </CButton>
      </div>

      <!-- SEARCH -->
      <div class="mb-3">
        <CFormInput
          v-model="search"
          placeholder="Search staff by name or email..."
        />
      </div>

      <!-- TABLE CARD -->
      <div class="border rounded-4 overflow-hidden">

        <CTable hover responsive class="align-middle mb-0">

          <!-- HEAD -->
          <CTableHead class="bg-light">
            <CTableRow class="text-medium-emphasis">
              <CTableHeaderCell>Staff</CTableHeaderCell>
              <CTableHeaderCell>Email</CTableHeaderCell>
              <CTableHeaderCell>Status</CTableHeaderCell>
              <CTableHeaderCell>Joined</CTableHeaderCell>
              <CTableHeaderCell class="text-end">Actions</CTableHeaderCell>
            </CTableRow>
          </CTableHead>

          <!-- BODY -->
          <CTableBody>

            <!-- EMPTY -->
            <CTableRow v-if="filteredStaffs.length === 0">
              <CTableDataCell colspan="5" class="text-center py-5 text-medium-emphasis">
                No staff members found
              </CTableDataCell>
            </CTableRow>

            <!-- ROW -->
            <CTableRow v-for="staff in filteredStaffs" :key="staff.id">

              <!-- STAFF -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">

                  <img
                    v-if="staff.avatar"
                    :src="`/storage/${staff.avatar}`"
                    class="rounded-circle border shadow-sm"
                    width="42"
                    height="42"
                    style="object-fit: cover"
                  />

                  <div
                    v-else
                    class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 42px; height: 42px"
                  >
                    {{ staff.name.charAt(0).toUpperCase() }}
                  </div>

                  <div>
                    <div class="fw-semibold">{{ staff.name }}</div>
                    <small class="text-medium-emphasis">Staff member</small>
                  </div>

                </div>
              </CTableDataCell>

              <!-- EMAIL -->
              <CTableDataCell class="text-medium-emphasis">
                {{ staff.email }}
              </CTableDataCell>

              <!-- STATUS -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">

    
                  <label class="switch mb-0">
                    <input
                      type="checkbox"
                      :checked="staff.status === 'active'"
                      @change="toggleStaffStatus(staff)"
                    />
                    <span class="slider"></span>
                  </label>

                </div>
              </CTableDataCell>

              <!-- DATE -->
              <CTableDataCell class="text-medium-emphasis">
                {{ new Date(staff.created_at).toLocaleDateString() }}
              </CTableDataCell>

              <!-- ACTIONS -->
              <CTableDataCell class="text-end">
                <div class="d-flex justify-content-end gap-2">

                  <CButton
                    size="sm"
                    color="info"
                    variant="outline"
                    :href="route('staffs.show', staff.id)"
                  >
                    <i class="bi bi-eye"></i>
                  </CButton>

                  <CButton
                    size="sm"
                    color="warning"
                    variant="outline"
                    :href="route('staffs.edit', staff.id)"
                  >
                    <i class="bi bi-pencil"></i>
                  </CButton>

                  <CButton
                    size="sm"
                    color="danger"
                    variant="outline"
                    @click="destroy(staff.id)"
                  >
                    <i class="bi bi-trash"></i>
                  </CButton>

                </div>
              </CTableDataCell>

            </CTableRow>

          </CTableBody>

        </CTable>

      </div>

    </div>
  </CContainer>
</template>
<style scoped>
.switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background-color: #cbd5e1;
  transition: 0.25s;
  border-radius: 50px;
}

.slider::before {
  content: "";
  position: absolute;
  height: 18px;
  width: 18px;
  left: 3px;
  top: 3px;
  background-color: white;
  transition: 0.25s;
  border-radius: 50%;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

input:checked + .slider {
  background-color: #321fdb;
}

input:checked + .slider::before {
  transform: translateX(22px);
}
</style>