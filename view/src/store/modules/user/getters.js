export const ANONYMOUS_LOGIN = 'anonymous';
export const getters = {
    getLogin(state) {
        if (!state.isLogged || state.login === null)
            return ANONYMOUS_LOGIN;
        return state.login;
    },
    getFirstName(state) {
        if (!state.isLogged)
            return null;
        return state.firstName;
    },
    getLastName(state) {
        if (!state.isLogged)
            return null;
        return state.lastName;
    },
    isLogged(state) {
        return state.isLogged;
    },
};
//# sourceMappingURL=getters.js.map