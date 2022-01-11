export type State = {
    lastName: string | null;
    firstName: string | null;
    login: string | null;
    isLogged: boolean;
};

export const state: State = {
    lastName: null,
    firstName: null,
    login: null,
    isLogged: false,
};
