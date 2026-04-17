<script setup>
import { ref } from "vue";
import CreateOwner from "./Create_Partials/CreateOwner.vue";
import CreateRestaurant from "./Create_Partials/CreateRestaurant.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
     unassigned_owners: Array,
})


const tab = ref("owner");
</script>

<template>
  <div class="container py-4 shadow-lg rounded-2" style="max-width: 900px;">

    <!-- HEADER -->
    <div class="mb-4">
      <h4 class="mb-1">Create</h4>
      <div class="text-muted">Manage users and restaurants independently</div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item">
        <button
          class="nav-link"
          :class="{ active: tab === 'owner' }"
          @click="tab = 'owner'"
        >
          User
        </button>
      </li>

      <li class="nav-item">
        <button
          class="nav-link"
          :class="{ active: tab === 'restaurant' }"
          @click="tab = 'restaurant'"
        >
          Restaurant
        </button>
      </li>
    </ul>

    <!-- CONTENT -->
    <div class="card border-0">
      <div class="card-body">

        <CreateOwner v-if="tab === 'owner'" />
        <CreateRestaurant :owners="props.unassigned_owners" v-if="tab === 'restaurant'" />

      </div>
    </div>

  </div>
</template>