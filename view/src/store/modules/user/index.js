import {createStore, createLogger,} from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import {state as stateState} from './state';
import {mutations as mutationsState} from './mutations';
import {actions as userActions} from './actions';
import {getters as guserGetters} from './getters';

export const userStore = createStore({
    plugins: process.env.NODE_ENV === 'development' ? [createLogger(), createPersistedState({
        key: 'auth'
    })] : [],
    state: stateState,
    mutations: mutationsState,
    actions: userActions,
    getters: guserGetters,
});

export function useStore() {
    return userStore;
}