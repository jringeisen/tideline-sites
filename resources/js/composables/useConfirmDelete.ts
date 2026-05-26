import { router } from '@inertiajs/vue3';

/**
 * Returns a helper that prompts for confirmation before issuing a DELETE
 * visit. Centralises the window.confirm + router.delete pattern repeated
 * across admin index/detail pages.
 */
export function useConfirmDelete() {
    return (url: string, message: string): void => {
        if (!window.confirm(message)) {
            return;
        }

        router.delete(url);
    };
}
