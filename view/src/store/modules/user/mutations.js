export var MutationType;
(function (MutationType) {
    MutationType["setLastName"] = "SET_LAST_NAME";
    MutationType["setFirstName"] = "SET_FIRST_NAME";
    MutationType["setLogin"] = "SET_LOGIN";
    MutationType["login"] = "LOGIN";
    MutationType["logout"] = "LOGOUT";
})(MutationType || (MutationType = {}));
export const mutations = {
    [MutationType.setFirstName](state, firstName) {
        state.firstName = firstName;
    },
    [MutationType.setLastName](state, lastName) {
        state.lastName = lastName;
    },
    [MutationType.setLogin](state, login) {
        state.login = login;
    },
    [MutationType.login](state) {
        state.isLogged = true;
    },
    [MutationType.logout](state) {
        state.firstName = null;
        state.lastName = null;
        state.login = null;
        state.isLogged = false;
    },
};