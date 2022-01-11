import { ActionContext } from 'vuex';
import { Mutations, MutationType } from './mutations';
import { State } from './state';

export enum ActionTypes {
  beginLoading = 'BEGIN_LOADING',
  endLoading = 'END_LOADING',
}

type ActionAugments = Omit<ActionContext<State, State>, 'commit'> & {
  commit<K extends keyof Mutations>(
    key: K,
    payload: Parameters<Mutations[K]>[1]
  ): ReturnType<Mutations[K]>
};

export type Actions = {
  [ActionTypes.beginLoading](context: ActionAugments): void
  [ActionTypes.endLoading](context: ActionAugments): void
};

export const actions: Actions = {
  [ActionTypes.beginLoading]({ commit }) {
    // in case of mutation without payload we have to pass it undefined
    commit(MutationType.beginLoading, undefined);
  },
  [ActionTypes.endLoading]({ commit }) {
    // in case of mutation without payload we have to pass it undefined
    commit(MutationType.endLoading, undefined);
  },

};
