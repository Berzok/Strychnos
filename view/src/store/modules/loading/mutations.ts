import { State } from './state';

export enum MutationType {
  beginLoading = 'BEGIN_LOADING',
  endLoading = 'END_LOADING',
}

export type Mutations = {
  [MutationType.beginLoading](state: State): void
  [MutationType.endLoading](state: State): void
};

export const mutations: Mutations = {
  [MutationType.beginLoading](state: State) {
    state.pendingLoading += 1;
  },
  [MutationType.endLoading](state: State) {
    state.pendingLoading -= 1;
    // TODO : fire an error instead
    if (state.pendingLoading < 0) {
      state.pendingLoading = 0;
    }
  },
};
