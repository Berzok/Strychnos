import {createStore, createLogger,} from 'vuex';
import {state as authenticationState} from './state';
import {mutations as mutationsState} from './mutations';
import {actions as actionsState} from './actions';
import {getters as authenticationGetters} from './getters';

export const authenticatioStore = createStore({
    plugins: process.env.NODE_ENV === 'development' ? [createLogger()] : [],
    state: authenticationState,
    mutations: mutationsState,
    actions: actionsState,
    getters: authenticationGetters,
});

export function useStore() {
    return authenticatioStore;
}

//# sourceMappingURL=index.js.map