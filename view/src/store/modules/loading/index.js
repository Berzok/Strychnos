import { createStore, createLogger, } from 'vuex';
import { state as stateState } from './state';
import { mutations as mutationsState } from './mutations';
import { actions as loadingActions } from './actions';
import { getters as gloadingGetters } from './getters';
export const loadingStore = createStore({
    plugins: process.env.NODE_ENV === 'development' ? [createLogger()] : [],
    state: stateState,
    mutations: mutationsState,
    actions: loadingActions,
    getters: gloadingGetters,
});
export function loadStore() {
    return loadingStore;
}
//# sourceMappingURL=index.js.map