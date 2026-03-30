// _nav.js
export default [
  // Dashboard - everyone
  {
    name: 'Dashboard',
    to: '/',
    icon: 'cil-home',
    roles: ['super-admin', 'owner', 'staff'],
  },

  {
     name: 'Hi',
     to: '/',
     icon: 'cil-speedometer',
     roles: ['staff'],
  },

  {
     name: 'owners',
     to: '/owners',
     icon: 'cil-people',
     roles: ['super-admin'],
  }

]