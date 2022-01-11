import { MutationType } from './mutations';
export var ActionTypes;
(function (ActionTypes) {
    ActionTypes["login"] = "LOGIN";
    ActionTypes["logout"] = "LOGOUT";
    ActionTypes["setFirstName"] = "SET_FIRST_NAME";
    ActionTypes["setLastName"] = "SET_LAST_NAME";
    ActionTypes["setLogin"] = "SET_LOGIN";
})(ActionTypes || (ActionTypes = {}));
export const actions = {
    [ActionTypes.login]({ commit }, login) {
        commit(MutationType.setLogin, login);
        // in case of mutation without payload we have to pass it undefined
        commit(MutationType.login, undefined);
    },
    [ActionTypes.logout]({ commit }) {
        // in case of mutation without payload we have to pass it undefined
        commit(MutationType.logout, undefined);
    },
    [ActionTypes.setFirstName]({ commit }, firstName) {
        commit(MutationType.setFirstName, firstName);
    },
    [ActionTypes.setLastName]({ commit }, lastname) {
        commit(MutationType.setLastName, lastname);
    },
    [ActionTypes.setLogin]({ commit }, login) {
        commit(MutationType.setLogin, login);
    },
};
//# sourceMappingURL=actions.js.map