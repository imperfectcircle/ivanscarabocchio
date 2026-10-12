export type AuthUser = {
    id: number;
    name: string;
    email: string;
};

/**
 * Props shared with every Inertia page by HandleInertiaRequests.
 */
export type SharedProps = {
    auth: {
        user: AuthUser | null;
    };
    flash: {
        status: string | null;
    };
};
