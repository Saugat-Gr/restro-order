<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";

import {
  CContainer,
  CCard,
  CCardBody,
  CTable,
  CTableHead,
  CTableRow,
  CTableHeaderCell,
  CTableBody,
  CTableDataCell,
  CBadge,
  CAvatar,
  CPagination,
  CPaginationItem,
} from "@coreui/vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  logs: Object,
});


const logsList = computed(() => props.logs?.data ?? []);
const meta = computed(() => props.logs);

const formatEvent = (event) => {
  return event
    .replace(".", " ")
    .replace(/\b\w/g, (l) => l.toUpperCase());
};
</script>

<template>
  <Head title="Activity Logs" />

  <CContainer class="mt-4">
    <CCard class="shadow-sm border-0">
      <CCardBody>

        <!-- HEADER -->
        <div class="mb-4">
          <h4 class="mb-0">Activity Logs</h4>
          <small class="text-medium-emphasis">
            System-wide activity history
          </small>
        </div>

        <!-- TABLE -->
        <CTable hover responsive align="middle">

          <CTableHead>
            <CTableRow>
              <CTableHeaderCell>#</CTableHeaderCell>
              <CTableHeaderCell>Logged By</CTableHeaderCell>
              <CTableHeaderCell>Activity</CTableHeaderCell>
              <CTableHeaderCell>Description</CTableHeaderCell>
              <CTableHeaderCell>Date</CTableHeaderCell>
            </CTableRow>
          </CTableHead>

          <CTableBody>
            <CTableRow v-for="(log, index) in logsList" :key="log.id">

              <!-- INDEX -->
              <CTableDataCell>
            {{ index + 1 }}
              </CTableDataCell>

              <!-- USER -->
              <CTableDataCell>
                <div class="d-flex align-items-center gap-2">
                  <CAvatar color="primary" text-color="white">
                    {{ log.user?.name?.charAt(0) ?? "S" }}
                  </CAvatar>

                  <div>
                    <div class="fw-semibold">
                      {{ log.user?.name ?? "System" }}
                    </div>
                    <small class="text-medium-emphasis">
                      {{ log.user?.email ?? "-" }}
                    </small>
                  </div>
                </div>
              </CTableDataCell>

              <!-- EVENT -->
              <CTableDataCell>
                <CBadge color="info" shape="rounded-pill">
                  {{ formatEvent(log.event_type) }}
                </CBadge>
              </CTableDataCell>

              <!-- DESCRIPTION -->
              <CTableDataCell class="text-medium-emphasis">
                {{ log.description }}
              </CTableDataCell>

              <!-- DATE -->
              <CTableDataCell class="text-medium-emphasis">
                {{ new Date(log.created_at).toLocaleString() }}
              </CTableDataCell>

            </CTableRow>
          </CTableBody>
        </CTable>

        <!-- EMPTY STATE -->
        <div
          v-if="logsList.length === 0"
          class="text-center py-5"
        >
          <div class="text-medium-emphasis">
            No activity logs found
          </div>
        </div>

        <!-- PAGINATION -->
        <div
          v-if="meta?.last_page > 1"
          class="d-flex justify-content-end mt-3"
        >
          <CPagination>
            <CPaginationItem
              v-for="page in meta.last_page"
              :key="page"
              :active="page === meta.current_page"
              @click="$inertia.get(meta.path + '?page=' + page)"
            >
              {{ page }}
            </CPaginationItem>
          </CPagination>
        </div>

      </CCardBody>
    </CCard>
  </CContainer>
</template>