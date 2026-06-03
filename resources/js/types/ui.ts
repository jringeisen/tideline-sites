export type Appearance = 'light' | 'dark' | 'system';
export type ResolvedAppearance = 'light' | 'dark';

export type AppVariant = 'header' | 'sidebar';

export type Company = {
    name: string;
    email: string;
};

export type FlashToast = {
    type: 'success' | 'info' | 'warning' | 'error';
    message: string;
};
