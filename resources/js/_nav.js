export default [
  // Dashboard - visible to everyone
  {
    name: 'Dashboard',
    to: '/dashboard',
    icon: 'cil-home',
    roles: ['super-admin', 'owner', 'staff'],
  },

 

  // Foldable group under Restaurant
  {
    component: 'CNavGroup',
    name: 'Restaurant',
    icon: 'cil-restaurant',
    roles: ['owner'],
    items: [
      {
        name: 'Settings',
        to: '/restaurants/settings',
        roles: ['owner'],
      },
      {
        name: 'Menu',
        to: '/restaurants/menu',
        roles: ['owner'],
      },
      {
        name: 'Orders',
        to: '/restaurants/orders',
        roles: ['owner'],
      },
    ],
  },

  // Staff-specific link
  {
    name: 'Hi',
    to: '/',
    icon: 'cil-speedometer',
    roles: ['staff'],
  },

  // Super-admin link
  {
    name: 'Owners',
    to: '/owners',
    icon: 'cil-people',
    roles: ['super-admin'],
  },
];