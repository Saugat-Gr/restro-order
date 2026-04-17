<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";

const owners = ref([]);

const props = defineProps({
  owners: Array,
});

const form = useForm({
  owner_id: "",
  name: "",
  email: "",
  phone: "",
  address: "",
  logo: null,
});


const submit = () => {
  form.post(route('restaurants.store'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
         form.reset();
    }
  });
};
</script>

<template>
  <div>
    <div class="mb-4 d-flex justify-content-between align-items-start">
      <div>
        <h4 class="mb-1">Create Restaurant</h4>
        <p class="text-muted mb-0">
          Link an owner and configure restaurant profile
        </p>
      </div>
    </div>

    <div class="vstack gap-3">
      <div class="form-floating">
        <select v-model="form.owner_id" class="form-select">
          <option value="" disabled>Select owner</option>
          <option v-for="o in props.owners" :key="o.id" :value="o.id">
            {{ o.name }} — {{ o.email }}
          </option>
        </select>
        <label>Owner</label>
      </div>

      <div class="form-floating">
        <input
          v-model="form.name"
          class="form-control"
          placeholder="Restaurant name"
        />
        <label>Restaurant name</label>
      </div>

      <div class="form-floating">
        <input
          v-model="form.email"
          class="form-control"
          placeholder="Restaurant name"
        />
        <label>Restaurant Email</label>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input
              v-model="form.phone"
              class="form-control"
              placeholder="Phone"
            />
            <label>Phone</label>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-floating">
            <input
              v-model="form.address"
              class="form-control"
              placeholder="Address"
            />
            <label>Address</label>
          </div>
        </div>
      </div>

      <div>
        <label class="form-label text-muted">Restaurant Logo</label>
        <input
          type="file"
          class="form-control"
          @change="(e) => (form.logo = e.target.files[0])"
        />
      </div>

      <button
        class="btn btn-primary btn-lg w-100 mt-2"
        :disabled="form.processing"
        @click.prevent="submit"
      >
        Create Restaurant →
      </button>
    </div>
  </div>
</template>