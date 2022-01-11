import { MutationType } from './mutations';
export var ActionTypes;
(function (ActionTypes) {
    ActionTypes["beginLoading"] = "BEGIN_LOADING";
    ActionTypes["endLoading"] = "END_LOADING";
})(ActionTypes || (ActionTypes = {}));
export const actions = {
    [ActionTypes.beginLoading]({ commit }) {
        // in case of mutation without payload we have to pass it undefined
        commit(MutationType.beginLoading, undefined);
    },
    [ActionTypes.endLoading]({ commit }) {
        // in case of mutation without payload we have to pass it undefined
        commit(MutationType.endLoading, undefined);
    },
};
//# sourceMappingURL=actions.js.map