<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';



// Access user from page props
const page = usePage();
const user = computed(() => page.props.auth.user);

// Determine correct dashboard route
const dashboardHref = computed(() =>
    user.value?.role === 'admin' ? '/admin/dashboard' : '/dashboard'
);

// Main nav items based on user role
const mainNavItems = computed<NavItem[]>(() => {
  if (!user.value) return []; // no user, no nav

  switch (user.value.role) {
    case 'admin':
      return [
        {
          title: 'Admin Dashboard',
          href: '/admin/dashboard',
          icon: LayoutGrid,
        },
        {
          title: 'User Management',
          href: '/admin/users',
          icon: Folder,
        },
        {
          title: 'Reports',
          href: '/admin/reports',
          icon: BookOpen,
        },
      ];

    case 'lgu':
      return [
        {
          title: 'LGU Dashboard',
          href: '/lgu/dashboard',
          icon: LayoutGrid,
        },
        {
          title: 'Community Services',
          href: '/lgu/services',
          icon: Folder,
        },
        {
          title: 'Requests',
          href: '/lgu/requests',
          icon: BookOpen,
        },
      ];

    default: // regular user
      return [
        {
          title: 'Dashboard',
          href: '/dashboard',
          icon: LayoutGrid,
        },
        {
          title: 'My Profile',
          href: '/profile',
          icon: Folder,
        },
        {
          title: 'Help & Support',
          href: '/help',
          icon: BookOpen,
        },
      ];
  }
});


</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
