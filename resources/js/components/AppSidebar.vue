<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    FileText,
    FolderOpen,
    Inbox,
    LayoutGrid,
    Tag as TagIcon,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { dashboard } from '@/routes';
import categories from '@/routes/admin/categories';
import contactInquiries from '@/routes/admin/contact-inquiries';
import posts from '@/routes/admin/posts';
import tags from '@/routes/admin/tags';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.is_admin === true);
const { isCurrentUrl } = useCurrentUrl();

const adminNavItems = computed<NavItem[]>(() => [
    { title: 'Posts', href: posts.index().url, icon: FileText },
    { title: 'Categories', href: categories.index().url, icon: FolderOpen },
    { title: 'Tags', href: tags.index().url, icon: TagIcon },
    {
        title: 'Contact inquiries',
        href: contactInquiries.index().url,
        icon: Inbox,
    },
]);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />

            <SidebarGroup v-if="isAdmin" class="px-2 py-0">
                <SidebarGroupLabel>Admin</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem
                        v-for="item in adminNavItems"
                        :key="item.title"
                    >
                        <SidebarMenuButton
                            as-child
                            :is-active="isCurrentUrl(item.href)"
                            :tooltip="item.title"
                        >
                            <Link :href="item.href">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
