import type { Component } from 'vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

/**
 * Marketing page component names (and prefixes) that render inside the
 * public MarketingLayout. Keep in sync with the marketing controllers.
 */
const MARKETING_PAGES = new Set([
    'Home',
    'About',
    'ServiceArea',
    'Contact',
    'Location',
]);

const isMarketingPage = (name: string): boolean => {
    return (
        MARKETING_PAGES.has(name) ||
        name.startsWith('Blog/') ||
        name.startsWith('SeoReport/')
    );
};

/**
 * Format the document title. App/admin pages get the app name appended
 * (e.g. "Log in - All American Web Design"), while marketing pages that
 * already include the brand in their title are used verbatim.
 */
export function formatTitle(title: string | undefined, appName: string): string {
    if (!title) {
        return appName;
    }

    return title.includes(appName) ? title : `${title} - ${appName}`;
}

/**
 * Resolve the persistent layout (or layout stack) for a given Inertia page
 * component name. Shared by the client (app.ts) and SSR (ssr.ts) entries so
 * the two never drift.
 */
export function resolveLayout(name: string): Component | Component[] | null {
    switch (true) {
        case isMarketingPage(name):
            return MarketingLayout;
        case name.startsWith('auth/'):
            return AuthLayout;
        case name.startsWith('settings/'):
            return [AppLayout, SettingsLayout];
        case name.startsWith('Admin/'):
            return [AppLayout, AdminLayout];
        default:
            return AppLayout;
    }
}
