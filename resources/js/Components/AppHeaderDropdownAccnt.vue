<script setup>
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { CDropdown } from "@coreui/vue";

const page = usePage();

const avatar = page.props.auth.user.avatar
  ? `/storage/${page.props.auth.user.avatar}`
  : null;

const itemsCount = 42;
</script>

<template>
  <CDropdown placement="bottom-end" variant="nav-item">
    <CDropdownToggle class="py-0 pe-0" :caret="false">
      <CAvatar :src="avatar" size="md" />
    </CDropdownToggle>

    <!-- DropDownHeader: 1: Account -->
    <CDropdownMenu class="pt-0">
      <CDropdownHeader
        component="h6"
        class="bg-body-secondary text-body-secondary fw-semibold rounded-top"
      >
        Settings
      </CDropdownHeader>

      <Link href="/profile" class="text-decoration-none">
        <CDropdownItem> <CIcon icon="cil-user" /> Profile </CDropdownItem>
      </Link>

      <Link :href="`/restaurant/${page.props.auth.user.restaurant_id}/edit`">
        <CDropdownItem> <CIcon icon="cil-settings" /> Settings </CDropdownItem>
      </Link>
      <CDropdownDivider />

      <Link href="/logout" class="text-decoration-none" method="POST" as="div">
        <CDropdownItem> <CIcon icon="cil-lock-locked" /> Logout </CDropdownItem>
      </Link>
    </CDropdownMenu>
  </CDropdown>
</template>
