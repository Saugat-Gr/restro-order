<script setup>
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { CDropdown } from "@coreui/vue";

const page = usePage();

const avatar = page.props.auth.user.avatar
  ? `/storage/${page.props.auth.user.avatar}`
  : avatar; 

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
        class="bg-body-secondary text-body-secondary fw-semibold mb-2 rounded-top"
      >
        Account
      </CDropdownHeader>
      <CDropdownItem>
        <CIcon icon="cil-bell" /> Updates
        <CBadge color="info" class="ms-auto">{{ itemsCount }}</CBadge>
      </CDropdownItem>
      <CDropdownItem>
        <CIcon icon="cil-envelope-open" /> Messages
        <CBadge color="success" class="ms-auto">{{ itemsCount }}</CBadge>
      </CDropdownItem>
      <CDropdownItem>
        <CIcon icon="cil-task" /> Tasks
        <CBadge color="danger" class="ms-auto">{{ itemsCount }}</CBadge>
      </CDropdownItem>
      <CDropdownItem>
        <CIcon icon="cil-comment-square" /> Comments
        <CBadge color="warning" class="ms-auto">{{ itemsCount }}</CBadge>
      </CDropdownItem>

      <!-- DropdownHeader 2: Settings-->
      <CDropdownHeader
        component="h6"
        class="bg-body-secondary text-body-secondary fw-semibold my-2"
      >
        Settings
      </CDropdownHeader>

      <Link href="/profile" class="text-decoration-none">
        <CDropdownItem> <CIcon icon="cil-user" /> Profile </CDropdownItem>
      </Link>

      <CDropdownItem> <CIcon icon="cil-settings" /> Settings </CDropdownItem>

      <CDropdownItem>
        <CIcon icon="cil-dollar" /> Payments
        <CBadge color="secondary" class="ms-auto">{{ itemsCount }}</CBadge>
      </CDropdownItem>

      <CDropdownItem>
        <CIcon icon="cil-file" /> Projects
        <CBadge color="primary" class="ms-auto">{{ itemsCount }}</CBadge>
      </CDropdownItem>

      <CDropdownDivider />
      

      <Link class="text-decoration-none" href="/logout" method="POST" as="button">
        <CDropdownItem> <CIcon icon="cil-lock-locked" /> Logout </CDropdownItem>
      </Link>

    </CDropdownMenu>
  </CDropdown>
</template>
