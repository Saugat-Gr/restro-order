export default function getNav(page) {
  return [
    {
      name: 'Dashboard',
      to: '/dashboard',
      icon: 'cil-home',
      roles: ['super-admin', 'owner', 'staff'],
    },

     {
      name: 'Owners',
      to: '/owners',
      icon: 'cil-people',
      roles: ['super-admin'],
    },
  

    {
      component: 'CNavGroup',
      name: 'Settings',
      icon: 'cil-settings',
      roles: ['owner'],
      items: [
        {
          name: 'General',
          to: `/restaurant/${page.props.auth.user.restaurant_id}/edit`,
          roles: ['owner'],
          icon: 'cil-star'
        },
        {
          name: 'Menu',
          to: '/restaurants/menu',
          roles: ['owner'],
          icon: 'cil-star'
        },
        {
          name: 'Orders',
          to: '/restaurants/orders',
          roles: ['owner'],
          icon: 'cil-star'
        },
      ],
    },

    {
      name: 'Hi',
      to: '/',
      icon: 'cil-speedometer',
      roles: ['staff'],
    },

   
  ];
  
}