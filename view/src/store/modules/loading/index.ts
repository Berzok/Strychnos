import {
  createStore,
  Store as VuexStore,
  CommitOptions,
  DispatchOptions,
  createLogger,
} from 'vuex';
import { State as LoadingState, state as stateState } from './state';
import { Mutations as LoadingMutations, mutations as mutationsState } from './mutations';
import { Actions as LoadingActions, actions as loadingActions } from './actions';
import { Getters as LoadingGetters, getters as gloadingGetters } from './getters';

export const loadingStore = createStore<LoadingState>({
  plugins: process.env.NODE_ENV === 'development' ? [createLogger()] : [],
  state: stateState,
  mutations: mutationsState,
  actions: loadingActions,
  getters: gloadingGetters,
});

export type LoadingStore = Omit<
  VuexStore<LoadingState>,
  'getters' | 'commit' | 'dispatch'
> & {
  commit<K extends keyof LoadingMutations, P extends Parameters<LoadingMutations[K]>[1]>(
    key: K,
    payload: P,
    options?: CommitOptions
  ): ReturnType<LoadingMutations[K]>
} & {
  dispatch<K extends keyof LoadingActions>(
    key: K,
    payload: Parameters<LoadingActions[K]>[1],
    options?: DispatchOptions
  ): ReturnType<LoadingActions[K]>
} & {
  getters: {
    [K in keyof LoadingGetters]: ReturnType<LoadingGetters[K]>
  }
};

export function loadStore(): LoadingStore {
  return loadingStore as LoadingStore;
}
