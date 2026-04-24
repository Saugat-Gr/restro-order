<script setup>
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  staffs: Object,
});

const search = ref("");

const staffsList = computed(() => props.staffs.data ?? []);

const hasPagination = computed(() => props.staffs?.last_page > 1);

const filteredStaffs = computed(() => {
  if (!search.value) return staffsList.value;

  return staffsList.value.filter((s) =>
    `${s.name} ${s.email}`.toLowerCase().includes(search.value.toLowerCase())
  );
});

/* IMPORT */
const fileInpute = ref(null);
const file = ref(null);

const handleFile = (e) => {
  file.value = e.target.files[0];
};

const uploadFile = () => {
  if (!file.value) return;

  const formData = new FormData();
  formData.append("file", file.value);

  router.post(route("staffs.import"), formData, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      file.value = null;
      if (fileInput.value) {
        fileInput.value.value = "";
      }
    },
  });
};

/* STATUS */
const statusForm = useForm({
  status: "",
  _method: "patch",
});

const toggleStaffStatus = (staff) => {
  const original = staff.status;
  const updated = original === "active" ? "inactive" : "active";

  staff.status = updated;
  statusForm.status = updated;

  statusForm.patch(route("staffs.update", staff.id), {
    preserveScroll: true,
    onError: () => (staff.status = original),
  });
};

/* DELETE */
const destroy = (id) => {
  if (confirm("Delete this staff?")) {
    router.delete(route("staffs.destroy", id), {
      preserveScroll: true,
    });
  }
};

const changePage = (page) => {
  router.get(
    route("staffs.index"),
    {page},
    { preserveState: true, preserveScroll: true }
  );
};
</script>

<template>
  <CContainer class="mt-4">
    <!-- HEADER CARD -->
    <CCard class="mb-4">
      <CCardBody>
        <div
          class="d-flex justify-content-between align-items-center flex-wrap gap-3"
        >
          <div>
            <h4 class="mb-0">Staff Management</h4>
            <small class="text-medium-emphasis">
              Manage restaurant staff, roles & permissions
            </small>
          </div>

          <div class="d-flex gap-2 align-items-center flex-wrap justify-end">
            <!-- FILE IMPORT -->
            <CFormInput
              type="file"
              ref="fileInput"
              size="sm"
              accept=".csv,.xlsx,.xls"
              @change="handleFile"
            />

            <div class="d-flex gap-2">
              <CButton
                color="success"
                class="text-white"
                size="sm"
                @click="uploadFile"
                :disabled="!file"
              >
                Import
              </CButton>

              <!-- ADD STAFF -->
              <CButton color="primary" size="sm" :href="route('staffs.create')">
                Add Staff
              </CButton>
            </div>
          </div>
        </div>
      </CCardBody>
    </CCard>

    <!-- SEARCH -->
    <CCard class="mb-3">
      <CCardBody>
        <CFormInput
          v-model="search"
          placeholder="Search staff by name or email..."
        />
      </CCardBody>
    </CCard>

    <!-- TABLE -->
    <CCard>
      <CCardBody class="p-0">
        <CTable hover responsive class="mb-0 align-middle">
          <CTableHead color="light">
            <CTableRow>
              <CTableHeaderCell>Staff</CTableHeaderCell>
              <CTableHeaderCell>Email</CTableHeaderCell>
              <CTableHeaderCell>Status</CTableHeaderCell>
              <CTableHeaderCell>Joined</CTableHeaderCell>
              <CTableHeaderCell class="text-center">Actions</CTableHeaderCell>
            </CTableRow>
          </CTableHead>

          <CTableBody>
            <CTableRow v-if="filteredStaffs.length === 0">
              <CTableDataCell
                colspan="5"
                class="text-center text-medium-emphasis py-4"
              >
                No staff found
              </CTableDataCell>
            </CTableRow>

            <CTableRow v-for="staff in filteredStaffs" :key="staff.id">
              <!-- STAFF -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">
                  <CAvatar
                    v-if="staff.avatar"
                    :src="`/storage/${staff.avatar}`"
                  />

                  <CAvatar v-else color="primary">
                    {{ staff.name.charAt(0) }}
                  </CAvatar>

                  <div>
                    <div class="fw-semibold">{{ staff.name }}</div>
                    <small class="text-medium-emphasis">Staff</small>
                  </div>
                </div>
              </CTableDataCell>

              <!-- EMAIL -->
              <CTableDataCell class="text-medium-emphasis">
                {{ staff.email }}
              </CTableDataCell>

              <!-- STATUS -->
              <CTableDataCell>

                 <label class="switch m-0">
                <input
                  type="checkbox"
                  :checked="staff.status === 'active'"
                  @change="() =>
                    toggleStaffStatus(staff )
                  "
                />
                <span class="slider"></span>
              </label>
              </CTableDataCell>

              <!-- DATE -->
              <CTableDataCell class="text-medium-emphasis">
                {{ new Date(staff.created_at).toLocaleDateString() }}
              </CTableDataCell>

              <!-- ACTIONS -->
              <CTableDataCell>
                <div class="d-flex gap-2 align-items-center justify-center">
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
      </CCardBody>
    </CCard>

     <div
        v-if="hasPagination"
        class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3"
      >
        <div class="text-muted small">
          Showing {{ props.staffs.from }} to {{ props.staffs.to }} of
          {{ props.staffs.total }}
        </div>

        <div class="d-flex align-items-center gap-3">
          <CPagination>
            <CPaginationItem
              :disabled="props.staffs.current_page === 1"
              @click="changePage(props.staffs.current_page - 1)"
            >
              Prev
            </CPaginationItem>

            <CPaginationItem
              :disabled="props.staffs.current_page === props.staffs.last_page"
              @click="changePage(props.staffs.current_page + 1)"
            >
              Next
            </CPaginationItem>
          </CPagination>

        </div>
      </div>

      <!-- EMPTY -->
      <div v-if="staffsList.length === 0" class="text-center py-5 text-medium-emphasis">
        No menu items found
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