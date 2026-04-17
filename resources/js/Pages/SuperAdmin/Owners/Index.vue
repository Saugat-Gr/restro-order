<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { computed, watch } from "vue";
import {
  CContainer,
  CTable,
  CTableBody,
  CTableHead,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
  CImage,
  CButton,
} from "@coreui/vue";
import debounce from "lodash/debounce";
import CIcon from "@coreui/icons-vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  owners: Array,
  app: Object,
});

const ownersList = computed(() => props.owners ?? []);

const form = useForm({
  owner: null,
  status: "",
  _method: "patch",
});

const filterUser = useForm({
  assigned: props.filters?.assigned ?? "all",
  status: props.filters?.status ?? "all",
});

const toggleOwnerStatus = (owner) => {
  const newStatus = owner.status === "active" ? "inactive" : "active";

  form.status = newStatus;

  form.patch(route("owners.update", owner.id), {
    preserveScroll: true,
    onSuccess: () => {
      owner.status = newStatus;
    },
  });
};

const filterUsers = debounce(() => {
  filterUser.get(route("owners.index"), {
    preserveState: true,
    replace: true,
  });
}, 300);

watch(
  () => [filterUser.assigned, filterUser.status],
  () => {
    filterUsers();
  },
  { deep: true }
);
</script>

<template>
  <Head>
    <title>{{ app?.title || "Owners" }}</title>
  </Head>

  <CContainer class="mt-4">
    <CCard class="shadow-lg border-0 rounded-4 p-4">
      <CCardBody>

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-0">Owners</h4>
            <small class="text-medium-emphasis">
              Manage all restaurant owners
            </small>
          </div>

          <Link :href="route('owners.create')">
            <CButton color="primary">
              <CIcon name="cil-plus" class="me-2" />
              Create Owner
            </CButton>
          </Link>
        </div>

        <!-- FILTERS -->
        <div class="d-flex gap-3 mb-4">
          <CFormSelect v-model="filterUser.assigned" class="w-auto">
            <option value="all">All Owners</option>
            <option value="assigned">Assigned</option>
            <option value="unassigned">Unassigned</option>
          </CFormSelect>

          <CFormSelect v-model="filterUser.status" class="w-auto">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </CFormSelect>
        </div>

        <!-- TABLE -->
        <CTable hover responsive align="middle" class="mb-0">
          <CTableHead>
            <CTableRow class="text-medium-emphasis">
              <CTableHeaderCell>#</CTableHeaderCell>
              <CTableHeaderCell>Owner</CTableHeaderCell>
              <CTableHeaderCell>Email</CTableHeaderCell>
              <CTableHeaderCell>Restaurant</CTableHeaderCell>
              <CTableHeaderCell>Status</CTableHeaderCell>
            </CTableRow>
          </CTableHead>

          <CTableBody>
            <CTableRow
              v-for="(owner, index) in ownersList"
              :key="owner.id"
            >
              <!-- Index -->
              <CTableDataCell>
                {{ index + 1 }}
              </CTableDataCell>

              <!-- Owner -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">
                  <CAvatar
                    :src="owner.avatar ? `/storage/${owner.avatar}` : null"
                    color="secondary"
                    text-color="white"
                  >
                    {{ owner.name.charAt(0) }}
                  </CAvatar>

                  <div>
                    <div class="fw-semibold">{{ owner.name }}</div>
                    <small class="text-medium-emphasis">
                      {{ owner.email }}
                    </small>
                  </div>
                </div>
              </CTableDataCell>

              <!-- Email (optional duplicate removed for cleaner UI) -->
              <CTableDataCell class="text-medium-emphasis">
                {{ owner.email }}
              </CTableDataCell>

              <!-- Restaurant -->
              <CTableDataCell>
                <CBadge
                  :color="owner.restaurant ? 'success' : 'secondary'"
                  shape="rounded-pill"
                >
                  {{ owner.restaurant?.name || "Not Assigned" }}
                </CBadge>
              </CTableDataCell>

              <!-- Status + Toggle -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-3">
          

                  <!-- YOUR ORIGINAL SWITCH (UNCHANGED) -->
                  <label class="switch mb-0">
                    <input
                      type="checkbox"
                      :checked="owner.status === 'active'"
                      @change="() => toggleOwnerStatus(owner)"
                    />
                    <span class="slider"></span>
                  </label>
                </div>
              </CTableDataCell>
            </CTableRow>
          </CTableBody>
        </CTable>

        <!-- EMPTY STATE -->
        <div
          v-if="ownersList.length === 0"
          class="text-center py-5 d-flex justify-center align-items-center gap-2"
        >
          <CIcon
            name="cil-user"
            size="xl"
            class="mb-3 text-medium-emphasis"
          />
          <h6 class="text-medium-emphasis">No owners found</h6>
        </div>

      </CCardBody>
    </CCard>
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
  background-color: #ccc;
  transition: 0.3s;
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
  transition: 0.3s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #321fdb;
}

input:checked + .slider::before {
  transform: translateX(22px);
}
</style>